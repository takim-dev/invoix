<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; }
        body { font-family: sans-serif; color: #0f172a; font-size: 12px; line-height: 1.5; }
        .invoice-box { padding: 30px; }
        .header { width: 100%; border-collapse: collapse; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #1e3a8a; }
        .header > tbody > tr > td { vertical-align: top; }
        .brand-cell { width: 60%; }
        .brand-row { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .brand-row > tbody > tr > td { vertical-align: middle; }
        .brand-row .logo-cell { width: 50px; }
        .brand-row h2 { font-size: 18px; color: #0f172a; font-weight: bold; }
        .company-info p { color: #475569; font-size: 11px; margin: 2px 0; }
        .invoice-title { width: 40%; text-align: right; }
        .invoice-title h1 { font-size: 28px; color: #1e3a8a; }
        .invoice-title .inv-number { font-size: 13px; color: #475569; margin-top: 4px; }
        .invoice-title .inv-date { font-size: 11px; color: #64748b; margin-top: 2px; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; margin-top: 6px; }
        .status-draft { background: #e0e7ff; color: #1e3a8a; }
        .status-sent { background: #eff6ff; color: #2563eb; }
        .status-paid { background: #f0fdf4; color: #16a34a; }
        .status-unpaid { background: #fefce8; color: #ca8a04; }
        .status-cancelled { background: #f3f4f6; color: #6b7280; }
        .parties { width: 100%; border-collapse: collapse; margin-bottom: 22px; }
        .parties h4 { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #1e3a8a; margin-bottom: 4px; font-weight: bold; }
        .parties .name { font-weight: bold; font-size: 13px; }
        .parties p { font-size: 11px; color: #333; margin: 2px 0; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 22px; }
        table.items thead th { background: #1e3a8a; color: #fff; padding: 8px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        table.items tbody td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        table.items .num { text-align: right; }
        table.items .ctr { text-align: center; }
        .totals-wrap { width: 100%; }
        .totals-box { width: 280px; margin-left: auto; border-collapse: collapse; }
        .totals-box td { padding: 3px 0; font-size: 11px; }
        .totals-box td.label { color: #475569; }
        .totals-box td.value { text-align: right; font-weight: 500; }
        .totals-box tr.total td { border-top: 2px solid #0f172a; padding-top: 7px; margin-top: 4px; font-size: 14px; font-weight: bold; }
        .notes { background: #f9fafb; border-radius: 6px; padding: 12px 15px; margin-bottom: 22px; }
        .notes h5 { font-size: 9px; text-transform: uppercase; color: #1e3a8a; margin-bottom: 4px; }
        .notes p { color: #475569; font-size: 11px; }
        .footer { text-align: center; color: #aaa; font-size: 9px; padding-top: 12px; border-top: 1px solid #e5e7eb; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header">
            <tr>
                <td class="brand-cell">
                    <table class="brand-row">
                        <tr>
                            <?php if (!empty($logoDataUri)): ?>
                                <td class="logo-cell"><img src="<?= $logoDataUri ?>" alt="Logo" style="height:40px;"></td>
                            <?php endif; ?>
                            <td><h2><?= esc($invoice['company_name'] ?? 'Company') ?></h2></td>
                        </tr>
                    </table>
                    <div class="company-info">
                        <?php if ($invoice['company_address']): ?><p><?= nl2br(esc($invoice['company_address'])) ?></p><?php endif; ?>
                        <?php if ($invoice['company_phone']): ?><p><?= esc($invoice['company_phone']) ?></p><?php endif; ?>
                        <?php if ($invoice['company_email']): ?><p><?= esc($invoice['company_email']) ?></p><?php endif; ?>
                        <?php if ($invoice['company_tax_number']): ?><p>NPWP: <?= esc($invoice['company_tax_number']) ?></p><?php endif; ?>
                    </div>
                </td>
                <td class="invoice-title">
                    <h1>INVOICE</h1>
                    <div class="inv-number"><?= esc($invoice['invoice_number']) ?></div>
                    <div class="inv-date">Date: <?= date('d M Y', strtotime($invoice['invoice_date'])) ?></div>
                    <?php if ($invoice['due_date']): ?>
                        <div class="inv-date">Due: <?= date('d M Y', strtotime($invoice['due_date'])) ?></div>
                    <?php endif; ?>
                    <span class="status-badge status-<?= $invoice['status'] ?>"><?= ucfirst($invoice['status']) ?></span>
                </td>
            </tr>
        </table>

        <table class="parties">
            <tr>
                <td style="width:50%;">
                    <h4>Bill To</h4>
                    <p class="name"><?= esc($invoice['client_name']) ?></p>
                    <?php if ($invoice['client_email']): ?><p><?= esc($invoice['client_email']) ?></p><?php endif; ?>
                    <?php if ($invoice['client_address']): ?><p><?= nl2br(esc($invoice['client_address'])) ?></p><?php endif; ?>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:50%;">Description</th>
                    <th style="width:12%;">Qty</th>
                    <th style="width:15%;">Unit Price</th>
                    <th style="width:18%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $item): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($item['description']) ?></td>
                    <td class="ctr"><?= $item['quantity'] ?></td>
                    <td class="num"><?= format_currency($item['unit_price'], $invoice['currency'] ?? 'USD') ?></td>
                    <td class="num"><?= format_currency($item['total'], $invoice['currency'] ?? 'USD') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals-wrap">
            <table class="totals-box">
                <tr><td class="label">Subtotal</td><td class="value"><?= format_currency($invoice['subtotal'], $invoice['currency'] ?? 'USD') ?></td></tr>
                <tr><td class="label">Tax (<?= $invoice['tax_rate'] ?>%)</td><td class="value"><?= format_currency($invoice['tax_amount'], $invoice['currency'] ?? 'USD') ?></td></tr>
                <tr class="total"><td>Total</td><td class="value"><?= format_currency($invoice['total'], $invoice['currency'] ?? 'USD') ?></td></tr>
            </table>
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
