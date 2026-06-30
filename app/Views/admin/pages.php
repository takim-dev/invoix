<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Pages<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<style>
    .pages-page .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .pages-page .topbar h2 { font-size: 1.25rem; font-weight: 600; margin: 0; }
    .pages-tabs {
        display: flex;
        gap: 0.4rem;
        margin-bottom: 1.2rem;
        border-bottom: 1px solid var(--bs-border-color);
        padding-bottom: 0;
    }
    .pages-tabs button {
        background: transparent;
        border: 0;
        padding: 0.7rem 1.1rem;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--bs-secondary-color);
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        cursor: pointer;
        transition: color 0.15s, border-color 0.15s;
    }
    .pages-tabs button:hover { color: var(--bs-primary); }
    .pages-tabs button.active {
        color: var(--bs-primary);
        border-bottom-color: var(--bs-primary);
    }
    .page-pane { display: none; }
    .page-pane.active { display: block; }
    .note-editor.note-frame { border-radius: 8px; }
    .form-label { font-weight: 600; font-size: 0.9rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="pages-page">
    <div class="topbar">
        <h2><i class="bi bi-file-earmark-richtext me-2" style="color:#6c5ce7"></i>Pages</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="pages-tabs" role="tablist">
                <button type="button" class="active" data-target="about-pane" role="tab">
                    <i class="bi bi-info-circle me-1"></i> About Us
                </button>
                <button type="button" data-target="contact-pane" role="tab">
                    <i class="bi bi-envelope me-1"></i> Contact Us
                </button>
            </div>

            <!-- ===== ABOUT ===== -->
            <div class="page-pane active" id="about-pane">
                <form action="/admin/pages" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Page Title</label>
                        <input type="text" name="page_about_title" class="form-control" value="<?= esc($settings['page_about_title'] ?? 'About Us') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="page_about_body" class="form-control wysiwyg" rows="10"><?= ($settings['page_about_body'] ?? '') ?></textarea>
                        <small class="text-muted">Shown on the public <a href="/about" target="_blank">/about</a> page.</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save About</button>
                </form>
            </div>

            <!-- ===== CONTACT ===== -->
            <div class="page-pane" id="contact-pane">
                <form action="/admin/pages" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Page Title</label>
                        <input type="text" name="page_contact_title" class="form-control" value="<?= esc($settings['page_contact_title'] ?? 'Contact Us') ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="page_contact_email" class="form-control" value="<?= esc($settings['page_contact_email'] ?? '') ?>" placeholder="hello@example.com">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="page_contact_phone" class="form-control" value="<?= esc($settings['page_contact_phone'] ?? '') ?>" placeholder="+62 ...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="page_contact_address" class="form-control" value="<?= esc($settings['page_contact_address'] ?? '') ?>" placeholder="Street, City">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Intro Content</label>
                        <textarea name="page_contact_body" class="form-control wysiwyg" rows="6"><?= ($settings['page_contact_body'] ?? '') ?></textarea>
                        <small class="text-muted">Shown on the public <a href="/contact" target="_blank">/contact</a> page next to the contact info card.</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Contact</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Summernote WYSIWYG (lightweight jQuery editor) -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(function () {
    $('.wysiwyg').summernote({
        minHeight: 240,
        maxHeight: 700,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'hr']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        dialogsInBody: true,
    });

    $('.pages-tabs button').on('click', function () {
        var target = $(this).data('target');
        $('.pages-tabs button').removeClass('active');
        $(this).addClass('active');
        $('.page-pane').removeClass('active');
        $('#' + target).addClass('active');
    });
});
</script>
<?= $this->endSection() ?>
