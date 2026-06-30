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
    }
    .page-body .prose p { margin-bottom: 1.1rem; }
    .contact-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 3rem;
        align-items: start;
    }
    .contact-info-card {
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 14px;
        padding: 1.8rem;
        box-shadow: 0 8px 24px -12px rgba(15, 23, 42, 0.10);
    }
    .contact-info-card h5 {
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--ink);
    }
    .contact-info-list { list-style: none; padding: 0; margin: 0; }
    .contact-info-list li {
        display: flex;
        gap: 0.9rem;
        padding: 0.8rem 0;
        border-bottom: 1px solid var(--bs-border-color);
    }
    .contact-info-list li:last-child { border-bottom: 0; }
    .contact-info-list .ci-icon {
        width: 38px; height: 38px;
        background: var(--brand-soft);
        color: var(--brand);
        border-radius: 9px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.05rem;
        flex: 0 0 auto;
    }
    .contact-info-list .ci-label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--muted);
        font-weight: 700;
    }
    .contact-info-list .ci-value { color: var(--ink); font-weight: 500; word-break: break-word; }
    .contact-info-list .ci-value a { color: var(--ink); text-decoration: none; }
    .contact-info-list .ci-value a:hover { color: var(--brand); }
    .empty-channel {
        font-style: italic;
        color: var(--muted) !important;
    }

    @media (max-width: 991px) {
        .contact-grid { grid-template-columns: 1fr; }
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
        <div class="contact-grid">
            <div class="prose">
                <?= $body ?>
            </div>
            <aside class="contact-info-card">
                <h5>Reach us directly</h5>
                <ul class="contact-info-list">
                    <li>
                        <span class="ci-icon"><i class="bi bi-envelope-fill"></i></span>
                        <div>
                            <span class="ci-label">Email</span>
                            <span class="ci-value">
                                <?php if (!empty($email)): ?>
                                    <a href="mailto:<?= esc($email) ?>"><?= esc($email) ?></a>
                                <?php else: ?>
                                    <span class="empty-channel">Not set</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </li>
                    <li>
                        <span class="ci-icon"><i class="bi bi-telephone-fill"></i></span>
                        <div>
                            <span class="ci-label">Phone</span>
                            <span class="ci-value">
                                <?php if (!empty($phone)): ?>
                                    <a href="tel:<?= esc(preg_replace('/\s+/', '', $phone)) ?>"><?= esc($phone) ?></a>
                                <?php else: ?>
                                    <span class="empty-channel">Not set</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </li>
                    <li>
                        <span class="ci-icon"><i class="bi bi-geo-alt-fill"></i></span>
                        <div>
                            <span class="ci-label">Address</span>
                            <span class="ci-value">
                                <?php if (!empty($address)): ?>
                                    <?= nl2br(esc($address)) ?>
                                <?php else: ?>
                                    <span class="empty-channel">Not set</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </li>
                </ul>
            </aside>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
