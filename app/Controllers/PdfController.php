<?php
namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends AppController {

    public function __construct() {
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceItemModel = new InvoiceItemModel();
    }

    public function print($id) {
        $invoice = $this->invoiceModel->getWithCompany($id);
        if (!$invoice || $invoice['user_id'] != $this->currentUserId()) {
            return redirect()->to('/invoices')->with('error', 'Not found.');
        }
        $items = $this->invoiceItemModel->getByInvoice($id);

        return view('invoices/print', [
            'invoice' => $invoice,
            'items' => $items,
        ]);
    }

    public function pdf($id) {
        // PDF rendering can be memory-hungry with large logos; raise the per-request limit.
        @ini_set('memory_limit', '1024M');

        $invoice = $this->invoiceModel->getWithCompany($id);
        if (!$invoice || $invoice['user_id'] != $this->currentUserId()) {
            return redirect()->to('/invoices')->with('error', 'Not found.');
        }
        $items = $this->invoiceItemModel->getByInvoice($id);

        $pdfLogoService = new \App\Services\PdfLogoService();
        $logoDataUri = $pdfLogoService->buildDataUri($invoice['company_logo'] ?? '');

        $html = view('invoices/pdf', [
            'invoice'     => $invoice,
            'items'       => $items,
            'logoDataUri' => $logoDataUri,
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);  // logo is base64-inlined; no remote fetch needed
        $options->set('defaultFont', 'sans-serif');
        $options->set('isFontSubsetting', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = $invoice['invoice_number'] . '.pdf';

        return $dompdf->stream($filename, ['Attachment' => true]);
    }

}
