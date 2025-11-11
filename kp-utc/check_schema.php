<?php
// Quick schema check script
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Check if jumlah_orang column exists
if (Schema::hasColumn('pemesanan_fasilitas', 'jumlah_orang')) {
    echo "✅ Column 'jumlah_orang' EXISTS in pemesanan_fasilitas\n";
} else {
    echo "❌ Column 'jumlah_orang' DOES NOT EXIST in pemesanan_fasilitas\n";
    echo "\nCurrent columns:\n";
    $columns = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'pemesanan_fasilitas'");
    foreach ($columns as $col) {
        echo "  - " . $col->COLUMN_NAME . "\n";
    }
}

// Check sample data
echo "\n\nSample data from pemesanan_fasilitas:\n";
$data = DB::table('pemesanan_fasilitas')->limit(3)->get();
foreach ($data as $row) {
    echo json_encode($row, JSON_PRETTY_PRINT) . "\n";
}
