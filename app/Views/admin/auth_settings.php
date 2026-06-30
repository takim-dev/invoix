<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Authentication Settings<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Authentication Settings</h5>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Manage registration and user verification</p>
    </div>
    <a href="/admin" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Admin
    </a>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="card">
        <div class="card-body p-4">
            <form action="/admin/auth-settings" method="POST">
                <div class="mb-3">
                    <label class="form-label">Enable Registration</label>
                    <div class="form-check form-switch">
                        <input type="hidden" name="enable_register" value="0">
                        <input type="checkbox" class="form-check-input" name="enable_register" value="1" <?= (($settings['enable_register'] ?? '1') === '1') ? 'checked' : '' ?>>
                        <label class="form-check-label">Allow new users to register</label>
                    </div>
                    <small class="text-muted">When disabled, only administrators can create new user accounts.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Default User Status</label>
                    <select name="default_user_status" class="form-select">
                        <option value="active" <?= ($settings['default_user_status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="pending" <?= ($settings['default_user_status'] ?? 'active') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    </select>
                    <small class="text-muted">Status assigned to newly registered users.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label">Verification Method</label>
                    <select name="verification_method" class="form-select">
                        <option value="none" <?= ($settings['verification_method'] ?? 'none') === 'none' ? 'selected' : '' ?>>None</option>
                        <option value="email" <?= ($settings['verification_method'] ?? 'none') === 'email' ? 'selected' : '' ?>>Email Verification</option>
                        <option value="admin" <?= ($settings['verification_method'] ?? 'none') === 'admin' ? 'selected' : '' ?>>Administrator Approval</option>
                    </select>
                    <small class="text-muted">
                        <strong>None:</strong> No verification required. Users are activated immediately.<br>
                        <strong>Email Verification:</strong> Users must verify their email address via a link sent to their email.<br>
                        <strong>Administrator Approval:</strong> Users must wait for an administrator to approve their account.
                    </small>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Tip:</strong> Combine "Email Verification" or "Administrator Approval" with "Default User Status: Pending" for best security.
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Settings
                </button>
            </form>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>
