<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?><?= lang('App.add_company') ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1"><?= lang('App.add_new_company') ?></h5>
        <p class="text-muted mb-0" style="font-size:0.85rem;"><?= lang('App.create_business') ?></p>
    </div>
    <a href="/companies" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> <?= lang('App.back') ?>
    </a>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="card">
        <div class="card-body p-4">
            <form action="/companies/store" method="POST" enctype="multipart/form-data">
<?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.company_name') ?> <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="companyName" class="form-control" required placeholder="<?= lang('App.company_name_ph') ?>" value="<?= esc(old('name')) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.company_code') ?></label>
                    <input type="text" name="code" id="companyCode" class="form-control" placeholder="<?= lang('App.company_code_ph') ?>" style="text-transform:uppercase;max-width:200px;" value="<?= esc(old('code')) ?>">
                    <small class="text-muted"><?= str_replace('{example}', '<code>INV-JHN-ACME-2026-0001</code>', lang('App.code_helper')) ?></small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= lang('App.company_email') ?></label>
                        <input type="email" name="email" class="form-control" placeholder="<?= lang('App.company_email_ph') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= lang('App.company_phone') ?></label>
                        <input type="text" name="phone" class="form-control" placeholder="<?= lang('App.company_phone_ph') ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.company_address') ?></label>
                    <textarea name="address" class="form-control" rows="2" placeholder="<?= lang('App.company_address_ph') ?>"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.company_npwp') ?></label>
                    <input type="text" name="tax_number" class="form-control" placeholder="<?= lang('App.company_npwp_ph') ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label"><?= lang('App.company_logo') ?></label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    <small class="text-muted"><?= lang('App.logo_helper') ?></small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> <?= lang('App.save_company') ?>
                    </button>
                    <a href="/companies" class="btn btn-outline-secondary"><?= lang('App.cancel') ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
(function () {
    const nameInput = document.getElementById('companyName');
    const codeInput = document.getElementById('companyCode');
    if (!nameInput || !codeInput) return;

    // If code already has a value (e.g., re-rendered after validation error),
    // treat it as user-typed and don't auto-suggest.
    let userEditedCode = codeInput.value.trim() !== '';

    codeInput.addEventListener('input', () => { userEditedCode = true; });
    codeInput.addEventListener('change', () => { userEditedCode = true; });

    function suggestFromName(name) {
        let n = name.trim();
        if (!n) return '';
        n = n.replace(/^(PT|CV|UD|TB|PD|KOPERASI|YAYASAN|FOUNDATION|GROUP|CORP|CORPORATION|LTD|INC|LLC|LLP)\s+/i, '');
        n = n.toUpperCase().replace(/[^A-Z0-9 ]/g, '');
        const words = n.split(/\s+/).filter(w => w.length > 0);
        if (words.length === 0) return '';
        if (words.length === 1) return words[0].substring(0, 4);
        return words.map(w => w[0]).join('').substring(0, 4);
    }

    nameInput.addEventListener('input', () => {
        if (userEditedCode) return;
        codeInput.value = suggestFromName(nameInput.value);
    });
})();
</script>
<?= $this->endSection() ?>
