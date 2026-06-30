<?php
namespace App\Controllers;

class LandingController extends AppController {

    public function index() {
        $settingModel = model('SettingModel');
        $appName = $settingModel->getSetting('app_name', 'InvoiceApp');
        $appTagline = $settingModel->getSetting('app_tagline', 'Professional invoices, minimal effort.');
        $appLogo = $settingModel->getSetting('app_logo', '');

        return view('landing/index', [
            'appName'     => $appName,
            'appTagline'  => $appTagline,
            'appLogo'     => $appLogo,
            'isLoggedIn'  => (bool) session()->get('user_id'),
            'userName'    => session()->get('user_name'),
        ]);
    }
}
