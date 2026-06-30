<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Edit Company<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Edit Company</h5>
        <p class="text-muted mb-0" style="font-size:0.85rem;"><?= esc($company['name']) ?></p>
    </div>
    <a href="/companies" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="card">
        <div class="card-body p-4">
            <form action="/companies/<?= $company['id'] ?>/update" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required value="<?= esc($company['name']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Company Code</label>
                    <input type="text" name="code" class="form-control" placeholder="ACME" style="text-transform:uppercase;max-width:200px;" value="<?= esc($company['code'] ?? '') ?>">
                    <small class="text-muted">Short uppercase code (letters, numbers, dash, underscore). Used in invoice numbers, e.g. <code>INV-JHN-ACME-2026-0001</code>. Leave empty to auto-use C{id}.</small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= esc($company['email']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= esc($company['phone']) ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2"><?= esc($company['address']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tax Number (NPWP)</label>
                    <input type="text" name="tax_number" class="form-control" value="<?= esc($company['tax_number']) ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label">Company Logo</label>
                    <?php if ($company['logo']): ?>
                        <div class="mb-2">
                            <img src="/uploads/logos/<?= $company['logo'] ?>" alt="Current Logo" style="max-height:60px;border-radius:8px;background:#fff;padding:4px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    <small class="text-muted">Leave empty to keep current logo</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Update Company
                    </button>
                    <a href="/companies" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>
