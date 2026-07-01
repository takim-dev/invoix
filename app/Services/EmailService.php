<?php

namespace App\Services;

use App\Models\SettingModel;

class EmailService
{
    private SettingModel $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    /**
     * Send an email using the app's configured SMTP settings.
     * Returns true on success, false on failure.
     */
    public function send(string $to, string $subject, string $body, ?string $toName = null): bool
    {
        $emailConfig = json_decode($this->settingModel->getSetting('email_config', '{}'), true);

        if (empty($emailConfig['host'])) {
            log_message('error', 'EmailService: SMTP host not configured.');
            return false;
        }

        $appName = $this->settingModel->getSetting('app_name', 'InvoiceApp');

        $emailService = \Config\Services::email();
        $emailService->initialize($this->buildConfig($emailConfig));
        $emailService->setTo($to);
        $emailService->setFrom(
            $emailConfig['from_email'] ?? $emailConfig['user'] ?? '',
            $emailConfig['from_name'] ?? $appName
        );
        $emailService->setSubject($subject);
        $emailService->setMessage($body);

        $sent = $emailService->send();

        if (!$sent) {
            log_message('error', 'EmailService: Failed to send to ' . $to);
        }

        return (bool) $sent;
    }

    /**
     * Get SMTP config values for external use (e.g., email debug output).
     */
    public function getConfig(): array
    {
        return json_decode($this->settingModel->getSetting('email_config', '{}'), true) ?: [];
    }

    /**
     * Build CodeIgniter Email config array from stored settings.
     */
    private function buildConfig(array $config): array
    {
        $mailType = strtolower($config['mail_type'] ?? 'html');
        if (!in_array($mailType, ['html', 'text'], true)) {
            $mailType = 'html';
        }

        return [
            'SMTPHost'  => $config['host'] ?? '',
            'SMTPPort'  => (int) ($config['port'] ?? 587),
            'SMTPUser'  => $config['user'] ?? '',
            'SMTPPass'  => $config['pass'] ?? '',
            'SMTPCrypto'=> strtolower($config['encryption'] ?? 'tls'),
            'SMTPTimeout' => 10,
            'protocol'  => 'smtp',
            'mailType'  => $mailType,
            'charset'   => $config['charset'] ?? 'UTF-8',
            'wordWrap'  => true,
            'wrapChars' => 76,
        ];
    }
}
