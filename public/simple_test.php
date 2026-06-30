<?php
echo "Method: " . ($_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN') . "\n";
echo "POST data: " . print_r($_POST, true) . "\n";
