<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LocaleFilter implements FilterInterface {

    /**
     * Locale labels shown in the UI (dropdown, account page).
     * Keep keys in sync with Config\App::$supportedLocales.
     */
    public const LOCALE_LABELS = [
        'en' => 'English',
        'id' => 'Bahasa Indonesia',
        'ms' => 'Bahasa Malaysia',
        'zh' => '中文',
        'vi' => 'Tiếng Việt',
        'ar' => 'العربية',
        'es' => 'Español',
        'fr' => 'Français',
        'hi' => 'हिन्दी',
    ];

    public function before(RequestInterface $request, $arguments = null) {
        $config = config('App');
        $supported = $config->supportedLocales;
        $default   = $config->defaultLocale;

        $locale = null;

        // 1) Logged-in user's stored preference wins
        $userId = session()->get('user_id');
        if ($userId) {
            $db = \Config\Database::connect();
            $row = $db->table('users')->select('language')->where('id', $userId)->get()->getRowArray();
            if (!empty($row['language']) && in_array($row['language'], $supported, true)) {
                $locale = $row['language'];
            }
        }

        // 2) Session-stored choice (covers anonymous visitors who picked a language)
        if (!$locale) {
            $sess = session()->get('locale');
            if ($sess && in_array($sess, $supported, true)) {
                $locale = $sess;
            }
        }

        // 3) Admin-configured default
        if (!$locale) {
            try {
                $settingModel = model('SettingModel');
                $admin = $settingModel->getSetting('default_locale', '');
                if ($admin && in_array($admin, $supported, true)) {
                    $locale = $admin;
                }
            } catch (\Throwable $e) {
                // settings table may not be ready during install — fall through
            }
        }

        // 4) Final fallback
        if (!$locale) {
            $locale = $default;
        }

        $request->setLocale($locale);
        // Sync the Language service so lang() picks up the new locale.
        service('language')->setLocale($locale);
        // Also persist on session so view helpers can read it cheaply.
        session()->set('locale', $locale);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
    }
}
