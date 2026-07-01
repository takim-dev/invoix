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

        // Build a small base64-encoded logo data URI (resized via GD when possible)
        // to keep dompdf memory usage bounded. Returns null when no logo or file unreadable.
        $logoDataUri = $this->buildPdfLogoDataUri($invoice['company_logo'] ?? '');

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

    /**
     * Produce a base64 data: URI for the company logo, resized to a small
     * PDF-friendly thumbnail via GD. Avoids OOM from embedding multi-MB images.
     * Skips images whose dimensions are too large to decode safely.
     */
    private function buildPdfLogoDataUri(string $logoName, int $maxW = 200, int $maxH = 80): ?string {
        if ($logoName === '') return null;
        $path = FCPATH . 'uploads/logos/' . $logoName;
        if (!is_file($path)) return null;

        // Hard caps — refuse absurdly large files (filesize OR pixel dimensions).
        // A 5000x5000 PNG decodes to ~100MB raster; anything bigger risks OOM.
        if (filesize($path) > 8_000_000) return null;

        $info = @getimagesize($path);
        if (!$info) return null;
        [$w, $h, $type] = $info;
        if ($w <= 0 || $h <= 0) return null;
        if ($w * $h > 25_000_000) return null; // ~5000x5000 safety cap

        // If already small enough AND file size is reasonable, embed as-is
        if ($w <= $maxW && $h <= $maxH && filesize($path) < 60_000) {
            $mime = image_type_to_mime_type($type);
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        // Resize via GD when available
        if (!function_exists('imagecreatetruecolor')) {
            $mime = image_type_to_mime_type($type);
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        $src = null;
        switch ($type) {
            case IMAGETYPE_PNG:
                $src = @imagecreatefrompng($path);
                break;
            case IMAGETYPE_JPEG:
                $src = @imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_WEBP:
                $src = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null;
                break;
        }
        if (!$src) return null;

        $ratio = min($maxW / $w, $maxH / $h, 1.0);
        $newW = max(1, (int) round($w * $ratio));
        $newH = max(1, (int) round($h * $ratio));

        $dst = imagecreatetruecolor($newW, $newH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

        ob_start();
        imagepng($dst, null, 6);
        $bin = ob_get_clean();
        imagedestroy($src);
        imagedestroy($dst);

        if ($bin === false) return null;
        return 'data:image/png;base64,' . base64_encode($bin);
    }
}
