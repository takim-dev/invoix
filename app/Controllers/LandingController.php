<?php
namespace App\Controllers;

class LandingController extends AppController {

    public function index() {
        $settingModel = model('SettingModel');
        $appName = $settingModel->getSetting('app_name', 'InvoiceApp');
        $appTagline = $settingModel->getSetting('app_tagline', 'Professional invoices, minimal effort.');
        $appLogo = $settingModel->getSetting('app_logo', '');

        $userModel = model('UserModel');
        $companyModel = model('CompanyModel');
        $invoiceModel = model('InvoiceModel');

        return view('landing/index', [
            'appName'       => $appName,
            'appTagline'    => $appTagline,
            'appLogo'       => $appLogo,
            'isLoggedIn'    => (bool) session()->get('user_id'),
            'userName'      => session()->get('user_name'),
            'totalUsers'    => $userModel->countAll(),
            'totalCompanies'=> $companyModel->countAll(),
            'totalInvoices' => $invoiceModel->countAll(),
        ]);
    }
}
