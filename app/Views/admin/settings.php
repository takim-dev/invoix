<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>App Settings<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Application Settings</h5>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Customize your app name and branding</p>
    </div>
    <a href="/admin" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Admin
    </a>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="card">
        <div class="card-body p-4">
            <form action="/admin/settings" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">App Name</label>
                    <input type="text" name="app_name" class="form-control" value="<?= esc($settings['app_name'] ?? 'InvoiceApp') ?>" placeholder="InvoiceApp">
                    <small class="text-muted">Displayed in sidebar and page titles</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">App Logo</label>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="border rounded d-flex align-items-center justify-content-center" style="width:72px;height:72px;background:var(--bs-tertiary-bg);overflow:hidden;">
                            <?php if (!empty($settings['app_logo'])): ?>
                                <img src="<?= esc($settings['app_logo']) ?>" alt="Current app logo" style="max-width:100%;max-height:100%;object-fit:contain;">
                            <?php else: ?>
                                <i class="bi bi-image text-muted" style="font-size:1.5rem;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <input type="file" name="app_logo" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                            <small class="text-muted">PNG, JPG, WEBP, or SVG. Max 2MB. Shown on login, register, and navbar.</small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">App Tagline</label>
                    <input type="text" name="app_tagline" class="form-control" value="<?= esc($settings['app_tagline'] ?? 'Smart Invoice Management') ?>" placeholder="Smart Invoice Management">
                    <small class="text-muted">Short description shown on login page</small>
                </div>
                <div class="mb-4">
                    <label class="form-label">Footer Text</label>
                    <input type="text" name="footer_text" class="form-control" value="<?= esc($settings['footer_text'] ?? 'Powered by CodeIgniter 4') ?>" placeholder="Powered by CodeIgniter 4">
                    <small class="text-muted">Text displayed in the footer</small>
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
