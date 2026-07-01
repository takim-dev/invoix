<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Edit User<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="topbar">
    <h2><i class="bi bi-person-gear me-2" style="color:#6c5ce7"></i>Edit User: <?= esc($target['name']) ?></h2>
    <a href="/admin" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row justify-content-center">
<div class="col-md-8">
    <div class="card">
        <div class="card-body">
            <form action="/admin/user/<?= $target['id'] ?>/update" method="POST">
<?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required value="<?= esc($target['name']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">User Code</label>
                    <input type="text" name="code" class="form-control" style="text-transform:uppercase;max-width:200px;" value="<?= esc($target['code'] ?? '') ?>" placeholder="JHN">
                    <small class="text-muted">Short uppercase code (letters, numbers, dash, underscore). Used in invoice numbers, e.g. <code>INV-JHN-ACME-2026-0001</code>. Must be unique across all users.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="<?= esc($target['email']) ?>" readonly>
                    <small class="text-muted">Email cannot be changed</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <?php $currentStatus = $target['status'] ?? 'active'; ?>
                    <select name="status" class="form-select" required>
                        <option value="active" <?= $currentStatus === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="blocked" <?= $currentStatus === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                    </select>
                    <small class="text-muted">Pending and blocked users cannot login.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                </div>
                <hr>
                <h6 style="color:#6c5ce7;margin-bottom:1rem;"><i class="bi bi-gear me-1"></i> User Limits</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Max Companies</label>
                        <input type="number" name="max_companies" class="form-control" min="1" value="<?= $target['max_companies'] ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Max Invoices</label>
                        <input type="number" name="max_invoices" class="form-control" min="1" value="<?= $target['max_invoices'] ?>">
                    </div>
                </div>
                <small class="text-muted">Current usage: <?= $target['max_companies'] ?> companies limit, <?= $target['max_invoices'] ?> invoices limit</small>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>
