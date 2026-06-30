<?php
namespace App\Models;
use CodeIgniter\Model;

class InvoiceItemModel extends Model {
    protected $table = 'invoice_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['invoice_id','item_id','description','quantity','unit_price','total'];

    public function getByInvoice($invoiceId) {
        return $this->where('invoice_id', $invoiceId)->findAll();
    }

    public function deleteByInvoice($invoiceId) {
        return $this->where('invoice_id', $invoiceId)->delete();
    }
}
