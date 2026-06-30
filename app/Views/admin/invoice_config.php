<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Invoice Config<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h5 class="mb-1">Invoice Configuration</h5>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Set default limits for newly registered users</p>
    </div>
    <a href="/admin" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Admin
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Default User Limits</div>
            <div class="card-body p-4">
                <form action="/admin/invoice-config" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Default Companies Limit</label>
                        <input type="number" name="default_max_companies" class="form-control" min="0" step="1" required value="<?= esc($settings['default_max_companies'] ?? '3') ?>">
                        <small class="text-muted">Applied to users created through public registration.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Default Invoices Limit</label>
                        <input type="number" name="default_max_invoices" class="form-control" min="0" step="1" required value="<?= esc($settings['default_max_invoices'] ?? '50') ?>">
                        <small class="text-muted">Existing users keep their current limits unless edited from User Management.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Save Configuration
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background:rgba(101,113,255,0.15);color:#6571ff;">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">How this works</h6>
                        <p class="text-muted mb-0" style="font-size:0.85rem;">These values become the starting quota for new users.</p>
                    </div>
                </div>
                <div class="text-muted" style="font-size:0.88rem;line-height:1.7;">
                    Admin can still override limits for each user from the User Management edit page.
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
