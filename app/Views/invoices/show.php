<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Invoice <?= esc($invoice['invoice_number']) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="topbar">
    <h2><i class="bi bi-file-earmark-text me-2" style="color:#c9a84c"></i><?= esc($invoice['invoice_number']) ?></h2>
    <div>
        <a href="/invoices/<?= $invoice['id'] ?>/print" target="_blank" class="btn btn-info btn-sm me-1"><i class="bi bi-printer me-1"></i> <?= esc(lang('App.print_btn')) ?></a>
        <a href="/invoices/<?= $invoice['id'] ?>/pdf" class="btn btn-success btn-sm me-1"><i class="bi bi-file-earmark-pdf me-1"></i> <?= esc(lang('App.pdf_btn')) ?></a>
        <a href="/invoices/<?= $invoice['id'] ?>/edit" class="btn btn-warning btn-sm me-1"><i class="bi bi-pencil me-1"></i> <?= esc(lang('App.edit')) ?></a>
        <form action="/invoices/<?= $invoice['id'] ?>/delete" method="POST" class="d-inline" data-confirm="<?= esc(lang('App.delete_invoice_confirm')) ?>">
            <button class="btn btn-danger btn-sm"><i class="bi bi-trash me-1"></i> <?= esc(lang('App.delete_btn')) ?></button>
        </form>
        <a href="/invoices" class="btn btn-outline-secondary btn-sm ms-1"><i class="bi bi-arrow-left me-1"></i> <?= esc(lang('App.back')) ?></a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h4 style="color:#c9a84c;margin-bottom:1.5rem;">
                    <?php if ($invoice['company_logo']): ?>
                        <img src="/uploads/logos/<?= $invoice['company_logo'] ?>" alt="" style="height:40px;vertical-align:middle;margin-right:8px;background:#fff;padding:3px;border-radius:6px;">
                    <?php endif; ?>
                    <?= esc($invoice['company_name'] ?? lang('App.company_col')) ?>
                </h4>
                <?php if ($invoice['company_address']): ?><p class="text-muted" style="margin:0;"><?= nl2br(esc($invoice['company_address'])) ?></p><?php endif; ?>
                <?php if ($invoice['company_phone']): ?><p class="text-muted" style="margin:0;"><?= esc($invoice['company_phone']) ?></p><?php endif; ?>
                <?php if ($invoice['company_email']): ?><p class="text-muted" style="margin:0;"><?= esc($invoice['company_email']) ?></p><?php endif; ?>
                <?php if ($invoice['company_tax_number']): ?><p class="text-muted" style="margin:0;"><?= esc(lang('App.npwp_label')) ?>: <?= esc($invoice['company_tax_number']) ?></p><?php endif; ?>
            </div>
            <div class="col-md-6 text-md-end">
                <h5><?= esc($invoice['invoice_number']) ?></h5>
                <p class="text-muted" style="margin:0;"><?= esc(lang('App.invoice_date')) ?>: <?= date('d M Y', strtotime($invoice['invoice_date'])) ?></p>
                <?php if ($invoice['due_date']): ?><p class="text-muted" style="margin:0;"><?= esc(lang('App.due_date')) ?>: <?= date('d M Y', strtotime($invoice['due_date'])) ?></p><?php endif; ?>
                <p class="mt-2"><span class="badge badge-<?= $invoice['status'] ?>" style="font-size:0.85rem;"><?= ucfirst($invoice['status']) ?></span></p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><?= esc(lang('App.bill_to')) ?></div>
    <div class="card-body">
        <strong><?= esc($invoice['client_name']) ?></strong>
        <?php if ($invoice['client_email']): ?><p class="text-muted" style="margin:0;"><?= esc($invoice['client_email']) ?></p><?php endif; ?>
        <?php if ($invoice['client_address']): ?><p class="text-muted" style="margin:0;"><?= nl2br(esc($invoice['client_address'])) ?></p><?php endif; ?>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th><?= esc(lang('App.table_num')) ?></th><th><?= esc(lang('App.description')) ?></th><th class="text-center"><?= esc(lang('App.table_qty')) ?></th><th class="text-end"><?= esc(lang('App.table_price')) ?></th><th class="text-end"><?= esc(lang('App.total')) ?></th></tr></thead>
                <tbody>
                <?php foreach ($items as $i => $item): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= esc($item['description']) ?></td>
                        <td class="text-center"><?= $item['quantity'] ?></td>
                        <td class="text-end"><?= format_currency($item['unit_price'], $invoice['currency'] ?? 'USD') ?></td>
                        <td class="text-end fw-bold"><?= format_currency($item['total'], $invoice['currency'] ?? 'USD') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row justify-content-end">
<div class="col-md-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2"><span class="text-muted"><?= esc(lang('App.subtotal')) ?></span><span><?= format_currency($invoice['subtotal'], $invoice['currency'] ?? 'USD') ?></span></div>
            <div class="d-flex justify-content-between mb-2"><span class="text-muted"><?= esc(lang('App.tax')) ?> (<?= $invoice['tax_rate'] ?>%)</span><span><?= format_currency($invoice['tax_amount'], $invoice['currency'] ?? 'USD') ?></span></div>
            <hr>
            <div class="d-flex justify-content-between"><strong><?= esc(lang('App.total')) ?></strong><strong style="color:#c9a84c;font-size:1.3rem;"><?= format_currency($invoice['total'], $invoice['currency'] ?? 'USD') ?></strong></div>
        </div>
    </div>
</div>
</div>

<?php if ($invoice['notes']): ?>
<div class="card mt-4">
    <div class="card-header"><?= esc(lang('App.notes')) ?></div>
    <div class="card-body"><p class="text-muted" style="margin:0;"><?= nl2br(esc($invoice['notes'])) ?></p></div>
</div>
<?php endif; ?>

<div class="mt-3">
    <form action="/invoices/<?= $invoice['id'] ?>/status" method="POST" class="d-inline">
        <select name="status" class="form-select form-select-sm d-inline-block" style="width:auto;" onchange="this.form.submit()">
            <option value="draft" <?= $invoice['status']==='draft'?'selected':'' ?>><?= esc(lang('App.draft')) ?></option>
            <option value="sent" <?= $invoice['status']==='sent'?'selected':'' ?>><?= esc(lang('App.sent')) ?></option>
            <option value="paid" <?= $invoice['status']==='paid'?'selected':'' ?>><?= esc(lang('App.paid')) ?></option>
            <option value="unpaid" <?= $invoice['status']==='unpaid'?'selected':'' ?>><?= esc(lang('App.unpaid')) ?></option>
            <option value="cancelled" <?= $invoice['status']==='cancelled'?'selected':'' ?>><?= esc(lang('App.cancelled')) ?></option>
        </select>
    </form>
</div>

<?= $this->endSection() ?>
