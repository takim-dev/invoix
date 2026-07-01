<?php $appName = $appName ?? model('SettingModel')->getSetting('app_name', 'InvoiceApp'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($invoice['invoice_number']) ?> - <?= esc(lang('App.print_btn')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= view('invoices/_invoice_styles') ?>
    <style>
        .no-print { text-align: center; margin-bottom: 20px; }
        .no-print .btn-print {
            background: var(--inv-brand); color: #fff; border: none;
            padding: 10px 30px; border-radius: 6px; font-size: 1rem;
            cursor: pointer; font-weight: 600;
        }
        .no-print a { margin-left: 15px; color: #555; text-decoration: none; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ <?= esc(lang('App.print_invoice')) ?></button>
        <a href="/invoices/<?= $invoice['id'] ?>">← <?= esc(lang('App.back_to_invoice')) ?></a>
    </div>

    <div class="invoice-box">
        <?= view('invoices/_invoice_body', ['invoice' => $invoice, 'items' => $items, 'appName' => $appName]) ?>
    </div>
</body>
</html>
