<?php
namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\CompanyModel;
use App\Models\ItemModel;
use App\Models\UserModel;
use Config\Validation;

class InvoiceController extends AppController {

    public function __construct() {
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceItemModel = new InvoiceItemModel();
        $this->companyModel = new CompanyModel();
        $this->itemModel = new ItemModel();
        $this->userModel = new UserModel();
    }

    public function index() {
        $userId = $this->currentUserId();
        return view('invoices/index', [
            'user' => $this->getUser(),
            'companies' => $this->companyModel->getByUser($userId),
            'clientOptions' => $this->invoiceModel->getClientOptionsByUser($userId),
            'filters' => $this->invoiceFilters(),
        ]);
    }

    public function datatables() {
        $userId = $this->currentUserId();
        $filters = $this->invoiceFilters();

        // IDOR defense: silently drop company_id filter if not owned
        if (!empty($filters['company_id'])
            && !$this->companyModel->findOwnedByUser($userId, (int) $filters['company_id'])) {
            $filters['company_id'] = null;
        }

        $pagination = $this->datatablePagination([
            0 => 'invoices.invoice_number',
            1 => 'invoices.client_name',
            2 => 'companies.name',
            3 => 'invoices.total',
            4 => 'invoices.status',
            5 => 'invoices.invoice_date',
        ], 'invoices.invoice_date');

        $search = $pagination['search'];

        // Count unfiltered total BEFORE datatablesBuilder() — calling it later would
        // leave the JOIN to companies on the builder, making an unprefixed user_id
        // column ambiguous (companies also has user_id).
        $total = $this->invoiceModel->where('user_id', $userId)->countAllResults();

        $builder = $this->invoiceModel->datatablesBuilder($userId, $filters, $search);
        $recordsFiltered = $builder->countAllResults();
        $invoices = $this->invoiceModel->datatablesBuilder($userId, $filters, $search)
            ->orderBy($pagination['orderColumn'], $pagination['orderDir'])
            ->limit($pagination['length'], $pagination['start'])
            ->get()
            ->getResultArray();

        $data = [];
        foreach ($invoices as $invoice) {
            $id = (int) $invoice['id'];
            $data[] = [
                '<a href="' . site_url('invoices/' . $id) . '" class="invoice-number-link">' . esc($invoice['invoice_number']) . '</a>',
                '<span class="invoice-client">' . esc($invoice['client_name']) . '</span>',
                '<span class="invoice-company" title="' . esc($invoice['company_name']) . '">' . esc($invoice['company_name']) . '</span>',
                '<span class="invoice-total">' . format_currency($invoice['total'], $invoice['currency'] ?? self::DEFAULT_CURRENCY) . '</span>',
                '<span class="badge badge-' . esc($invoice['status']) . '">' . esc(ucfirst($invoice['status'])) . '</span>',
                '<span class="invoice-date">' . date('d M Y', strtotime($invoice['invoice_date'])) . '</span>',
                '<div class="invoice-actions">'
                    . '<a href="' . site_url('invoices/' . $id) . '" class="btn btn-sm btn-info" title="View" aria-label="View invoice"><i class="bi bi-eye"></i></a>'
                    . '<a href="' . site_url('invoices/' . $id . '/edit') . '" class="btn btn-sm btn-warning" title="Edit" aria-label="Edit invoice"><i class="bi bi-pencil"></i></a>'
                    . '<form action="' . site_url('invoices/' . $id . '/delete') . '" method="POST" class="d-inline" data-confirm="Delete this invoice?">'
                    . csrf_field()
                    . '<button class="btn btn-sm btn-danger" title="Delete" aria-label="Delete invoice"><i class="bi bi-trash"></i></button>'
                    . '</form>'
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

    public function export() {
        $userId = $this->currentUserId();
        $invoices = $this->invoiceModel->getFilteredByUser($userId, $this->invoiceFilters());

        $rows = [['Invoice #', 'Client', 'Company', 'Status', 'Invoice Date', 'Due Date', 'Currency', 'Subtotal', 'Tax Amount', 'Total']];
        foreach ($invoices as $invoice) {
            $rows[] = [
                $invoice['invoice_number'],
                $invoice['client_name'],
                $invoice['company_name'],
                ucfirst($invoice['status']),
                $invoice['invoice_date'],
                $invoice['due_date'],
                $invoice['currency'] ?? self::DEFAULT_CURRENCY,
                $invoice['subtotal'],
                $invoice['tax_amount'],
                $invoice['total'],
            ];
        }

        $html = '<table border="1">';
        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . esc((string) $cell) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="invoices-' . date('Ymd-His') . '.xls"')
            ->setBody($html);
    }

    public function create() {
        $userId = $this->currentUserId();
        $blocked = $this->enforceLimit(
            $this->invoiceModel->countByUser($userId),
            (int) session()->get('max_invoices'),
            '/invoices',
            'Invoice limit reached (' . session()->get('max_invoices') . '). Contact admin.'
        );
        if ($blocked) return $blocked;

        return view('invoices/create', [
            'user' => $this->getUser(),
            'companies' => $this->companyModel->getByUser($userId),
            'items' => $this->itemModel->getByUser($userId),
        ]);
    }

    /**
     * AJAX: return the next invoice number for a given company.
     * Called when user picks a company in the create form.
     */
    public function generateNumber() {
        $companyId = (int) $this->request->getGet('company_id');
        if ($companyId <= 0) {
            return $this->response->setJSON(['number' => null]);
        }
        $userId = $this->currentUserId();

        $company = $this->companyModel->findOwnedByUser($userId, $companyId);
        if (!$company) {
            return $this->response->setJSON(['number' => null]);
        }

        $user = $this->userModel->find($userId);
        return $this->response->setJSON([
            'number' => $this->invoiceModel->generateNumber($user, $company),
        ]);
    }

    public function store() {
        $userId = $this->currentUserId();
        $blocked = $this->enforceLimit(
            $this->invoiceModel->countByUser($userId),
            (int) session()->get('max_invoices'),
            '/invoices',
            'Invoice limit reached.'
        );
        if ($blocked) return $blocked;

        if (!$this->validate(Validation::$invoice)) {
            return $this->failValidation();
        }

        // Generate invoice number server-side if the form didn't supply one
        // (e.g., JS disabled or company wasn't selected when AJAX fired).
        $invoiceNumber = trim((string) $this->request->getPost('invoice_number'));
        if ($invoiceNumber === '') {
            $company = $this->companyModel->findOwnedByUser($userId, (int) $this->request->getPost('company_id'));
            $user    = $this->userModel->find($userId);
            if ($company && $user) {
                $invoiceNumber = $this->invoiceModel->generateNumber($user, $company);
            }
        }

        $invoiceId = $this->invoiceModel->insert($this->invoicePayload($userId, $invoiceNumber));
        $this->insertLineItems((int) $invoiceId);
        $this->invoiceModel->calculateTotals($invoiceId);

        return redirect()->to('/invoices/' . $invoiceId)->with('success', 'Invoice created!');
    }

    public function show($id) {
        $invoice = $this->invoiceModel->getWithCompany($id);
        if (!$invoice || $invoice['user_id'] != $this->currentUserId()) {
            return redirect()->to('/invoices')->with('error', 'Not found.');
        }
        return view('invoices/show', [
            'user' => $this->getUser(),
            'invoice' => $invoice,
            'items' => $this->invoiceItemModel->getByInvoice($id),
        ]);
    }

    public function edit($id) {
        $invoice = $this->invoiceModel->getWithCompany($id);
        if (!$invoice || $invoice['user_id'] != $this->currentUserId()) {
            return redirect()->to('/invoices')->with('error', 'Not found.');
        }
        $userId = $this->currentUserId();
        return view('invoices/edit', [
            'user' => $this->getUser(),
            'invoice' => $invoice,
            'items' => $this->invoiceItemModel->getByInvoice($id),
            'companies' => $this->companyModel->getByUser($userId),
            'allItems' => $this->itemModel->getByUser($userId),
        ]);
    }

    public function update($id) {
        $invoice = $this->invoiceModel->find($id);
        if (!$invoice || $invoice['user_id'] != $this->currentUserId()) {
            return redirect()->to('/invoices')->with('error', 'Not found.');
        }

        if (!$this->validate(Validation::$invoice)) {
            return $this->failValidation();
        }

        $this->invoiceModel->update($id, $this->invoicePayload());
        $this->invoiceItemModel->deleteByInvoice($id);
        $this->insertLineItems((int) $id);
        $this->invoiceModel->calculateTotals($id);

        return redirect()->to('/invoices/' . $id)->with('success', 'Invoice updated!');
    }

    public function delete($id) {
        $invoice = $this->invoiceModel->find($id);
        if (!$invoice || $invoice['user_id'] != $this->currentUserId()) {
            return redirect()->to('/invoices')->with('error', 'Not found.');
        }
        $this->invoiceItemModel->deleteByInvoice($id);
        $this->invoiceModel->delete($id);
        return redirect()->to('/invoices')->with('success', 'Invoice deleted!');
    }

    public function updateStatus($id) {
        $invoice = $this->invoiceModel->find($id);
        if (!$invoice || $invoice['user_id'] != $this->currentUserId()) {
            return redirect()->to('/invoices')->with('error', 'Not found.');
        }
        $status = $this->request->getPost('status');
        $this->invoiceModel->update($id, ['status' => $status]);
        return redirect()->to('/invoices/' . $id)->with('success', 'Status updated to ' . ucfirst($status));
    }

    public function togglePublic($id) {
        $result = $this->invoiceModel->togglePublic((int) $id, $this->currentUserId());
        if ($result === null) {
            return $this->response->setJSON(['error' => 'Not found.'], 404);
        }
        return $this->response->setJSON($result);
    }

    public function share($token) {
        $invoice = $this->invoiceModel->findByPublicToken($token);
        if (!$invoice) {
            return view('invoices/public_error', ['title' => lang('App.invoice_not_found')]);
        }
        $items = $this->invoiceItemModel->getByInvoice($invoice['id']);
        $settingModel = model('SettingModel');
        return view('invoices/public', [
            'invoice'  => $invoice,
            'items'    => $items,
            'appName'  => $settingModel->getSetting('app_name', 'InvoiceApp'),
            'appLogo'  => $settingModel->getSetting('app_logo', ''),
        ]);
    }

    private function invoicePayload(?int $userId = null, ?string $invoiceNumber = null): array {
        return [
            'user_id'        => $userId ?? $this->currentUserId(),
            'company_id'     => $this->request->getPost('company_id'),
            'invoice_number' => $invoiceNumber ?? $this->request->getPost('invoice_number'),
            'client_name'    => $this->request->getPost('client_name'),
            'client_email'   => $this->request->getPost('client_email'),
            'client_address' => $this->request->getPost('client_address'),
            'invoice_date'   => $this->request->getPost('invoice_date'),
            'due_date'       => $this->request->getPost('due_date'),
            'notes'          => $this->request->getPost('notes'),
            'status'         => $this->request->getPost('status') ?: 'draft',
            'currency'       => $this->request->getPost('currency') ?: self::DEFAULT_CURRENCY,
            'tax_rate'       => $this->request->getPost('tax_rate') ?: 0,
        ];
    }

    private function insertLineItems(int $invoiceId): void {
        $descriptions = $this->request->getPost('item_description');
        $quantities   = $this->request->getPost('item_quantity');
        $prices       = $this->request->getPost('item_price');
        $itemIds      = $this->request->getPost('item_id');

        if (!$descriptions) return;

        foreach ($descriptions as $i => $desc) {
            if (trim((string) $desc) === '') continue;
            $qty   = (float) ($quantities[$i] ?? 1);
            $price = (float) ($prices[$i] ?? 0);
            $this->invoiceItemModel->insert([
                'invoice_id' => $invoiceId,
                'item_id'    => $itemIds[$i] ?? null,
                'description'=> $desc,
                'quantity'   => $qty,
                'unit_price' => $price,
                'total'      => $qty * $price,
            ]);
        }
    }

    private function invoiceFilters(): array {
        return [
            'client'     => trim((string) $this->request->getGet('client')),
            'company_id' => trim((string) $this->request->getGet('company_id')),
            'date_from'  => trim((string) $this->request->getGet('date_from')),
            'date_to'    => trim((string) $this->request->getGet('date_to')),
        ];
    }
}
