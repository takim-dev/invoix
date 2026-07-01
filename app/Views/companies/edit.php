<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?><?= lang('App.edit_company') ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1"><?= lang('App.edit_company') ?></h5>
        <p class="text-muted mb-0" style="font-size:0.85rem;"><?= esc($company['name']) ?></p>
    </div>
    <a href="/companies" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> <?= lang('App.back') ?>
    </a>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="card">
        <div class="card-body p-4">
            <form action="/companies/<?= $company['id'] ?>/update" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.company_name') ?> <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required value="<?= esc($company['name']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.company_code') ?></label>
                    <input type="text" name="code" class="form-control" placeholder="<?= lang('App.company_code_ph') ?>" style="text-transform:uppercase;max-width:200px;" value="<?= esc($company['code'] ?? '') ?>">
                    <small class="text-muted"><?= str_replace('{example}', '<code>INV-JHN-ACME-2026-0001</code>', lang('App.code_helper')) ?> <?= lang('App.code_helper_empty') ?></small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= lang('App.company_email') ?></label>
                        <input type="email" name="email" class="form-control" value="<?= esc($company['email']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= lang('App.company_phone') ?></label>
                        <input type="text" name="phone" class="form-control" value="<?= esc($company['phone']) ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.company_address') ?></label>
                    <textarea name="address" class="form-control" rows="2"><?= esc($company['address']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.company_npwp') ?></label>
                    <input type="text" name="tax_number" class="form-control" value="<?= esc($company['tax_number']) ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label"><?= lang('App.company_logo') ?></label>
                    <?php if ($company['logo']): ?>
                        <div class="mb-2">
                            <img src="/uploads/logos/<?= $company['logo'] ?>" alt="<?= lang('App.current_logo') ?>" style="max-height:60px;border-radius:8px;background:#fff;padding:4px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    <small class="text-muted"><?= lang('App.logo_helper_edit') ?></small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> <?= lang('App.update_company') ?>
                    </button>
                    <a href="/companies" class="btn btn-outline-secondary"><?= lang('App.cancel') ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>
