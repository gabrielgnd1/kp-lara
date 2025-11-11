<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Filament\Reservasi\Resources\ReservasiResource;
use Illuminate\Support\Facades\DB;

echo "=== Testing with Res 27 (Camping + Tenda) ===\n\n";

ReservasiResource::$currentEditingReservasiId = 27;
ReservasiResource::clearBookedDateCache();

// Get what res 27 booked
$res27Bookings = DB::table('pemesanan_fasilitas')
    ->select('fasilitas_id', 'mulai', 'selesai')
    ->where('reservasi_id', 27)
    ->get();

echo "Res 27 booked:\n";
foreach ($res27Bookings as $b) {
    $facName = DB::table('fasilitas')->where('id', $b->fasilitas_id)->value('nama');
    echo "  - Fac {$b->fasilitas_id} ($facName): {$b->mulai} to {$b->selesai}\n";
}

echo "\n";

// Test with first facility ID from res 27
if ($res27Bookings->count() > 0) {
    $facId = $res27Bookings->first()->fasilitas_id;
    
    $allBookings = DB::table('pemesanan_fasilitas')
        ->select('mulai', 'selesai', 'reservasi_id')
        ->where('fasilitas_id', $facId)
        ->get();
    
    echo "All bookings for facility $facId:\n";
    foreach ($allBookings as $d) {
        echo "  - Res {$d->reservasi_id}: {$d->mulai} to {$d->selesai}\n";
    }
    
    $filtered = DB::table('pemesanan_fasilitas')
        ->select('mulai', 'selesai', 'reservasi_id')
        ->where('fasilitas_id', $facId)
        ->where('reservasi_id', '!=', 27)
        ->get();
    
    echo "\nFiltered (excluding Res 27):\n";
    foreach ($filtered as $d) {
        echo "  - Res {$d->reservasi_id}: {$d->mulai} to {$d->selesai}\n";
    }
    
    echo "\nTotal all: " . count($allBookings) . ", Filtered: " . count($filtered) . "\n";
}

?>
