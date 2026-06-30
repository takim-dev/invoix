<?php
require __DIR__ . '/../vendor/autoload.php';
$app = \CodeIgniter\Config\Factories::injector()->create('codeigniter');
$app->initialize();
echo "Request method: " . $app->request->getMethod() . "\n";
echo "Is POST: " . ($app->request->getMethod() === 'post' ? 'YES' : 'NO') . "\n";
echo "SERVER REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NOT SET') . "\n";
