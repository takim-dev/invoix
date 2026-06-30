<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 30px;
        }
        h1 {
            color: #6571ff;
        }
        .info {
            background-color: #eef;
            border-left: 4px solid #6571ff;
            padding: 12px 16px;
            margin: 20px 0;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Test Email from <?= esc($app_name) ?></h1>
        <p>Hi <?= esc($recipient) ?>,</p>
        <p>This is a test email sent from the <strong><?= esc($app_name) ?></strong> admin panel to verify that your SMTP configuration is working correctly.</p>
        <div class="info">
            <strong>Recipient:</strong> <?= esc($recipient) ?><br>
            <strong>Sent at:</strong> <?= esc($sent_at) ?>
        </div>
        <p>If you received this email, your SMTP settings are configured properly.</p>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= esc($app_name) ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
