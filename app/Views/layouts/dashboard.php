<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="topbar">
    <h2>Dashboard</h2>
    <span style="opacity:0.6">Welcome, <?= esc($user['name']) ?></span>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(108,92,231,0.15);color:#6c5ce7;"><i class="bi bi-building"></i></div>
            <div class="stat-number" style="color:#6c5ce7"><?= $company_count ?></div>
            <div class="stat-label">Companies (max <?= $user['max_companies'] ?>)</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(253,121,168,0.15);color:#fd79a8;"><i class="bi bi-file-earmark-text"></i></div>
            <div class="stat-number" style="color:#fd79a8"><?= $invoice_count ?></div>
            <div class="stat-label">Invoices (max <?= $user['max_invoices'] ?>)</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(0,206,209,0.15);color:#00ced1;"><i class="bi bi-box"></i></div>
            <div class="stat-number" style="color:#00ced1"><?= $item_count ?></div>
            <div class="stat-label">Items</div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 style="color: var(--bs-body-color);">Recent Invoices</h5>
    <a href="/invoices/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i> New Invoice</a>
</div>

<?php if (empty($recent_invoices)): ?>
    <div class="card"><div class="card-body text-center" style="padding:3rem;">
        <i class="bi bi-inbox" style="font-size:3rem;display:block;margin-bottom:1rem;"></i>
        No invoices yet. <a href="/invoices/create">Create your first invoice</a>
    </div></div>
<?php else: ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>#</th><th>Client</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                    <?php foreach ($recent_invoices as $inv): ?>
                        <tr>
                            <td><a href="/invoices/<?= $inv['id'] ?>" class="text-decoration-none" style="color:#6c5ce7"><?= esc($inv['invoice_number']) ?></a></td>
                            <td><?= esc($inv['client_name']) ?></td>
                            <td><?= format_currency($inv['total'], $inv['currency'] ?? 'USD') ?></td>
                            <td><span class="badge badge-<?= $inv['status'] ?>"><?= ucfirst($inv['status']) ?></span></td>
                            <td><?= date('d M Y', strtotime($inv['invoice_date'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
