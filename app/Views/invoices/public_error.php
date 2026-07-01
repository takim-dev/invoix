<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f5f6fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .error-box { text-align: center; background: #fff; padding: 60px 40px; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); max-width: 480px; }
        .error-box .icon { font-size: 3rem; color: #1b3a5c; margin-bottom: 20px; }
        .error-box h2 { color: #1a1a2e; margin-bottom: 10px; }
        .error-box p { color: #777; }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="icon"><i class="bi bi-file-earmark-x"></i></div>
        <h2><?= esc($title) ?></h2>
        <p><?= esc(lang('App.invoice_not_found_desc')) ?></p>
    </div>
</body>
</html>
