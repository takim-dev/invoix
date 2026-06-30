<?php
namespace App\Models;
use CodeIgniter\Model;

class CompanyModel extends Model {
    protected $table = 'companies';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['user_id','name','code','address','phone','email','tax_number','logo'];

    public function getByUser($userId) {
        return $this->where('user_id', $userId)->orderBy('name', 'ASC')->findAll();
    }

    public function countByUser($userId) {
        return $this->where('user_id', $userId)->countAllResults();
    }

    /**
     * Verify a company belongs to the given user (used to prevent IDOR
     * on invoice filter by company_id). Returns the company or null.
     */
    public function findOwnedByUser(int $userId, int $companyId): ?array {
        return $this->where('id', $companyId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Suggest a short uppercase code from a company name.
     * Strips common business prefixes (PT, CV, LTD, ...) then takes
     * initials (or first 4 chars for single-word names).
     */
    public function generateCodeFromName(string $name): string {
        $name = trim($name);
        if ($name === '') return '';

        // Drop common business entity prefixes
        $name = preg_replace(
            '/^(PT|CV|UD|TB|PD|KOPERASI|YAYASAN|FOUNDATION|GROUP|CORP|CORPORATION|LTD|INC|LLC|LLP)\s+/i',
            '',
            $name
        );

        // Keep letters and digits only, uppercase
        $name = strtoupper(preg_replace('/[^A-Z0-9 ]/i', '', $name));
        $words = array_values(array_filter(explode(' ', $name), fn($w) => $w !== ''));

        if (empty($words)) return '';
        if (count($words) === 1) {
            return substr($words[0], 0, 4);
        }

        $code = '';
        foreach ($words as $w) {
            $code .= substr($w, 0, 1);
            if (strlen($code) >= 4) break;
        }
        return $code;
    }

    /**
     * True if the code is unused across BOTH companies and users
     * (since both namespaces feed into invoice number prefixes).
     */
    public function isCodeUniqueGlobally(string $code, ?int $excludeCompanyId = null): bool {
        $code = strtoupper(trim($code));
        if ($code === '') return true;

        $companyQ = $this->where('code', $code);
        if ($excludeCompanyId !== null) {
            $companyQ->where('id !=', $excludeCompanyId);
        }
        if ($companyQ->countAllResults() > 0) return false;

        $userCount = $this->db->table('users')->where('code', $code)->countAllResults();
        return $userCount === 0;
    }

    /**
     * Guarantee a globally-unique code by appending a numeric suffix on collision.
     */
    public function generateUniqueCode(string $baseCode, ?int $excludeCompanyId = null): string {
        $base = strtoupper(preg_replace('/[^A-Z0-9]/', '', $baseCode));
        if ($base === '') $base = 'CO';
        $base = substr($base, 0, 17); // leave room for up to 2-digit suffix within VARCHAR(20)

        if ($this->isCodeUniqueGlobally($base, $excludeCompanyId)) {
            return $base;
        }
        for ($i = 2; $i <= 99; $i++) {
            $candidate = $base . $i;
            if ($this->isCodeUniqueGlobally($candidate, $excludeCompanyId)) {
                return $candidate;
            }
        }
        return $base . time();
    }
}
