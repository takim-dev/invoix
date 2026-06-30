<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Account<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<style>
    .account-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(320px, 0.9fr);
        gap: 1.25rem;
        align-items: start;
    }

    .account-hero {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .account-avatar {
        width: 58px;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        color: #fff;
        background: #6571ff;
        border-radius: 12px;
        font-size: 1.35rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .account-hero h5 {
        margin: 0 0 0.25rem;
        font-weight: 700;
    }

    .account-hero p {
        margin: 0;
        color: var(--bs-secondary-color);
        font-size: 0.9rem;
    }

    .quota-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .quota-card {
        padding: 1rem;
        border: 1px solid var(--bs-border-color);
        border-radius: 10px;
        background: var(--bs-body-bg);
    }

    .quota-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
    }

    .quota-card-header span {
        color: var(--bs-secondary-color);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .quota-card-header i {
        color: #6571ff;
        font-size: 1.1rem;
    }

    .quota-value {
        margin-bottom: 0.65rem;
        color: var(--bs-body-color);
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1;
    }

    .quota-value small {
        color: var(--bs-secondary-color);
        font-size: 0.9rem;
        font-weight: 600;
    }

    .quota-progress {
        height: 7px;
        overflow: hidden;
        background: var(--bs-tertiary-bg);
        border-radius: 999px;
    }

    .quota-progress span {
        display: block;
        height: 100%;
        width: var(--progress);
        background: #6571ff;
        border-radius: inherit;
    }

    .account-meta {
        display: grid;
        gap: 0.75rem;
        margin-top: 1.25rem;
    }

    .account-meta-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.8rem 0;
        border-top: 1px solid var(--bs-border-color);
    }

    .account-meta-row span {
        color: var(--bs-secondary-color);
    }

    .account-meta-row strong {
        text-align: right;
    }

    @media (max-width: 991.98px) {
        .account-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .quota-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?php
    $maxCompanies = max(0, (int) ($account['max_companies'] ?? 0));
    $maxInvoices = max(0, (int) ($account['max_invoices'] ?? 0));
    $companyPercent = $maxCompanies > 0 ? min(100, round(($company_count / $maxCompanies) * 100)) : 0;
    $invoicePercent = $maxInvoices > 0 ? min(100, round(($invoice_count / $maxInvoices) * 100)) : 0;
    $initial = strtoupper(substr((string) ($account['name'] ?? 'U'), 0, 1));
?>

<div class="topbar">
    <h2><i class="bi bi-person-gear me-2" style="color:#6571ff"></i>Account</h2>
</div>

<div class="account-grid">
    <div class="card">
        <div class="card-body">
            <div class="account-hero">
                <div class="account-avatar"><?= esc($initial) ?></div>
                <div>
                    <h5><?= esc($account['name']) ?></h5>
                    <p><?= esc($account['email']) ?></p>
                </div>
            </div>

            <div class="quota-grid">
                <div class="quota-card">
                    <div class="quota-card-header">
                        <span>Companies</span>
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="quota-value"><?= (int) $company_count ?> <small>/ <?= $maxCompanies ?></small></div>
                    <div class="quota-progress" style="--progress: <?= $companyPercent ?>%;">
                        <span></span>
                    </div>
                </div>

                <div class="quota-card">
                    <div class="quota-card-header">
                        <span>Invoices</span>
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="quota-value"><?= (int) $invoice_count ?> <small>/ <?= $maxInvoices ?></small></div>
                    <div class="quota-progress" style="--progress: <?= $invoicePercent ?>%;">
                        <span></span>
                    </div>
                </div>
            </div>

            <div class="account-meta">
                <div class="account-meta-row">
                    <span>Role</span>
                    <strong><?= esc(ucfirst($account['role'])) ?></strong>
                </div>
                <div class="account-meta-row">
                    <span>Total invoices created</span>
                    <strong><?= (int) $invoice_count ?></strong>
                </div>
                <div class="account-meta-row">
                    <span>Company creation limit</span>
                    <strong><?= $maxCompanies ?></strong>
                </div>
                <div class="account-meta-row">
                    <span>Invoice creation limit</span>
                    <strong><?= $maxInvoices ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Change Password</div>
        <div class="card-body">
            <form action="<?= site_url('account/password') ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label" for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required autocomplete="current-password">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">New Password</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6" autocomplete="new-password">
                    <small class="text-muted">Minimum 6 characters.</small>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-shield-check me-1"></i> Update Password
                </button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
