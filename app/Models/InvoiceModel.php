<?php
namespace App\Models;
use CodeIgniter\Model;

class InvoiceModel extends Model {
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['user_id','company_id','invoice_number','client_name','client_email','client_address','invoice_date','due_date','notes','status','is_public','public_token','currency','subtotal','tax_rate','tax_amount','total'];

    public function getByUser($userId) {
        return $this->select('invoices.*, companies.name as company_name')
            ->join('companies', 'companies.id = invoices.company_id', 'left')
            ->where('invoices.user_id', $userId)
            ->orderBy('invoices.created_at', 'DESC')
            ->findAll();
    }

    public function getFilteredByUser($userId, array $filters = []) {
        return $this->datatablesBuilder($userId, $filters, '')
            ->orderBy('invoices.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Base query builder for invoice datatables / exports.
     * Returns the query builder so callers can add count/order/limit.
     */
    public function datatablesBuilder(int $userId, array $filters, string $search) {
        $builder = $this->builder();
        $builder->select('invoices.*, companies.name as company_name')
            ->join('companies', 'companies.id = invoices.company_id', 'left')
            ->where('invoices.user_id', $userId);

        if (!empty($filters['client'])) {
            $builder->like('invoices.client_name', $filters['client']);
        }
        if (!empty($filters['company_id'])) {
            $builder->where('invoices.company_id', (int) $filters['company_id']);
        }
        if (!empty($filters['date_from'])) {
            $builder->where('invoices.invoice_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $builder->where('invoices.invoice_date <=', $filters['date_to']);
        }
        if ($search !== '') {
            $builder->groupStart()
                ->like('invoices.invoice_number', $search)
                ->orLike('invoices.client_name', $search)
                ->orLike('companies.name', $search)
                ->orLike('invoices.status', $search)
                ->groupEnd();
        }
        return $builder;
    }

    public function getClientOptionsByUser($userId) {
        return $this->select('client_name')
            ->where('user_id', $userId)
            ->where('client_name !=', '')
            ->groupBy('client_name')
            ->orderBy('client_name', 'ASC')
            ->findAll();
    }

    public function countByUser($userId) {
        return $this->where('user_id', $userId)->countAllResults();
    }

    public function getWithCompany($id) {
        return $this->withCompanySelect()
            ->where('invoices.id', $id)
            ->first();
    }

    /**
     * Generate the next invoice number for a user+company pair.
     * Format: INV-<user.code>-<company.code>-<YYYY>-<NNNN>
     * Falls back to U{id}/C{id} when code is empty.
     */
    public function generateNumber(array $user, array $company): string {
        $year = date('Y');

        $userCode = strtoupper(trim((string) ($user['code'] ?? '')));
        if ($userCode === '') {
            $userCode = 'U' . str_pad((string) ($user['id'] ?? 0), 3, '0', STR_PAD_LEFT);
        }

        $companyCode = strtoupper(trim((string) ($company['code'] ?? '')));
        if ($companyCode === '') {
            $companyCode = 'C' . str_pad((string) ($company['id'] ?? 0), 3, '0', STR_PAD_LEFT);
        }

        $count = $this->where('user_id', $user['id'])
            ->where('company_id', $company['id'])
            ->where('YEAR(created_at)', $year)
            ->countAllResults();
        $seq = str_pad((string) ($count + 1), 4, '0', STR_PAD_LEFT);

        return 'INV-' . $userCode . '-' . $companyCode . '-' . $year . '-' . $seq;
    }

    public function togglePublic($invoiceId, $userId) {
        $invoice = $this->where('id', $invoiceId)->where('user_id', $userId)->first();
        if (!$invoice) return null;

        if (!empty($invoice['is_public'] ?? false)) {
            $this->update($invoiceId, ['is_public' => 0, 'public_token' => null]);
            return ['is_public' => false, 'url' => null];
        }

        $token = bin2hex(random_bytes(32));
        $this->update($invoiceId, ['is_public' => 1, 'public_token' => $token]);
        return ['is_public' => true, 'url' => site_url('share/' . $token)];
    }

    public function findByPublicToken($token) {
        return $this->withCompanySelect()
            ->where('invoices.public_token', $token)
            ->where('invoices.is_public', 1)
            ->first();
    }

    /**
     * Shared query builder fragment: SELECT + JOIN for company details.
     */
    private function withCompanySelect()
    {
        return $this->select(
            'invoices.*, companies.name as company_name, companies.address as company_address, ' .
            'companies.phone as company_phone, companies.email as company_email, ' .
            'companies.tax_number as company_tax_number, companies.logo as company_logo'
        )->join('companies', 'companies.id = invoices.company_id', 'left');
    }

    public function calculateTotals($invoiceId) {
        $items = $this->db->table('invoice_items')->where('invoice_id', $invoiceId)->get()->getResultArray();
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }
        $invoice = $this->find($invoiceId);
        $taxAmount = $subtotal * ($invoice['tax_rate'] / 100);
        $total = $subtotal + $taxAmount;

        $this->update($invoiceId, [
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total
        ]);
    }
}
