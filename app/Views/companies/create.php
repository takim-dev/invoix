<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Add Company<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Add New Company</h5>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Create a new business entity</p>
    </div>
    <a href="/companies" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="card">
        <div class="card-body p-4">
            <form action="/companies/store" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="companyName" class="form-control" required placeholder="PT Maju Jaya" value="<?= esc(old('name')) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Company Code</label>
                    <input type="text" name="code" id="companyCode" class="form-control" placeholder="ACME" style="text-transform:uppercase;max-width:200px;" value="<?= esc(old('code')) ?>">
                    <small class="text-muted">Short uppercase code (letters, numbers, dash, underscore). Used in invoice numbers, e.g. <code>INV-JHN-ACME-2026-0001</code>. Auto-suggested from name; editable. Must be unique across all companies and users.</small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="info@company.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+62 812 xxx">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Jl. Sudirman No. 123, Jakarta"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tax Number (NPWP)</label>
                    <input type="text" name="tax_number" class="form-control" placeholder="00.000.000.0-000.000">
                </div>
                <div class="mb-4">
                    <label class="form-label">Company Logo</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    <small class="text-muted">PNG, JPG, max 2MB</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Save Company
                    </button>
                    <a href="/companies" class="btn btn-outline-secondary">Cancel</a>
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
