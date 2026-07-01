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
            <span class="status-badge status-<?= esc($invoice['status']) ?>"><?= esc(lang('App.' . $invoice['status'])) ?></span>
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
            <th class="col-num"><?= esc(lang('App.table_num')) ?></th>
            <th class="col-desc"><?= esc(lang('App.description')) ?></th>
            <th class="col-qty"><?= esc(lang('App.table_qty')) ?></th>
            <th class="col-price"><?= esc(lang('App.table_unit_price')) ?></th>
            <th class="col-total"><?= esc(lang('App.total')) ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $i => $item): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($item['description']) ?></td>
            <td class="col-qty"><?= $item['quantity'] ?></td>
            <td class="col-price"><?= format_currency($item['unit_price'], $invoice['currency'] ?? 'USD') ?></td>
            <td class="col-total"><?= format_currency($item['total'], $invoice['currency'] ?? 'USD') ?></td>
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
    <?= esc(lang('App.generated_by')) ?> <?= esc($appName ?? 'InvoiceApp') ?> &mdash; <?= date('d M Y H:i') ?>
</div>
