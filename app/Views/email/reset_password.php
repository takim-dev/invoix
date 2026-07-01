<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
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
        .button {
            display: inline-block;
            background-color: #6571ff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
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
        <h1>Reset Your Password</h1>
        <p>Hello <?= esc($name) ?>,</p>
        <p>We received a request to reset your password for <?= esc($app_name) ?>. Click the button below to create a new password:</p>
        <a href="<?= esc($reset_url) ?>" class="button">Reset Password</a>
        <p>Or copy and paste this link into your browser:</p>
        <p><?= esc($reset_url) ?></p>
        <p><strong>This link will expire in 1 hour.</strong></p>
        <div class="footer">
            <p>If you didn't request a password reset, you can safely ignore this email.</p>
            <p>&copy; <?= date('Y') ?> <?= esc($app_name) ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
