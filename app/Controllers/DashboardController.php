<?php
namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\InvoiceModel;
use App\Models\ItemModel;

class DashboardController extends AppController {

    public function __construct() {
        $this->companyModel = new CompanyModel();
        $this->invoiceModel = new InvoiceModel();
        $this->itemModel = new ItemModel();
    }

    public function index() {
        $userId = $this->currentUserId();

        $data = [
            'user' => $this->getUser(),
            'company_count' => $this->companyModel->countByUser($userId),
            'invoice_count' => $this->invoiceModel->countByUser($userId),
            'item_count' => $this->itemModel->where('user_id', $userId)->countAllResults(),
            'recent_invoices' => $this->invoiceModel->getByUser($userId),
        ];
        $data['recent_invoices'] = array_slice($data['recent_invoices'], 0, 5);

        return view('layouts/dashboard', $data);
    }
}
