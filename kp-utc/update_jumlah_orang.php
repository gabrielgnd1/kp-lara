<?php

use App\Models\Reservasi;
use Illuminate\Support\Facades\DB;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update semua pemesanan_fasilitas yang NULL jumlah_orang menjadi 1
$updated = DB::table('pemesanan_fasilitas')
    ->whereNull('jumlah_orang')
    ->update(['jumlah_orang' => 1]);

echo "✅ Updated $updated rows with NULL jumlah_orang to 1\n";

// Also check per-person cottages dan update mereka dengan jumlah yang sesuai jika ada info
// (ini optional, tapi baik untuk data consistency)

$perPersonCottages = ['avocado cottage', 'banana cottage', 'cassava cottage', 'durian cottage'];

foreach ($perPersonCottages as $cottage) {
    $rows = DB::table('pemesanan_fasilitas as pf')
        ->join('fasilitas as f', 'pf.fasilitas_id', '=', 'f.id')
        ->where(DB::raw('LOWER(f.nama)'), '=', strtolower($cottage))
        ->select('pf.id', 'pf.reservasi_id', 'pf.jumlah_orang')
        ->get();
    
    echo "Found " . count($rows) . " records for $cottage\n";
}

echo "\n✅ Database updated successfully!\n";
echo "Now try refreshing the reservation detail page in browser.\n";
