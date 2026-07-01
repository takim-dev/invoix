<?= $this->extend('layouts/site') ?>
<?= $this->section('title') ?><?= esc(lang('Help.title')) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .page-hero {
        padding: 3rem 0 2rem;
        background: linear-gradient(135deg, var(--brand-soft) 0%, rgba(201, 168, 76, 0.08) 100%);
        border-bottom: 1px solid var(--bs-border-color);
    }
    .page-hero h1 {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0;
        background: linear-gradient(135deg, var(--brand) 0%, var(--brand-gold) 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .help-section { padding: 3rem 0; }
    .help-section h3 {
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1.4rem;
    }
    .help-section h3 i {
        color: var(--brand);
        margin-right: 0.5rem;
    }
    .step-list {
        list-style: none;
        padding: 0;
        counter-reset: step;
    }
    .step-list li {
        counter-increment: step;
        position: relative;
        padding-left: 3rem;
        margin-bottom: 1.8rem;
    }
    .step-list li::before {
        content: counter(step);
        position: absolute;
        left: 0;
        top: 0;
        width: 32px;
        height: 32px;
        background: var(--brand);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.9rem;
        box-shadow: 0 6px 14px rgba(27, 58, 92, 0.30);
    }
    .step-list li h5 {
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    .step-list li p {
        color: var(--muted);
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .tip-card {
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 12px;
        padding: 1.4rem;
        margin-bottom: 1rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }
    .tip-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--brand-soft);
        color: var(--brand);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .tip-card h6 {
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    .tip-card p {
        color: var(--muted);
        margin: 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<header class="page-hero">
    <div class="container">
        <h1><?= esc(lang('Help.title')) ?></h1>
    </div>
</header>

<section class="help-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <h3><i class="bi bi-list-ol"></i> <?= esc(lang('Help.steps_heading')) ?></h3>
                <ol class="step-list">
                    <li>
                        <h5><?= esc(lang('Help.step1_t')) ?></h5>
                        <p><?= lang('Help.step1_d') ?></p>
                    </li>
                    <li>
                        <h5><?= esc(lang('Help.step2_t')) ?></h5>
                        <p><?= lang('Help.step2_d') ?></p>
                    </li>
                    <li>
                        <h5><?= esc(lang('Help.step3_t')) ?></h5>
                        <p><?= lang('Help.step3_d') ?></p>
                    </li>
                    <li>
                        <h5><?= esc(lang('Help.step4_t')) ?></h5>
                        <p><?= lang('Help.step4_d') ?></p>
                    </li>
                    <li>
                        <h5><?= esc(lang('Help.step5_t')) ?></h5>
                        <p><?= lang('Help.step5_d') ?></p>
                    </li>
                    <li>
                        <h5><?= esc(lang('Help.step6_t')) ?></h5>
                        <p><?= lang('Help.step6_d') ?></p>
                    </li>
                </ol>
            </div>

            <div class="col-lg-4">
                <h3><i class="bi bi-lightbulb"></i> <?= esc(lang('Help.tips_heading')) ?></h3>

                <div class="tip-card">
                    <span class="tip-icon"><i class="bi bi-currency-exchange"></i></span>
                    <div>
                        <h6><?= esc(lang('Help.tip1_t')) ?></h6>
                        <p><?= esc(lang('Help.tip1_d')) ?></p>
                    </div>
                </div>

                <div class="tip-card">
                    <span class="tip-icon"><i class="bi bi-hash"></i></span>
                    <div>
                        <h6><?= esc(lang('Help.tip2_t')) ?></h6>
                        <p><?= lang('Help.tip2_d') ?></p>
                    </div>
                </div>

                <div class="tip-card">
                    <span class="tip-icon"><i class="bi bi-file-earmark-pdf"></i></span>
                    <div>
                        <h6><?= esc(lang('Help.tip3_t')) ?></h6>
                        <p><?= esc(lang('Help.tip3_d')) ?></p>
                    </div>
                </div>

                <div class="tip-card">
                    <span class="tip-icon"><i class="bi bi-arrow-repeat"></i></span>
                    <div>
                        <h6><?= esc(lang('Help.tip4_t')) ?></h6>
                        <p><?= esc(lang('Help.tip4_d')) ?></p>
                    </div>
                </div>

                <div class="tip-card">
                    <span class="tip-icon"><i class="bi bi-database"></i></span>
                    <div>
                        <h6><?= esc(lang('Help.tip5_t')) ?></h6>
                        <p><?= esc(lang('Help.tip5_d')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
