<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check if file logs exist
$logFile = storage_path('logs/laravel.log');
echo "Log file: $logFile\n";

if (file_exists($logFile)) {
    $lastLines = shell_exec("tail -n 50 " . escapeshellarg($logFile));
    echo "Last 50 lines of log:\n";
    echo $lastLines;
} else {
    echo "Log file not found\n";
}

?>
