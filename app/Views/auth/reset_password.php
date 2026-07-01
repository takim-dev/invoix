<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        $settingModel = new \App\Models\SettingModel();
        $appName = $settingModel->getSetting('app_name', 'InvoiceApp');
        $appLogo = $settingModel->getSetting('app_logo', '');
        $appTagline = $settingModel->getSetting('app_tagline', 'Smart Invoice Management');
    ?>
    <title>Reset Password - <?= esc($appName) ?></title>
    <link rel="icon" type="image/png" href="<?= esc($appLogo) ?>">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <style>
        :root {
            --bg: #f4f7fb;
            --panel: #ffffff;
            --ink: #0a1628;
            --muted: #5a6f85;
            --line: #e5e7eb;
            --primary: #1b3a5c;
            --primary-dark: #0f2539;
            --primary-soft: rgba(27, 58, 92, 0.08);
            --primary-gold: #c9a84c;
            --success-bg: #edfdf4;
            --success: #0f8a4b;
            --danger-bg: #fff1f1;
            --danger: #b42318;
            --shadow: 0 18px 48px rgba(10, 22, 40, 0.14);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 16% 18%, rgba(27, 58, 92, 0.12), transparent 28%),
                linear-gradient(135deg, #f0f4f8 0%, #e8ecf2 100%);
        }

        a { color: var(--primary); font-weight: 700; text-decoration: none; }
        a:hover { color: var(--primary-dark); }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px 18px;
        }

        .auth-card {
            width: min(920px, 100%);
            overflow: hidden;
            display: grid;
            grid-template-columns: 0.92fr 1.08fr;
            background: var(--panel);
            border: 1px solid rgba(229, 231, 235, 0.88);
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .auth-aside {
            min-height: 520px;
            padding: 34px;
            color: #fff;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background:
                linear-gradient(145deg, rgba(10, 22, 40, 0.96), rgba(27, 58, 92, 0.92)),
                linear-gradient(135deg, #0f2539, #1b3a5c);
        }

        .auth-aside::before,
        .auth-aside::after {
            content: "";
            position: absolute;
            inset: auto;
            pointer-events: none;
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .auth-aside::before {
            width: 220px;
            height: 220px;
            right: -64px;
            top: -54px;
            border-radius: 50%;
        }

        .auth-aside::after {
            left: 34px;
            right: 34px;
            bottom: 126px;
            height: 1px;
            border-width: 1px 0 0;
        }

        .brand-row {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0;
        }

        .brand-mark {
            width: 58px;
            height: 58px;
            display: grid;
            place-items: center;
            color: var(--primary);
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
        }

        .brand-mark svg { width: 32px; height: 32px; }

        .brand-mark img {
            width: 46px;
            height: 46px;
            object-fit: contain;
        }

        .aside-copy {
            position: relative;
            z-index: 1;
            max-width: 320px;
        }

        .aside-copy h1 {
            margin: 0 0 14px;
            font-size: 32px;
            line-height: 1.15;
            letter-spacing: 0;
        }

        .aside-copy p {
            margin: 0;
            color: rgba(255, 255, 255, 0.76);
            line-height: 1.65;
            font-size: 15px;
        }

        .aside-stats {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat {
            padding: 14px 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            backdrop-filter: blur(12px);
        }

        .stat strong {
            display: block;
            font-size: 18px;
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat span {
            color: rgba(255, 255, 255, 0.68);
            font-size: 12px;
        }

        .auth-form {
            padding: 56px 58px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header { margin-bottom: 28px; }

        .eyebrow {
            margin: 0 0 8px;
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .form-header h2 {
            margin: 0 0 8px;
            font-size: 28px;
            line-height: 1.2;
            letter-spacing: 0;
        }

        .form-header p {
            margin: 0;
            color: var(--muted);
            line-height: 1.55;
        }

        .alert {
            margin: 0 0 18px;
            padding: 13px 14px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.45;
            border: 1px solid transparent;
        }

        .alert-success {
            color: var(--success);
            background: var(--success-bg);
            border-color: #b7f1cd;
        }

        .alert-danger {
            color: var(--danger);
            background: var(--danger-bg);
            border-color: #ffd0d0;
        }

        .field { margin-bottom: 18px; }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-size: 13px;
            font-weight: 700;
        }

        input {
            width: 100%;
            height: 46px;
            padding: 0 14px;
            color: var(--ink);
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            font: inherit;
            outline: none;
            transition: border-color 160ms ease, box-shadow 160ms ease, background 160ms ease;
        }

        input::placeholder { color: #9ca3af; }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(27, 58, 92, 0.12);
            background: #fff;
        }

        .btn-primary {
            width: 100%;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #fff;
            background: var(--primary);
            border: 0;
            border-radius: 8px;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 14px 24px rgba(27, 58, 92, 0.25);
            transition: transform 160ms ease, background 160ms ease, box-shadow 160ms ease;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(27, 58, 92, 0.30);
        }

        .btn-primary svg { width: 18px; height: 18px; }

        .switch-link {
            margin: 22px 0 0;
            color: var(--muted);
            text-align: center;
            font-size: 14px;
        }

        @media (max-width: 760px) {
            .auth-shell { padding: 18px; align-items: start; }
            .auth-card { grid-template-columns: 1fr; }
            .auth-aside { min-height: auto; gap: 32px; padding: 28px; }
            .aside-copy h1 { font-size: 26px; }
            .aside-stats { grid-template-columns: 1fr; }
            .auth-form { padding: 32px 24px; }
        }
    </style>
</head>
<body>
    <main class="auth-shell">
        <section class="auth-card" aria-label="Reset Password">
            <aside class="auth-aside">
                <div class="brand-row">
                    <div class="brand-mark" aria-hidden="true">
                        <?php if (!empty($appLogo)): ?>
                            <img src="<?= esc($appLogo) ?>" alt="">
                        <?php else: ?>
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M7 3h8l4 4v14H7V3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M15 3v5h4" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M10 12h6M10 16h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <span><?= esc($appName) ?></span>
                </div>

                <div class="aside-copy">
                    <h1>Create a new password</h1>
                    <p><?= esc($appTagline) ?></p>
                </div>

                <div class="aside-stats" aria-label="Highlights">
                    <div class="stat">
                        <strong>Strong</strong>
                        <span>Password</span>
                    </div>
                    <div class="stat">
                        <strong>Secure</strong>
                        <span>Hashing</span>
                    </div>
                    <div class="stat">
                        <strong>1 Hour</strong>
                        <span>Expiry</span>
                    </div>
                </div>
            </aside>

            <div class="auth-form">
                <header class="form-header">
                    <p class="eyebrow">Set new password</p>
                    <h2>New Password</h2>
                    <p>Enter and confirm your new password below.</p>
                </header>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= site_url('reset-password/' . $token) ?>">
                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="password">New Password</label>
                        <input id="password" type="password" name="password" required minlength="6" placeholder="Min. 6 characters" autocomplete="new-password">
                    </div>

                    <div class="field">
                        <label for="confirm_password">Confirm New Password</label>
                        <input id="confirm_password" type="password" name="confirm_password" required minlength="6" placeholder="Re-enter your new password" autocomplete="new-password">
                    </div>

                    <button class="btn-primary" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 12h12M13 8l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Reset Password
                    </button>
                </form>

                <p class="switch-link">Remember your password? <a href="<?= site_url('login') ?>">Sign in</a></p>
            </div>
        </section>
    </main>
</body>
</html>
