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
     * Returns [success, debugOutput].
     */
    public function send(string $to, string $subject, string $body, ?string $toName = null): array
    {
        $emailConfig = json_decode($this->settingModel->getSetting('email_config', '{}'), true);

        if (empty($emailConfig['host'])) {
            log_message('error', 'EmailService: SMTP host not configured.');
            return [false, 'SMTP host is not configured.'];
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
        $debug = $emailService->printDebugger();

        if (!$sent) {
            log_message('error', 'EmailService: Failed to send to ' . $to . "\n" . strip_tags($debug));
        }

        return [(bool) $sent, $debug];
    }

    /**
     * Compatibility wrapper: returns bool only.
     */
    public function sendSimple(string $to, string $subject, string $body, ?string $toName = null): bool
    {
        [$success] = $this->send($to, $subject, $body, $toName);
        return $success;
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

        $encryption = strtolower($config['encryption'] ?? 'tls');
        $newline = $config['newline'] ?? "\r\n";

        return [
            'SMTPHost'    => $config['host'] ?? '',
            'SMTPPort'    => (int) ($config['port'] ?? 587),
            'SMTPUser'    => $config['user'] ?? '',
            'SMTPPass'    => $config['pass'] ?? '',
            'SMTPCrypto'  => $encryption === 'none' ? '' : $encryption,
            'SMTPTimeout' => 30,
            'SMTPAuth'    => true,
            'protocol'    => 'smtp',
            'mailType'    => $mailType,
            'charset'     => $config['charset'] ?? 'UTF-8',
            'wordWrap'    => true,
            'wrapChars'   => 76,
            'newline'     => $newline,
            'CRLF'        => $newline,
            'SMTPOptions' => [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ],
        ];
    }
}
