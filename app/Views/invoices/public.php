<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($invoice['invoice_number']) ?> — <?= esc($appName) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; color: #1a1a2e; background: #f5f6fa; padding: 40px; }
        .invoice-box { max-width: 800px; margin: 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 50px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 3px solid #1b3a5c; }
        .company-info h2 { font-size: 1.5rem; color: #1a1a2e; margin-bottom: 5px; }
        .company-info p { color: #555; font-size: 0.9rem; margin: 2px 0; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { font-size: 2rem; color: #1b3a5c; margin: 0; }
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
        .party-box h4 { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #1b3a5c; margin-bottom: 8px; font-weight: 600; }
        .party-box p { font-size: 0.9rem; color: #333; margin: 2px 0; }
        .party-box .name { font-weight: 600; font-size: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        thead th { background: #1b3a5c; color: #fff; padding: 10px 12px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
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
        .notes h5 { font-size: 0.8rem; text-transform: uppercase; color: #1b3a5c; margin-bottom: 5px; }
        .notes p { color: #555; font-size: 0.9rem; }
        .footer { text-align: center; color: #aaa; font-size: 0.75rem; padding-top: 20px; border-top: 1px solid #e5e7eb; }
        .public-badge { display: inline-block; background: #f0fdf4; color: #16a34a; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-bottom: 24px; }
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-box { box-shadow: none; border-radius: 0; padding: 20px; }
        }
        @media (max-width: 600px) {
            body { padding: 15px; }
            .invoice-box { padding: 25px; }
            .header { flex-direction: column; gap: 20px; }
            .invoice-title { text-align: left; }
            .parties { flex-direction: column; gap: 20px; }
            .party-box { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="public-badge">
            <i class="bi bi-globe2" style="margin-right:4px;"></i> <?= esc(lang('App.public_invoice')) ?>
        </div>

        <div class="header">
            <div class="company-info">
                <?php if ($invoice['company_logo']): ?>
                    <img src="/uploads/logos/<?= esc($invoice['company_logo']) ?>" alt="<?= esc(lang('App.logo_alt')) ?>" style="height:50px;margin-bottom:10px;">
                <?php endif; ?>
                <h2><?= esc($invoice['company_name'] ?? lang('App.company_col')) ?></h2>
                <?php if ($invoice['company_address']): ?><p><?= nl2br(esc($invoice['company_address'])) ?></p><?php endif; ?>
                <?php if ($invoice['company_phone']): ?><p><?= esc($invoice['company_phone']) ?></p><?php endif; ?>
                <?php if ($invoice['company_email']): ?><p><?= esc($invoice['company_email']) ?></p><?php endif; ?>
                <?php if ($invoice['company_tax_number']): ?><p><?= esc(lang('App.npwp_label')) ?>: <?= esc($invoice['company_tax_number']) ?></p><?php endif; ?>
            </div>
            <div class="invoice-title">
                <h1><?= esc(lang('App.invoice_title')) ?></h1>
                <div class="inv-number"><?= esc($invoice['invoice_number']) ?></div>
                <div class="inv-date"><?= esc(lang('App.invoice_date')) ?>: <?= date('d M Y', strtotime($invoice['invoice_date'])) ?></div>
                <?php if ($invoice['due_date']): ?>
                    <div class="inv-date"><?= esc(lang('App.due_date')) ?>: <?= date('d M Y', strtotime($invoice['due_date'])) ?></div>
                <?php endif; ?>
                <div style="margin-top:8px;">
                    <span class="status-badge status-<?= esc($invoice['status']) ?>"><?= esc(ucfirst($invoice['status'])) ?></span>
                </div>
            </div>
        </div>

        <div class="parties">
            <div class="party-box">
                <h4><?= esc(lang('App.bill_to')) ?></h4>
                <p class="name"><?= esc($invoice['client_name']) ?></p>
                <?php if ($invoice['client_email']): ?><p><?= esc($invoice['client_email']) ?></p><?php endif; ?>
                <?php if ($invoice['client_address']): ?><p><?= nl2br(esc($invoice['client_address'])) ?></p><?php endif; ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:5%;"><?= esc(lang('App.table_num')) ?></th>
                    <th style="width:50%;"><?= esc(lang('App.description')) ?></th>
                    <th style="width:12%;text-align:center;"><?= esc(lang('App.table_qty')) ?></th>
                    <th style="width:15%;text-align:right;"><?= esc(lang('App.table_unit_price')) ?></th>
                    <th style="width:18%;text-align:right;"><?= esc(lang('App.total')) ?></th>
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
                    <span><?= esc(lang('App.subtotal')) ?></span>
                    <span><?= format_currency($invoice['subtotal'], $invoice['currency'] ?? 'USD') ?></span>
                </div>
                <div class="totals-row">
                    <span><?= esc(lang('App.tax')) ?> (<?= $invoice['tax_rate'] ?>%)</span>
                    <span><?= format_currency($invoice['tax_amount'], $invoice['currency'] ?? 'USD') ?></span>
                </div>
                <div class="totals-row total">
                    <span><?= esc(lang('App.total')) ?></span>
                    <span><?= format_currency($invoice['total'], $invoice['currency'] ?? 'USD') ?></span>
                </div>
            </div>
        </div>

        <?php if ($invoice['notes']): ?>
        <div class="notes">
            <h5><?= esc(lang('App.notes')) ?></h5>
            <p><?= nl2br(esc($invoice['notes'])) ?></p>
        </div>
        <?php endif; ?>

        <div class="footer">
            <?= esc($appName) ?> — <?= esc(lang('App.generated_by')) ?><?= date('d M Y H:i') ?>
        </div>
    </div>
</body>
</html>
