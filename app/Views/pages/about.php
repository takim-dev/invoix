<?= $this->extend('layouts/site') ?>
<?= $this->section('title') ?><?= esc($title) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .page-hero {
        padding: 3rem 0 2rem;
        background: linear-gradient(135deg, var(--brand-soft) 0%, rgba(139, 92, 246, 0.08) 100%);
        border-bottom: 1px solid var(--bs-border-color);
    }
    .page-hero h1 {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0;
        background: linear-gradient(135deg, var(--brand) 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .page-body { padding: 3rem 0; }
    .page-body .prose {
        font-size: 1.05rem;
        line-height: 1.75;
        color: var(--ink);
        max-width: 760px;
    }
    .page-body .prose p { margin-bottom: 1.1rem; }
    .page-body .prose h2,
    .page-body .prose h3 { font-weight: 700; margin-top: 2rem; margin-bottom: 0.8rem; letter-spacing: -0.01em; }
    .page-body .prose h2 { font-size: 1.5rem; }
    .page-body .prose h3 { font-size: 1.25rem; }
    .page-body .prose a { color: var(--brand); text-decoration: underline; }
    .page-body .prose ul, .page-body .prose ol { padding-left: 1.4rem; margin-bottom: 1.1rem; }
    .page-body .prose li { margin-bottom: 0.4rem; }
    .page-body .prose img { max-width: 100%; height: auto; border-radius: 10px; margin: 1.2rem 0; }
    .page-body .prose blockquote {
        border-left: 4px solid var(--brand);
        padding: 0.6rem 1.2rem;
        background: var(--brand-soft);
        border-radius: 0 8px 8px 0;
        margin: 1.2rem 0;
        color: var(--muted);
        font-style: italic;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--muted);
        border: 1px dashed var(--bs-border-color);
        border-radius: 12px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="page-hero">
    <div class="container">
        <h1><?= esc($title) ?></h1>
    </div>
</section>

<section class="page-body">
    <div class="container">
        <?php if (!empty(trim((string) $body))): ?>
            <div class="prose"><?= $body ?></div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-file-earmark-text" style="font-size: 2rem; opacity: 0.5;"></i>
                <p class="mt-3 mb-0">Content not yet published. Admin can update this from <a href="/admin/pages">Admin → Pages</a>.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>
