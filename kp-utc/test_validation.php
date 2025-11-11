<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Filament\Reservasi\Resources\ReservasiResource;
use Illuminate\Support\Facades\DB;

echo "=== Testing disabledDatesForGroup logic ===\n\n";

// Simulate editing reservasi 27 (has actual dates)
ReservasiResource::$currentEditingReservasiId = 27;
ReservasiResource::clearBookedDateCache();

// Get facilities for "Avocado Cottage" 
$facilities = DB::table('fasilitas')
    ->where('status', 'Available')
    ->whereRaw('LOWER(nama) = ?', ['avocado cottage'])
    ->pluck('id')
    ->all();

echo "Avocado Cottage facility IDs: " . json_encode($facilities) . "\n\n";

// Check what bookedDatesForFacility returns
if (!empty($facilities)) {
    $facId = $facilities[0];
    
    // Get WITHOUT exclude
    $allDates = DB::table('pemesanan_fasilitas')
        ->select('mulai', 'selesai', 'reservasi_id')
        ->where('fasilitas_id', $facId)
        ->get();
    
    echo "All bookings for facility $facId:\n";
    foreach ($allDates as $d) {
        echo "  - Res {$d->reservasi_id}: {$d->mulai} to {$d->selesai}\n";
    }
    
    // Get WITH exclude
    $filteredDates = DB::table('pemesanan_fasilitas')
        ->select('mulai', 'selesai', 'reservasi_id')
        ->where('fasilitas_id', $facId)
        ->where('reservasi_id', '!=', 27)
        ->get();
    
    echo "\nBookings EXCLUDING Reservasi 27:\n";
    foreach ($filteredDates as $d) {
        echo "  - Res {$d->reservasi_id}: {$d->mulai} to {$d->selesai}\n";
    }
    
    echo "\nFiltered count: " . count($filteredDates) . "\n";
}

echo "\n=== Current editing reservasi ID: " . ReservasiResource::$currentEditingReservasiId . " ===\n";

?>
