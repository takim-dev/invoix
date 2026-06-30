<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
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
            color: #4CAF50;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
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
        <h1>Welcome to <?= esc($app_name) ?>!</h1>
        <p>Hello <?= esc($name) ?>,</p>
        <p>Thank you for registering with <?= esc($app_name) ?>. Please verify your email address by clicking the button below:</p>
        <a href="<?= esc($verification_url) ?>" class="button">Verify Email Address</a>
        <p>Or copy and paste this link into your browser:</p>
        <p><?= esc($verification_url) ?></p>
        <p>This link will expire in 24 hours.</p>
        <div class="footer">
            <p>If you didn't create an account with <?= esc($app_name) ?>, you can safely ignore this email.</p>
            <p>&copy; <?= date('Y') ?> <?= esc($app_name) ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
