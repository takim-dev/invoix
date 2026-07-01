<?php
namespace App\Controllers;

class PageController extends AppController {

    public function about() {
        $s = model('SettingModel');
        $settingModel = $s; // alias for clarity
        return view('pages/about', [
            'appName'    => $settingModel->getSetting('app_name', 'InvoiceApp'),
            'appLogo'    => $settingModel->getSetting('app_logo', ''),
            'isLoggedIn' => (bool) session()->get('user_id'),
            'userName'   => session()->get('user_name'),
            'title'      => $settingModel->getSetting('page_about_title', 'About Us'),
            'body'       => $settingModel->getSetting('page_about_body', '<p>Tell visitors who you are and what your business does.</p>'),
        ]);
    }

    public function help() {
        $settingModel = model('SettingModel');
        return view('pages/help', [
            'appName'    => $settingModel->getSetting('app_name', 'InvoiceApp'),
            'appLogo'    => $settingModel->getSetting('app_logo', ''),
            'isLoggedIn' => (bool) session()->get('user_id'),
            'userName'   => session()->get('user_name'),
            'title'      => 'Cara Membuat Invoice',
        ]);
    }

    public function contact() {
        $settingModel = model('SettingModel');
        return view('pages/contact', [
            'appName'    => $settingModel->getSetting('app_name', 'InvoiceApp'),
            'appLogo'    => $settingModel->getSetting('app_logo', ''),
            'isLoggedIn' => (bool) session()->get('user_id'),
            'userName'   => session()->get('user_name'),
            'title'      => $settingModel->getSetting('page_contact_title', 'Contact Us'),
            'body'       => $settingModel->getSetting('page_contact_body', '<p>Reach out with any questions — we usually reply within one business day.</p>'),
            'email'      => $settingModel->getSetting('page_contact_email', ''),
            'phone'      => $settingModel->getSetting('page_contact_phone', ''),
            'address'    => $settingModel->getSetting('page_contact_address', ''),
        ]);
    }
}
