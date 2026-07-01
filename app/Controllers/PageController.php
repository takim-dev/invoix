<?php
namespace App\Controllers;

class PageController extends AppController {

    public function about() {
        $s = model('SettingModel');
        $settingModel = $s; // alias for clarity
        $body = $settingModel->getSetting('page_about_body', '<p>Tell visitors who you are and what your business does.</p>');
        $body = strip_tags($body, '<p><b><i><u><strong><em><a><ul><ol><li><br><h2><h3><h4><h5><h6><img><blockquote><hr><span><div><table><thead><tbody><tr><td><th>');

        return view('pages/about', [
            'appName'    => $settingModel->getSetting('app_name', 'InvoiceApp'),
            'appLogo'    => $settingModel->getSetting('app_logo', ''),
            'isLoggedIn' => (bool) session()->get('user_id'),
            'userName'   => session()->get('user_name'),
            'title'      => $settingModel->getSetting('page_about_title', 'About Us'),
            'body'       => $body,
        ]);
    }

    public function help() {
        $settingModel = model('SettingModel');
        return view('pages/help', [
            'appName'    => $settingModel->getSetting('app_name', 'InvoiceApp'),
            'appLogo'    => $settingModel->getSetting('app_logo', ''),
            'isLoggedIn' => (bool) session()->get('user_id'),
            'userName'   => session()->get('user_name'),
            'title'      => lang('Help.title'),
        ]);
    }

    public function contact() {
        $settingModel = model('SettingModel');
        $body = $settingModel->getSetting('page_contact_body', '<p>Reach out with any questions — we usually reply within one business day.</p>');
        $body = strip_tags($body, '<p><b><i><u><strong><em><a><ul><ol><li><br><h2><h3><h4><h5><h6><img><blockquote><hr><span><div><table><thead><tbody><tr><td><th>');

        return view('pages/contact', [
            'appName'    => $settingModel->getSetting('app_name', 'InvoiceApp'),
            'appLogo'    => $settingModel->getSetting('app_logo', ''),
            'isLoggedIn' => (bool) session()->get('user_id'),
            'userName'   => session()->get('user_name'),
            'title'      => $settingModel->getSetting('page_contact_title', 'Contact Us'),
            'body'       => $body,
            'email'      => $settingModel->getSetting('page_contact_email', ''),
            'phone'      => $settingModel->getSetting('page_contact_phone', ''),
            'address'    => $settingModel->getSetting('page_contact_address', ''),
        ]);
    }
}
