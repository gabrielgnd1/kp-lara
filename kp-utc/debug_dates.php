<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Fasilitas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Test scenario: Ambil satu reservasi
$reservasiId = 1; // Sesuaikan dengan ID yang ingin ditest

// Ambil fasilitas yang digunakan di reservasi ini
$booked = DB::table('pemesanan_fasilitas')
    ->where('reservasi_id', $reservasiId)
    ->get();

echo "=== Testing Reservasi ID: $reservasiId ===\n\n";

foreach ($booked as $b) {
    echo "Fasilitas ID: {$b->fasilitas_id}\n";
    echo "Mulai: {$b->mulai}\n";
    echo "Selesai: {$b->selesai}\n";
    
    // Test WITHOUT excluding (old behavior)
    $datesAll = DB::table('pemesanan_fasilitas')
        ->select('mulai', 'selesai', 'reservasi_id')
        ->where('fasilitas_id', $b->fasilitas_id)
        ->get();
    
    echo "Total bookings for this facility: " . count($datesAll) . "\n";
    
    // Test WITH excluding
    $datesExcluded = DB::table('pemesanan_fasilitas')
        ->select('mulai', 'selesai', 'reservasi_id')
        ->where('fasilitas_id', $b->fasilitas_id)
        ->where('reservasi_id', '!=', $reservasiId)
        ->get();
    
    echo "Bookings EXCLUDING this reservasi: " . count($datesExcluded) . "\n";
    echo "Difference: " . (count($datesAll) - count($datesExcluded)) . " (should be >= 1)\n";
    
    echo "\n---\n\n";
}
?>
