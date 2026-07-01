<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Email Configuration<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Email Configuration</h5>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Configure SMTP settings for email sending</p>
    </div>
    <a href="/admin" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Admin
    </a>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="card">
        <div class="card-body p-4">
            <form action="/admin/email-config" method="POST">
<?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Protocol</label>
                    <select name="protocol" class="form-select">
                        <option value="smtp" <?= ($emailConfig['protocol'] ?? 'smtp') === 'smtp' ? 'selected' : '' ?>>SMTP</option>
                        <option value="mail" <?= ($emailConfig['protocol'] ?? 'smtp') === 'mail' ? 'selected' : '' ?>>PHP Mail</option>
                        <option value="sendmail" <?= ($emailConfig['protocol'] ?? 'smtp') === 'sendmail' ? 'selected' : '' ?>>Sendmail</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">SMTP Host</label>
                    <input type="text" name="host" class="form-control" value="<?= esc($emailConfig['host'] ?? '') ?>" placeholder="smtp.gmail.com">
                    <small class="text-muted">Example: smtp.gmail.com, smtp-relay.sendinblue.com</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">SMTP Port</label>
                    <input type="number" name="port" class="form-control" value="<?= esc($emailConfig['port'] ?? '587') ?>" placeholder="587">
                    <small class="text-muted">Common ports: 25, 465 (SSL), 587 (TLS)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">SMTP Username</label>
                    <input type="text" name="user" class="form-control" value="<?= esc($emailConfig['user'] ?? '') ?>" placeholder="your-email@gmail.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">SMTP Password</label>
                    <input type="password" name="pass" class="form-control" value="<?= esc($emailConfig['pass'] ?? '') ?>" placeholder="Enter your SMTP password">
                    <small class="text-muted">For Gmail, use an App Password. Leave unchanged to keep current.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Encryption</label>
                    <select name="encryption" class="form-select">
                        <option value="tls" <?= ($emailConfig['encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                        <option value="ssl" <?= ($emailConfig['encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                        <option value="none" <?= ($emailConfig['encryption'] ?? 'tls') === 'none' ? 'selected' : '' ?>>None</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">From Email</label>
                    <input type="email" name="from_email" class="form-control" value="<?= esc($emailConfig['from_email'] ?? '') ?>" placeholder="noreply@yourdomain.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">From Name</label>
                    <input type="text" name="from_name" class="form-control" value="<?= esc($emailConfig['from_name'] ?? '') ?>" placeholder="Invoice App">
                </div>

                <div class="mb-4">
                    <label class="form-label">Mail Type</label>
                    <select name="mail_type" class="form-select">
                        <option value="html" <?= ($emailConfig['mail_type'] ?? 'html') === 'html' ? 'selected' : '' ?>>HTML</option>
                        <option value="text" <?= ($emailConfig['mail_type'] ?? 'html') === 'text' ? 'selected' : '' ?>>Plain Text</option>
                    </select>
                </div>

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> Test your email settings after saving. Make sure your SMTP credentials are correct and your firewall allows outbound connections to the SMTP server.
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Save Configuration
                    </button>
                    <button type="button" class="btn btn-outline-success" id="btnTestEmail" data-bs-toggle="modal" data-bs-target="#testEmailModal">
                        <i class="bi bi-send-check me-1"></i> Test Email SMTP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1" aria-labelledby="testEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testEmailModalLabel">
                    <i class="bi bi-send-check me-1"></i> Test Email SMTP
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted" style="font-size:0.85rem;">
                    Send a test email using the currently saved SMTP configuration to verify connectivity.
                </p>
                <div class="mb-3">
                    <label class="form-label">Recipient Email</label>
                    <input type="email" id="test_email" class="form-control" placeholder="recipient@example.com" required>
                    <small class="text-muted">The test email will be sent to this address.</small>
                </div>
                <div id="testEmailResult" class="alert d-none" role="alert"></div>
                <div id="testEmailDebug" class="d-none mt-2">
                    <details>
                        <summary class="text-muted" style="font-size:0.85rem; cursor:pointer;">Show debug info</summary>
                        <pre class="mt-2 p-2 bg-light border rounded" style="font-size:0.75rem; max-height:250px; overflow:auto;"><code id="testEmailDebugContent"></code></pre>
                    </details>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btnSendTestEmail">
                    <i class="bi bi-send me-1"></i> Send Test Email
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    const modalEl = document.getElementById('testEmailModal');
    const btnSend = document.getElementById('btnSendTestEmail');
    const inputEmail = document.getElementById('test_email');
    const resultBox = document.getElementById('testEmailResult');
    const debugBox = document.getElementById('testEmailDebug');
    const debugContent = document.getElementById('testEmailDebugContent');

    modalEl.addEventListener('shown.bs.modal', function () {
        inputEmail.focus();
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        resultBox.classList.add('d-none');
        resultBox.textContent = '';
        debugBox.classList.add('d-none');
        debugContent.textContent = '';
        inputEmail.value = '';
    });

    btnSend.addEventListener('click', function () {
        const email = inputEmail.value.trim();

        resultBox.classList.add('d-none');
        resultBox.textContent = '';
        debugBox.classList.add('d-none');
        debugContent.textContent = '';

        if (!email) {
            showResult('Please enter a recipient email address.', 'danger');
            return;
        }

        btnSend.disabled = true;
        btnSend.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';

        fetch('<?= site_url('admin/email-config/test') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            },
            body: 'test_email=' + encodeURIComponent(email) + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
        })
        .then(r => r.json())
        .then(data => {
            showResult(data.message || (data.success ? 'Email sent.' : 'Failed to send email.'), data.success ? 'success' : 'danger');
            if (data.debug) {
                debugContent.textContent = data.debug;
                debugBox.classList.remove('d-none');
            }
            // Refresh CSRF tokens on all forms after AJAX — token regenerates on every POST.
            if (data.csrfToken && data.csrfHash) {
                document.querySelectorAll('input[name="' + data.csrfToken + '"]').forEach(function (el) {
                    el.value = data.csrfHash;
                });
            }
        })
        .catch(() => showResult('An unexpected error occurred while sending the test email.', 'danger'))
        .finally(() => {
            btnSend.disabled = false;
            btnSend.innerHTML = '<i class="bi bi-send me-1"></i> Send Test Email';
        });
    });

    function showResult(message, type) {
        resultBox.classList.remove('d-none', 'alert-success', 'alert-danger');
        resultBox.classList.add('alert-' + type);
        resultBox.textContent = message;
    }
})();
</script>
<?= $this->endSection() ?>
