<?php
namespace App\Controllers;

class LanguageController extends AppController {

    /**
     * Switch the active locale for the current viewer.
     * Persists in session for anonymous users, and also in the user record
     * for logged-in users so the preference survives across devices.
     */
    public function switch(string $locale) {
        $supported = config('App')->supportedLocales;
        if (!in_array($locale, $supported, true)) {
            return redirect()->back();
        }

        session()->set('locale', $locale);

        $userId = session()->get('user_id');
        if ($userId) {
            model('UserModel')->update($userId, ['language' => $locale]);
        }

        $redirect = $this->request->getGet('r');
        if ($redirect && is_string($redirect)) {
            $path = '/' . ltrim($redirect, '/');
            return redirect()->to($path);
        }

        return redirect()->back();
    }
}
