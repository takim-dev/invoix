<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CompanyModel;
use App\Models\InvoiceModel;
use App\Models\SettingModel;
use Config\Validation;

class AdminController extends AppController {

    public function __construct() {
        $this->userModel = new UserModel();
        $this->companyModel = new CompanyModel();
        $this->invoiceModel = new InvoiceModel();
    }

    public function index() {
        return view('admin/dashboard', [
            'user' => $this->getUser(),
            'total_users' => $this->userModel->countAllResults(),
            'total_companies' => $this->companyModel->countAllResults(),
            'total_invoices' => $this->invoiceModel->countAllResults(),
        ]);
    }

    public function usersDatatables() {
        $pagination = $this->datatablePagination([
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'role',
            4 => 'status',
            5 => 'max_companies',
            6 => 'max_invoices',
            7 => 'max_companies',
        ], 'id');

        $search = $pagination['search'];
        $total = $this->userModel->countAllResults();
        $recordsFiltered = $this->userModel->datatablesBuilder($search)->countAllResults();
        $users = $this->userModel->datatablesBuilder($search)
            ->orderBy($pagination['orderColumn'], $pagination['orderDir'])
            ->limit($pagination['length'], $pagination['start'])
            ->get()
            ->getResultArray();
        $totalUsers = $this->userModel->countAllResults();

        $data = [];
        foreach ($users as $index => $user) {
            $id = (int) $user['id'];
            $status = in_array($user['status'] ?? self::STATUS_ACTIVE, self::ALLOWED_STATUSES, true)
                ? $user['status'] : self::STATUS_ACTIVE;
            $companyCount = $this->companyModel->countByUser($id);
            $invoiceCount = $this->invoiceModel->countByUser($id);

            $deleteButton = '';
            if ($user['role'] !== self::ROLE_ADMIN || $totalUsers > 1) {
                $deleteButton = '<form action="' . site_url('admin/user/' . $id . '/delete') . '" method="POST" class="d-inline" data-confirm="Delete this user and all their data?">'
                    . csrf_field()
                    . '<button class="btn btn-sm btn-danger" title="Delete user" aria-label="Delete user"><i class="bi bi-trash"></i></button>'
                    . '</form>';
            }

            $data[] = [
                $pagination['start'] + $index + 1,
                '<span class="admin-user-name">' . esc($user['name']) . '</span>',
                '<span class="admin-user-email" title="' . esc($user['email']) . '">' . esc($user['email']) . '</span>',
                '<span class="badge role-badge ' . ($user['role'] === self::ROLE_ADMIN ? 'role-badge-admin' : 'role-badge-user') . '" style="background:' . ($user['role'] === self::ROLE_ADMIN ? 'rgba(101,113,255,0.18)' : 'rgba(150,150,150,0.16)') . ';color:' . ($user['role'] === self::ROLE_ADMIN ? '#a29bfe' : '#999') . ';">' . esc(lang('App.role_' . $user['role'])) . '</span>',
                '<span class="badge status-badge status-badge-' . esc($status) . '">' . esc(lang('App.' . $status)) . '</span>',
                '<span class="admin-user-count">' . $companyCount . '</span>',
                '<span class="admin-user-count">' . $invoiceCount . '</span>',
                '<span class="admin-user-limits">' . (int) $user['max_companies'] . ' co / ' . (int) $user['max_invoices'] . ' inv</span>',
                '<div class="datatable-actions">'
                    . '<a href="' . site_url('admin/user/' . $id . '/edit') . '" class="btn btn-sm btn-warning" title="Edit user" aria-label="Edit user"><i class="bi bi-pencil"></i></a>'
                    . $deleteButton
                    . '</div>',
            ];
        }

        return $this->response->setJSON([
            'draw' => $pagination['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function editUser($id) {
        $target = $this->getOrAbort($this->userModel, $id, '/admin');
        if (!$target) return redirect()->to('/admin');
        return view('admin/edit_user', [
            'user' => $this->getUser(),
            'target' => $target,
        ]);
    }

    public function updateUser($id) {
        $target = $this->getOrAbort($this->userModel, $id, '/admin');
        if (!$target) return redirect()->to('/admin');

        $status = $this->request->getPost('status') ?: self::STATUS_ACTIVE;
        if (!in_array($status, self::ALLOWED_STATUSES, true)) {
            return redirect()->back()->withInput()->with('error', 'Invalid user status.');
        }
        if ($target['role'] === self::ROLE_ADMIN
            && ($target['status'] ?? self::STATUS_ACTIVE) === self::STATUS_ACTIVE
            && $status !== self::STATUS_ACTIVE
            && $this->userModel->getActiveAdminCount() <= 1) {
            return redirect()->back()->withInput()->with('error', 'Cannot deactivate the last active admin.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'code' => strtoupper(trim((string) $this->request->getPost('code'))) ?: null,
            'status' => $status,
            'max_companies' => (int) $this->request->getPost('max_companies'),
            'max_invoices' => (int) $this->request->getPost('max_invoices'),
        ];

        // Cross-namespace uniqueness: user code must not collide with any company code
        if ($data['code'] !== null) {
            $db = \Config\Database::connect();
            $companyCollision = $db->table('companies')->where('code', $data['code'])->countAllResults();
            if ($companyCollision > 0) {
                return redirect()->back()->withInput()->with(
                    'error',
                    "User code '{$data['code']}' is already used by a company. Pick a different code."
                );
            }
        }

        $newPass = $this->request->getPost('password');
        if (!empty($newPass)) {
            $data['password'] = password_hash($newPass, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        return redirect()->to('/admin')->with('success', 'User updated!');
    }

    public function deleteUser($id) {
        $target = $this->getOrAbort($this->userModel, $id, '/admin');
        if (!$target) return redirect()->to('/admin');
        if ($target['role'] === self::ROLE_ADMIN && $this->userModel->getAdminCount() <= 1) {
            return redirect()->to('/admin')->with('error', 'Cannot delete the last admin.');
        }
        $this->userModel->delete($id);
        return redirect()->to('/admin')->with('success', 'User deleted!');
    }

    public function settings() {
        $settingModel = model('SettingModel');
        $settings = $settingModel->getAllSettings();

        if ($this->request->getMethod() === 'POST') {
            foreach (['app_name', 'app_tagline', 'footer_text', 'default_locale'] as $field) {
                $value = $this->request->getPost($field);
                if ($value !== null) {
                    $settingModel->saveSetting($field, trim($value));
                }
            }

            $logoPath = $this->handleAppLogoUpload();
            if ($logoPath === false) {
                return redirect()->back()->withInput()->with('error', 'Logo must be PNG, JPG, or WEBP, max 2MB.');
            }
            if ($logoPath !== null) {
                $settingModel->saveSetting('app_logo', $logoPath);
            }

            return redirect()->to('/admin/settings')->with('success', 'Settings updated!');
        }

        return view('admin/settings', [
            'user' => $this->getUser(),
            'settings' => $settings,
        ]);
    }

    public function invoiceConfig() {
        $settingModel = model('SettingModel');
        if ($this->request->getMethod() === 'POST') {
            if (!$this->validate(Validation::$invoiceConfig)) {
                return $this->failValidation('/admin/invoice-config');
            }
            $settingModel->saveSetting('default_max_companies', (string) (int) $this->request->getPost('default_max_companies'));
            $settingModel->saveSetting('default_max_invoices', (string) (int) $this->request->getPost('default_max_invoices'));
            return redirect()->to('/admin/invoice-config')->with('success', 'Invoice configuration updated!');
        }
        return view('admin/invoice_config', [
            'user' => $this->getUser(),
            'settings' => $settingModel->getAllSettings(),
        ]);
    }

    public function authSettings() {
        $settingModel = model('SettingModel');
        if ($this->request->getMethod() === 'POST') {
            if (!$this->validate(Validation::$authSettings)) {
                return $this->failValidation('/admin/auth-settings');
            }
            $settingModel->saveSetting('enable_register', (string) $this->request->getPost('enable_register'));
            $settingModel->saveSetting('default_user_status', (string) $this->request->getPost('default_user_status'));
            $settingModel->saveSetting('verification_method', (string) $this->request->getPost('verification_method'));
            return redirect()->to('/admin/auth-settings')->with('success', 'Authentication settings updated!');
        }
        return view('admin/auth_settings', [
            'user' => $this->getUser(),
            'settings' => $settingModel->getAllSettings(),
        ]);
    }

    public function pages() {
        $settingModel = model('SettingModel');

        if ($this->request->getMethod() === 'POST') {
            foreach ([
                'page_about_title',
                'page_about_body',
                'page_contact_title',
                'page_contact_body',
                'page_contact_email',
                'page_contact_phone',
                'page_contact_address',
            ] as $field) {
                $value = $this->request->getPost($field);
                if ($value !== null) {
                    $settingModel->saveSetting($field, is_string($value) ? trim($value) : $value);
                }
            }
            return redirect()->to('/admin/pages')->with('success', 'Pages updated!');
        }

        $settings = $settingModel->getAllSettings();
        return view('admin/pages', [
            'user'     => $this->getUser(),
            'settings' => $settings,
        ]);
    }

    public function emailConfig() {
        $settingModel = model('SettingModel');
        if ($this->request->getMethod() === 'POST') {
            $emailConfig = [
                'protocol'   => $this->request->getPost('protocol') ?: 'smtp',
                'host'       => $this->request->getPost('host'),
                'port'       => (int) $this->request->getPost('port'),
                'user'       => $this->request->getPost('user'),
                'pass'       => $this->request->getPost('pass'),
                'from_email' => $this->request->getPost('from_email'),
                'from_name'  => $this->request->getPost('from_name'),
                'mail_type'  => $this->request->getPost('mail_type') ?: 'html',
                'charset'    => $this->request->getPost('charset') ?: 'utf-8',
                'encryption' => $this->request->getPost('encryption') ?: 'tls',
                'newline'    => $this->request->getPost('newline') ?: "\r\n",
            ];
            $settingModel->saveSetting('email_config', json_encode($emailConfig));
            return redirect()->to('/admin/email-config')->with('success', 'Email configuration updated!');
        }

        $settings = $settingModel->getAllSettings();
        return view('admin/email_config', [
            'user' => $this->getUser(),
            'settings' => $settings,
            'emailConfig' => json_decode($settings['email_config'] ?? '{}', true),
        ]);
    }

    public function testEmail() {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method.']);
        }

        $testEmail = trim((string) $this->request->getPost('test_email'));
        if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please enter a valid recipient email address.']);
        }

        $settingModel = model('SettingModel');
        $appName = $settingModel->getSetting('app_name', 'InvoiceApp');

        $emailService = new \App\Services\EmailService();
        $config = $emailService->getConfig();

        if (empty($config['host'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'SMTP host is not configured. Please save your SMTP settings first.']);
        }

        $sent = $emailService->send(
            $testEmail,
            'Test Email from ' . $appName,
            view('email/test_email', [
                'app_name'  => $appName,
                'recipient' => $testEmail,
                'sent_at'   => date('Y-m-d H:i:s'),
            ])
        );

        return $this->response->setJSON([
            'success' => (bool) $sent,
            'message' => $sent
                ? 'Test email sent successfully to ' . $testEmail . '. Please check the inbox (and spam folder).'
                : 'Failed to send test email. Please check your SMTP configuration.',
        ]);
    }

    private function handleAppLogoUpload() {
        $file = $this->request->getFile('app_logo');
        if (!$file || !$file->isValid() || $file->hasMoved()) return null;
        if (!in_array($file->getMimeType(), ['image/png', 'image/jpeg', 'image/webp'], true)) return false;
        if (!in_array(strtolower($file->getExtension()), ['png', 'jpg', 'jpeg', 'webp'], true)) return false;
        if ($file->getSize() > 2 * 1024 * 1024) return false;

        $uploadPath = FCPATH . 'uploads/logos';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);
        return '/uploads/logos/' . $newName;
    }
}
