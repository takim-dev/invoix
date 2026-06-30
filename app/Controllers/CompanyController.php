<?php
namespace App\Controllers;

use App\Models\CompanyModel;
use Config\Validation;
use CodeIgniter\Files\File;

class CompanyController extends AppController {

    private const ALLOWED_LOGO_MIMES = ['image/png', 'image/jpeg', 'image/webp'];
    private const ALLOWED_LOGO_EXT   = ['png', 'jpg', 'jpeg', 'webp'];
    private const MAX_LOGO_SIZE      = 2 * 1024 * 1024; // 2MB

    public function __construct() {
        $this->companyModel = new CompanyModel();
    }

    public function index() {
        return view('companies/index', [
            'user' => $this->getUser(),
            'companies' => $this->companyModel->getByUser($this->currentUserId()),
        ]);
    }

    public function create() {
        $blocked = $this->enforceLimit(
            $this->companyModel->countByUser($this->currentUserId()),
            (int) session()->get('max_companies'),
            '/companies',
            'Company limit reached (' . session()->get('max_companies') . '). Contact admin to increase.'
        );
        if ($blocked) return $blocked;
        return view('companies/create', ['user' => $this->getUser()]);
    }

    public function store() {
        $userId = $this->currentUserId();
        $blocked = $this->enforceLimit(
            $this->companyModel->countByUser($userId),
            (int) session()->get('max_companies'),
            '/companies',
            'Company limit reached.'
        );
        if ($blocked) return $blocked;

        if (!$this->validate(Validation::$company)) {
            return $this->failValidation();
        }

        $logo = $this->handleLogoUpload();
        if ($logo === false) {
            return redirect()->back()->withInput()->with('error', 'Logo must be PNG, JPG, or WEBP, max 2MB.');
        }

        $code = $this->resolveCompanyCode(null);
        if ($code instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $code;
        }

        $this->companyModel->insert([
            'user_id'     => $userId,
            'name'        => $this->request->getPost('name'),
            'code'        => $code,
            'address'     => $this->request->getPost('address'),
            'phone'       => $this->request->getPost('phone'),
            'email'       => $this->request->getPost('email'),
            'tax_number'  => $this->request->getPost('tax_number'),
            'logo'        => $logo,
        ]);
        return redirect()->to('/companies')->with('success', 'Company created!');
    }

    public function edit($id) {
        $company = $this->getOwnedEntity($this->companyModel, $id, '/companies');
        if (!$company) return redirect()->to('/companies');
        return view('companies/edit', [
            'user' => $this->getUser(),
            'company' => $company,
        ]);
    }

    public function update($id) {
        $company = $this->getOwnedEntity($this->companyModel, $id, '/companies');
        if (!$company) return redirect()->to('/companies');

        if (!$this->validate(Validation::$company)) {
            return $this->failValidation();
        }

        $logo = $company['logo'];
        $uploaded = $this->handleLogoUpload($company['logo'] ?? null);
        if ($uploaded === false) {
            return redirect()->back()->withInput()->with('error', 'Logo must be PNG, JPG, or WEBP, max 2MB.');
        }
        if ($uploaded !== null) $logo = $uploaded;

        $code = $this->resolveCompanyCode((int) $id);
        if ($code instanceof \CodeIgniter\HTTP\RedirectResponse) {
            return $code;
        }

        $this->companyModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'code'        => $code,
            'address'     => $this->request->getPost('address'),
            'phone'       => $this->request->getPost('phone'),
            'email'       => $this->request->getPost('email'),
            'tax_number'  => $this->request->getPost('tax_number'),
            'logo'        => $logo,
        ]);
        return redirect()->to('/companies')->with('success', 'Company updated!');
    }

    public function delete($id) {
        $company = $this->getOwnedEntity($this->companyModel, $id, '/companies');
        if (!$company) return redirect()->to('/companies');
        $this->deleteLogoFile($company['logo'] ?? null);
        $this->companyModel->delete($id);
        return redirect()->to('/companies')->with('success', 'Company deleted!');
    }

    /**
     * Validate and store the uploaded logo. Returns:
     *   - string  new filename on success
     *   - null    no file uploaded (use existing)
     *   - false   validation failed (caller should reject)
     */
    private function handleLogoUpload(?string $oldLogo = null) {
        $file = $this->request->getFile('logo');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }
        if (!in_array($file->getMimeType(), self::ALLOWED_LOGO_MIMES, true)) return false;
        if (!in_array(strtolower($file->getExtension()), self::ALLOWED_LOGO_EXT, true)) return false;
        if ($file->getSize() > self::MAX_LOGO_SIZE) return false;

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/logos', $newName);
        $this->deleteLogoFile($oldLogo);
        return $newName;
    }

    private function deleteLogoFile(?string $logo): void {
        if ($logo && is_file(FCPATH . 'uploads/logos/' . $logo)) {
            @unlink(FCPATH . 'uploads/logos/' . $logo);
        }
    }

    /**
     * Resolve the final company code:
     *   - if user submitted a code, validate global uniqueness (across companies + users)
     *   - if empty, auto-generate from company name
     * Returns the code string, or a RedirectResponse on validation failure.
     */
    private function resolveCompanyCode(?int $excludeId) {
        $submitted = strtoupper(trim((string) $this->request->getPost('code')));

        if ($submitted === '') {
            $suggestion = $this->companyModel->generateCodeFromName((string) $this->request->getPost('name'));
            return $this->companyModel->generateUniqueCode($suggestion, $excludeId);
        }

        if (!$this->companyModel->isCodeUniqueGlobally($submitted, $excludeId)) {
            $alt = $this->companyModel->generateUniqueCode($submitted, $excludeId);
            return redirect()->back()->withInput()->with(
                'error',
                "Code '{$submitted}' is already used by another company or user. Try '{$alt}'."
            );
        }
        return $submitted;
    }
}
