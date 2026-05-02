<?php
require_once 'includes/functions.php';

echo "BASE_URL: " . BASE_URL . "\n";
echo "Asset Path: public/uploads/test.png\n";
echo "Generated URL: " . asset('public/uploads/test.png') . "\n";
?>
