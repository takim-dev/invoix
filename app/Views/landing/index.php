<?= $this->extend('layouts/site') ?>
<?= $this->section('title') ?><?= esc($appName) ?> — <?= esc($appTagline) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --brand: #6571ff;
        --brand-dark: #4f5bd5;
        --brand-soft: rgba(101, 113, 255, 0.10);
        --ink: #0f172a;
        --muted: #64748b;
        --bg-soft: #f8fafc;
    }

    /* ===== HERO ===== */
    .hero {
        padding: 3.5rem 0 3rem;
        position: relative;
        overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute;
        top: -120px; right: -120px;
        width: 460px; height: 460px;
        background: radial-gradient(circle, rgba(101, 113, 255, 0.20), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero::after {
        content: '';
        position: absolute;
        bottom: -160px; left: -120px;
        width: 380px; height: 380px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.18), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero .row { position: relative; z-index: 1; }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.35rem 0.85rem;
        background: var(--brand-soft);
        color: var(--brand);
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1.2rem;
    }
    .hero h1 {
        font-size: clamp(2.3rem, 5vw, 3.6rem);
        font-weight: 800;
        line-height: 1.05;
        letter-spacing: -0.02em;
        margin-bottom: 1.2rem;
    }
    .hero h1 .accent {
        background: linear-gradient(135deg, var(--brand) 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .hero p.lead {
        font-size: 1.15rem;
        color: var(--muted);
        margin-bottom: 1.8rem;
        max-width: 540px;
    }
    .hero-cthas { display: flex; flex-wrap: wrap; gap: 0.7rem; }
    .hero-meta {
        margin-top: 1.5rem;
        font-size: 0.85rem;
        color: var(--muted);
        display: flex; flex-wrap: wrap; gap: 1.2rem;
    }
    .hero-meta i { color: var(--brand); margin-right: 0.3rem; }

    /* ===== INVOICE PREVIEW CARD ===== */
    .invoice-preview {
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: 0 30px 60px -20px rgba(15, 23, 42, 0.18), 0 8px 20px -10px rgba(15, 23, 42, 0.10);
        transform: perspective(1200px) rotateY(-6deg) rotateX(2deg);
        transition: transform 0.4s ease;
    }
    .invoice-preview:hover { transform: perspective(1200px) rotateY(0deg) rotateX(0deg); }
    [data-bs-theme="dark"] .invoice-preview { box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.6); }
    .invoice-preview .ip-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.2rem; }
    .invoice-preview .ip-brand { display: flex; align-items: center; gap: 0.5rem; font-weight: 700; }
    .invoice-preview .ip-logo {
        width: 30px; height: 30px;
        background: linear-gradient(135deg, var(--brand) 0%, #8b5cf6 100%);
        border-radius: 7px;
    }
    .invoice-preview h6.ip-title { color: var(--brand); font-weight: 700; margin: 0; font-size: 1.1rem; }
    .invoice-preview .ip-meta { font-size: 0.78rem; color: var(--muted); text-align: right; }
    .invoice-preview .ip-badges { margin: 0.6rem 0 1rem; display: flex; gap: 0.4rem; flex-wrap: wrap; }
    .invoice-preview .ip-badge { padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 600; background: var(--brand-soft); color: var(--brand); }
    .invoice-preview table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .invoice-preview thead th { background: var(--brand); color: #fff; padding: 0.5rem 0.7rem; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px; font-weight: 600; }
    .invoice-preview thead th:first-child { border-radius: 6px 0 0 6px; }
    .invoice-preview thead th:last-child { border-radius: 0 6px 6px 0; text-align: right; }
    .invoice-preview tbody td { padding: 0.55rem 0.7rem; border-bottom: 1px solid var(--bs-border-color); }
    .invoice-preview tbody td:last-child { text-align: right; font-weight: 500; }
    .invoice-preview .ip-totals { margin-top: 1rem; display: flex; justify-content: flex-end; }
    .invoice-preview .ip-totals table { width: 220px; }
    .invoice-preview .ip-totals td { padding: 0.25rem 0; font-size: 0.85rem; }
    .invoice-preview .ip-totals tr.grand td { border-top: 2px solid var(--ink); padding-top: 0.55rem; font-weight: 800; font-size: 1rem; color: var(--brand); }

    /* ===== SECTION HEADERS ===== */
    .section { padding: 4rem 0; }
    .section-eyebrow {
        color: var(--brand);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.78rem;
        margin-bottom: 0.6rem;
    }
    .section-title {
        font-size: clamp(1.8rem, 3.5vw, 2.4rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 0.6rem;
    }
    .section-subtitle {
        color: var(--muted);
        font-size: 1.05rem;
        max-width: 620px;
        margin: 0 auto;
    }
    .text-center .section-subtitle { margin-bottom: 3rem; }

    /* ===== FEATURE CARDS ===== */
    .feature-card {
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 14px;
        padding: 1.6rem;
        height: 100%;
        transition: all 0.2s ease;
    }
    .feature-card:hover {
        transform: translateY(-4px);
        border-color: var(--brand);
        box-shadow: 0 18px 40px -20px rgba(101, 113, 255, 0.30);
    }
    .feature-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        background: var(--brand-soft);
        color: var(--brand);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }
    .feature-card h5 { font-weight: 700; margin-bottom: 0.4rem; font-size: 1.05rem; }
    .feature-card p { color: var(--muted); margin: 0; font-size: 0.92rem; line-height: 1.55; }

    /* ===== STEPS ===== */
    .step-card { text-align: center; padding: 1.5rem; }
    .step-num {
        width: 48px; height: 48px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background: var(--brand);
        color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        box-shadow: 0 8px 20px -6px rgba(101, 113, 255, 0.45);
    }
    .step-card h6 { font-weight: 700; margin-bottom: 0.3rem; }
    .step-card p { color: var(--muted); font-size: 0.9rem; margin: 0; }

    /* ===== CTA BANNER ===== */
    .cta-banner {
        background: linear-gradient(135deg, var(--brand) 0%, #8b5cf6 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        color: #fff;
        text-align: center;
        box-shadow: 0 30px 60px -20px rgba(101, 113, 255, 0.45);
    }
    .cta-banner h2 { font-weight: 800; margin-bottom: 0.6rem; font-size: clamp(1.6rem, 3vw, 2.2rem); }
    .cta-banner p { opacity: 0.9; margin-bottom: 1.5rem; font-size: 1.05rem; }
    .cta-banner .btn { font-size: 1.05rem; padding: 0.7rem 2rem; border-radius: 10px; font-weight: 600; }
    .btn-white { background: #fff; color: var(--brand); border-color: #fff; }
    .btn-white:hover { background: #f1f5ff; color: var(--brand-dark); border-color: #f1f5ff; }

    @media (max-width: 991px) {
        .hero { padding: 2rem 0 2.5rem; }
        .invoice-preview { margin-top: 2rem; transform: none; }
        .hero::before, .hero::after { display: none; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ===== HERO ===== -->
<header class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="hero-eyebrow">
                    <i class="bi bi-stars"></i> Simple. Fast. Professional.
                </span>
                <h1>
                    Professional invoices,<br>
                    <span class="accent">in seconds.</span>
                </h1>
                <p class="lead">
                    Create, send, and track invoices across 6 currencies. Manage multiple companies, build a reusable item catalog, and export polished PDFs — all in one clean dashboard.
                </p>
                <div class="hero-cthas">
                    <?php if (!empty($isLoggedIn)): ?>
                        <a href="/dashboard" class="btn btn-brand btn-lg">
                            <i class="bi bi-grid-1x2-fill me-1"></i> Go to Dashboard
                        </a>
                    <?php else: ?>
                        <a href="/register" class="btn btn-brand btn-lg">
                            <i class="bi bi-rocket-takeoff me-1"></i> Get Started Free
                        </a>
                        <a href="/login" class="btn btn-outline-ink btn-lg">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    <?php endif; ?>
                </div>
                <div class="hero-meta">
                    <?php if (!empty($isLoggedIn) && !empty($userName)): ?>
                        <span><i class="bi bi-person-check-fill"></i> Logged in as <?= esc($userName) ?></span>
                    <?php else: ?>
                        <span><i class="bi bi-check-circle-fill"></i> No credit card required</span>
                        <span><i class="bi bi-check-circle-fill"></i> Multi-currency ready</span>
                        <span><i class="bi bi-check-circle-fill"></i> PDF export built-in</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="invoice-preview">
                    <div class="ip-head">
                        <div class="ip-brand">
                            <span class="ip-logo"></span>
                            <div>
                                <div>Acme Studio</div>
                                <small class="text-muted">Jakarta, ID</small>
                            </div>
                        </div>
                        <div class="ip-meta">
                            <h6 class="ip-title">INVOICE</h6>
                            <div>INV-JHN-ACME-2026-0042</div>
                            <div>Due: 30 Jul 2026</div>
                        </div>
                    </div>
                    <div class="ip-badges">
                        <span class="ip-badge"><i class="bi bi-cash-coin me-1"></i> IDR</span>
                        <span class="ip-badge" style="background: rgba(34,197,94,0.12); color: #16a34a;"><i class="bi bi-check-circle me-1"></i> Paid</span>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th style="text-align:center;">Qty</th>
                                <th style="text-align:right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Brand Identity Design</td>
                                <td style="text-align:center;">1</td>
                                <td>Rp 15.000.000</td>
                            </tr>
                            <tr>
                                <td>Website UI/UX (5 pages)</td>
                                <td style="text-align:center;">5</td>
                                <td>Rp 25.000.000</td>
                            </tr>
                            <tr>
                                <td>Consultation (hours)</td>
                                <td style="text-align:center;">10</td>
                                <td>Rp 5.000.000</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="ip-totals">
                        <table>
                            <tr><td style="color: var(--muted);">Subtotal</td><td style="text-align:right;">Rp 45.000.000</td></tr>
                            <tr><td style="color: var(--muted);">Tax (11%)</td><td style="text-align:right;">Rp 4.950.000</td></tr>
                            <tr class="grand"><td>Total</td><td>Rp 49.950.000</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ===== FEATURES ===== -->
<section class="section" id="features">
    <div class="container">
        <div class="text-center">
            <div class="section-eyebrow">Everything you need</div>
            <h2 class="section-title">Built for freelancers and small teams</h2>
            <p class="section-subtitle">All the essentials to bill clients professionally — without the bloat of enterprise software.</p>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <span class="feature-icon"><i class="bi bi-lightning-charge"></i></span>
                    <h5>Lightning fast</h5>
                    <p>Create and send an invoice in under a minute. Server-side DataTables keep long lists snappy.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <span class="feature-icon"><i class="bi bi-cash-stack"></i></span>
                    <h5>Multi-currency</h5>
                    <p>USD, IDR, SGD, JPY, CNY, MYR. Item prices can be defined per currency and flow through to invoice totals.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <span class="feature-icon"><i class="bi bi-file-earmark-pdf"></i></span>
                    <h5>One-click PDF</h5>
                    <p>Polished PDF export with your company branding — ready to email or print on the spot.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <span class="feature-icon"><i class="bi bi-building"></i></span>
                    <h5>Multiple companies</h5>
                    <p>Run several businesses under one account. Each gets its own short code embedded in invoice numbers.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <span class="feature-icon"><i class="bi bi-box-seam"></i></span>
                    <h5>Item catalog</h5>
                    <p>Reusable items with categories and per-currency pricing. Pick from catalog when building invoices.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <span class="feature-icon"><i class="bi bi-shield-lock"></i></span>
                    <h5>Secure by design</h5>
                    <p>Per-user data ownership, admin approval flow, optional email verification, and CSRF protection throughout.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== HOW IT WORKS ===== -->
<section class="section" id="how" style="background: var(--bg-soft);">
    <div class="container">
        <div class="text-center">
            <div class="section-eyebrow">How it works</div>
            <h2 class="section-title">From zero to sent in 3 steps</h2>
        </div>
        <div class="row g-4 mt-1">
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <h6>Add your company</h6>
                    <p>Set up company profile, logo, and tax info. Get a short code auto-generated from the name.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-num">2</div>
                    <h6>Build your catalog</h6>
                    <p>Add reusable items with category, unit, and per-currency pricing. Pick them later in seconds.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-num">3</div>
                    <h6>Create &amp; send</h6>
                    <p>Compose an invoice, watch totals update live, then download a polished PDF or print instantly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA BANNER ===== -->
<section class="section">
    <div class="container">
        <div class="cta-banner">
            <?php if (!empty($isLoggedIn)): ?>
                <h2>Welcome back, <?= esc($userName ?? '') ?>!</h2>
                <p>Pick up where you left off.</p>
                <a href="/dashboard" class="btn btn-white btn-lg">
                    <i class="bi bi-grid-1x2-fill me-1"></i> Open Dashboard
                </a>
            <?php else: ?>
                <h2>Ready to send your first invoice?</h2>
                <p>Free account. No credit card. Set up in minutes.</p>
                <a href="/register" class="btn btn-white btn-lg">
                    <i class="bi bi-rocket-takeoff me-1"></i> Get Started Free
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
