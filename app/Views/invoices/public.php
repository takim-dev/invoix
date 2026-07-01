<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (!empty($appLogo)): ?>
    <link rel="icon" type="image/png" href="<?= esc($appLogo) ?>">
    <?php else: ?>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <?php endif; ?>
    <title><?= esc($invoice['invoice_number']) ?> — <?= esc($appName) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= view('invoices/_invoice_styles') ?>
</head>
<body>
    <div class="invoice-box">
        <div class="top-toolbar">
            <a href="<?= site_url('share/' . ($invoice['public_token'] ?? '')) ?>/pdf" class="btn-pdf">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="14" x2="12" y2="18"/><polyline points="10 16 12 18 14 16"/></svg>
                <?= esc(lang('App.pdf_btn')) ?>
            </a>
        </div>

        <?= view('invoices/_invoice_body', ['invoice' => $invoice, 'items' => $items, 'appName' => $appName]) ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
