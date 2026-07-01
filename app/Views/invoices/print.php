<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($invoice['invoice_number']) ?> - Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; color: #1a1a2e; background: #fff; padding: 40px; }
        .invoice-box { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 3px solid #c9a84c; }
        .company-info h2 { font-size: 1.5rem; color: #1a1a2e; margin-bottom: 5px; }
        .company-info p { color: #555; font-size: 0.9rem; margin: 2px 0; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { font-size: 2rem; color: #c9a84c; margin: 0; }
        .invoice-title .inv-number { font-size: 1rem; color: #555; margin-top: 5px; }
        .invoice-title .inv-date { font-size: 0.85rem; color: #777; margin-top: 3px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
        .status-draft { background: #e8ecf4; color: #1b3a5c; }
        .status-sent { background: #eff6ff; color: #2563eb; }
        .status-paid { background: #f0fdf4; color: #16a34a; }
        .status-unpaid { background: #fefce8; color: #ca8a04; }
        .status-cancelled { background: #f3f4f6; color: #6b7280; }
        .parties { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .party-box { width: 48%; }
        .party-box h4 { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #c9a84c; margin-bottom: 8px; font-weight: 600; }
        .party-box p { font-size: 0.9rem; color: #333; margin: 2px 0; }
        .party-box .name { font-weight: 600; font-size: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        thead th { background: #c9a84c; color: #fff; padding: 10px 12px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
        thead th:first-child { border-radius: 6px 0 0 0; }
        thead th:last-child { border-radius: 0 6px 0 0; text-align: right; }
        tbody td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; font-size: 0.9rem; }
        tbody td:last-child { text-align: right; font-weight: 500; }
        tbody tr:hover { background: #fafafa; }
        .totals { display: flex; justify-content: flex-end; margin-bottom: 30px; }
        .totals-box { width: 300px; }
        .totals-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 0.9rem; }
        .totals-row.total { border-top: 2px solid #1a1a2e; padding-top: 10px; margin-top: 5px; font-size: 1.1rem; font-weight: 700; }
        .notes { background: #f9fafb; border-radius: 8px; padding: 15px 20px; margin-bottom: 30px; }
        .notes h5 { font-size: 0.8rem; text-transform: uppercase; color: #c9a84c; margin-bottom: 5px; }
        .notes p { color: #555; font-size: 0.9rem; }
        .footer { text-align: center; color: #aaa; font-size: 0.75rem; padding-top: 20px; border-top: 1px solid #e5e7eb; }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:center;margin-bottom:20px;">
        <button onclick="window.print()" style="background:#c9a84c;color:#fff;border:none;padding:10px 30px;border-radius:6px;font-size:1rem;cursor:pointer;font-weight:600;">🖨️ Print Invoice</button>
        <a href="/invoices/<?= $invoice['id'] ?>" style="margin-left:15px;color:#555;text-decoration:none;">← Back to Invoice</a>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div class="company-info">
                <?php if ($invoice['company_logo']): ?>
                    <img src="/uploads/logos/<?= $invoice['company_logo'] ?>" alt="Logo" style="height:50px;margin-bottom:10px;">
                <?php endif; ?>
                <h2><?= esc($invoice['company_name'] ?? 'Company') ?></h2>
                <?php if ($invoice['company_address']): ?><p><?= nl2br(esc($invoice['company_address'])) ?></p><?php endif; ?>
                <?php if ($invoice['company_phone']): ?><p><?= esc($invoice['company_phone']) ?></p><?php endif; ?>
                <?php if ($invoice['company_email']): ?><p><?= esc($invoice['company_email']) ?></p><?php endif; ?>
                <?php if ($invoice['company_tax_number']): ?><p>NPWP: <?= esc($invoice['company_tax_number']) ?></p><?php endif; ?>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <div class="inv-number"><?= esc($invoice['invoice_number']) ?></div>
                <div class="inv-date">Date: <?= date('d M Y', strtotime($invoice['invoice_date'])) ?></div>
                <?php if ($invoice['due_date']): ?>
                    <div class="inv-date">Due: <?= date('d M Y', strtotime($invoice['due_date'])) ?></div>
                <?php endif; ?>
                <div style="margin-top:8px;">
                    <span class="status-badge status-<?= $invoice['status'] ?>"><?= ucfirst($invoice['status']) ?></span>
                </div>
            </div>
        </div>

        <div class="parties">
            <div class="party-box">
                <h4>Bill To</h4>
                <p class="name"><?= esc($invoice['client_name']) ?></p>
                <?php if ($invoice['client_email']): ?><p><?= esc($invoice['client_email']) ?></p><?php endif; ?>
                <?php if ($invoice['client_address']): ?><p><?= nl2br(esc($invoice['client_address'])) ?></p><?php endif; ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:50%;">Description</th>
                    <th style="width:12%;text-align:center;">Qty</th>
                    <th style="width:15%;text-align:right;">Unit Price</th>
                    <th style="width:18%;text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $item): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($item['description']) ?></td>
                    <td style="text-align:center;"><?= $item['quantity'] ?></td>
                    <td style="text-align:right;"><?= format_currency($item['unit_price'], $invoice['currency'] ?? 'USD') ?></td>
                    <td style="text-align:right;"><?= format_currency($item['total'], $invoice['currency'] ?? 'USD') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-box">
                <div class="totals-row">
                    <span>Subtotal</span>
                    <span><?= format_currency($invoice['subtotal'], $invoice['currency'] ?? 'USD') ?></span>
                </div>
                <div class="totals-row">
                    <span>Tax (<?= $invoice['tax_rate'] ?>%)</span>
                    <span><?= format_currency($invoice['tax_amount'], $invoice['currency'] ?? 'USD') ?></span>
                </div>
                <div class="totals-row total">
                    <span>Total</span>
                    <span><?= format_currency($invoice['total'], $invoice['currency'] ?? 'USD') ?></span>
                </div>
            </div>
        </div>

        <?php if ($invoice['notes']): ?>
        <div class="notes">
            <h5>Notes</h5>
            <p><?= nl2br(esc($invoice['notes'])) ?></p>
        </div>
        <?php endif; ?>

        <div class="footer">
            Generated by InvoiceApp &mdash; <?= date('d M Y H:i') ?>
        </div>
    </div>
</body>
</html>
