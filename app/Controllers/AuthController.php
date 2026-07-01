<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SettingModel;
use App\Models\EmailVerificationModel;
use Config\Validation;

class AuthController extends AppController {

    public function __construct() {
        $this->userModel = new UserModel();
        $this->settingModel = new SettingModel();
        $this->emailVerificationModel = new EmailVerificationModel();
    }

    public function login() {
        if ($this->request->getMethod() === 'POST') {
            if (!$this->validate(Validation::$login)) {
                return $this->failValidation();
            }

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = $this->userModel->verifyPassword($email, $password);
            if ($user) {
                $status = $user['status'] ?? 'active';
                if ($status === 'pending') {
                    return redirect()->back()->withInput()->with('error', 'Akun Anda masih pending. Silakan hubungi administrator untuk aktivasi.');
                }
                if ($status === 'blocked') {
                    return redirect()->back()->withInput()->with('error', 'Akun Anda diblokir. Silakan hubungi administrator.');
                }

                session()->set([
                    'user_id' => $user['id'],
                    'user_name' => $user['name'],
                    'user_email' => $user['email'],
                    'role' => $user['role'],
                    'max_companies' => $user['max_companies'],
                    'max_invoices' => $user['max_invoices'],
                    'logged_in' => true,
                ]);
                if ($user['role'] === 'admin') {
                    return redirect()->to('/admin')->with('success', 'Welcome back, Admin!');
                }
                return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['name'] . '!');
            }
            return redirect()->back()->with('error', 'Invalid email or password.');
        }
        return view('auth/login');
    }

    public function register() {
        $enableRegister = $this->settingModel->getSetting('enable_register', '1');
        if ($enableRegister !== '1') {
            return redirect()->to('/login')->with('error', 'Registration is currently disabled.');
        }

        if ($this->request->getMethod() === 'POST') {
            if (!$this->validate(Validation::$register)) {
                return $this->failValidation();
            }

            $verificationMethod = $this->settingModel->getSetting('verification_method', 'none');
            $defaultUserStatus = $this->settingModel->getSetting('default_user_status', 'active');

            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'user',
                'status' => $defaultUserStatus,
                'max_companies' => max(0, (int) $this->settingModel->getSetting('default_max_companies', 3)),
                'max_invoices' => max(0, (int) $this->settingModel->getSetting('default_max_invoices', 50)),
            ];

            $userId = $this->userModel->insert($data);

            if ($userId) {
                if ($verificationMethod === 'email' && $defaultUserStatus === 'pending') {
                    $token = bin2hex(random_bytes(32));
                    $this->emailVerificationModel->createToken($userId, $token);

                    if ($this->sendVerificationEmail($data['email'], $data['name'], $token)) {
                        return redirect()->to('/login')->with('success', 'Registration successful! Please check your email to verify your account.');
                    } else {
                        return redirect()->to('/login')->with('success', 'Registration successful! Please contact administrator to activate your account.');
                    }
                } elseif ($verificationMethod === 'admin' && $defaultUserStatus === 'pending') {
                    return redirect()->to('/login')->with('success', 'Registration successful! Your account requires administrator approval.');
                }

                return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
            }
            return redirect()->back()->with('error', 'Registration failed.');
        }
        return view('auth/register', [
            'enable_register' => $enableRegister === '1',
        ]);
    }

    public function verifyEmail($token) {
        $verification = $this->emailVerificationModel->getByToken($token);

        if (!$verification) {
            return redirect()->to('/login')->with('error', 'Invalid or expired verification token.');
        }

        if ($verification['verified_at'] !== null) {
            return redirect()->to('/login')->with('success', 'Email already verified. Please login.');
        }

        if (strtotime($verification['expires_at']) < time()) {
            return redirect()->to('/login')->with('error', 'Verification token has expired.');
        }

        $this->emailVerificationModel->verifyToken($token);
        $this->userModel->update($verification['user_id'], ['status' => 'active']);

        return redirect()->to('/login')->with('success', 'Email verified successfully! Please login.');
    }

    private function sendVerificationEmail($email, $name, $token): bool
    {
        $emailService = new \App\Services\EmailService();
        $appName = $this->settingModel->getSetting('app_name', 'InvoiceApp');
        $verificationUrl = site_url('/verify-email/' . $token);

        return $emailService->send(
            $email,
            'Verify Your Email - ' . $appName,
            view('email/verify_email', [
                'name' => $name,
                'verification_url' => $verificationUrl,
                'app_name' => $appName,
            ])
        );
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully.');
    }
}
