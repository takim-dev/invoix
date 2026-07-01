<?php
namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\InvoiceModel;
use App\Models\UserModel;
use Config\Validation;

class AccountController extends AppController {

    public function __construct() {
        $this->userModel = new UserModel();
        $this->companyModel = new CompanyModel();
        $this->invoiceModel = new InvoiceModel();
    }

    public function index() {
        $userId = $this->currentUserId();
        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Account not found. Please login again.');
        }

        return view('account/index', [
            'user' => $this->getUser(),
            'account' => $user,
            'company_count' => $this->companyModel->countByUser($userId),
            'invoice_count' => $this->invoiceModel->countByUser($userId),
        ]);
    }

    public function updatePassword() {
        $userId = $this->currentUserId();
        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Account not found. Please login again.');
        }

        if (!$this->validate(Validation::$passwordChange)) {
            return $this->failValidation();
        }

        if (!password_verify((string) $this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $this->userModel->update($userId, [
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/account')->with('success', 'Password updated successfully.');
    }

    public function updateLanguage() {
        $userId = $this->currentUserId();
        $locale = trim((string) $this->request->getPost('language'));

        $supported = config('App')->supportedLocales;
        if (!in_array($locale, $supported, true)) {
            return redirect()->back()->with('error', 'Unsupported language.');
        }

        $this->userModel->update($userId, ['language' => $locale]);
        session()->set('locale', $locale);

        return redirect()->to('/account')->with('success', 'Language preference saved.');
    }
}
