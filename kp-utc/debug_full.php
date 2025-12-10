<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservasi;
use App\Models\PemesananFasilitas;
use App\Models\Fasilitas;
use Carbon\Carbon;

// Get all reservasi with check-in/check-out dates
echo "=== ALL RESERVASI BOOKINGS ===\n";
$reservasis = Reservasi::select('id', 'waktu_check_in', 'waktu_check_out')->get();

foreach ($reservasis as $res) {
    if ($res->waktu_check_in) {
        echo "ID {$res->id}: {$res->waktu_check_in->format('Y-m-d H:i:s')} to {$res->waktu_check_out->format('Y-m-d H:i:s')}\n";
        
        // Get facilities in this booking
        $pemesanans = PemesananFasilitas::where('reservasi_id', $res->id)->get();
        foreach ($pemesanans as $pem) {
            $fac = Fasilitas::find($pem->fasilitas_id);
            if ($fac) {
                echo "  - {$fac->nama} (ID: {$fac->id}, Day: {$fac->day}, Jenis User: {$fac->jenis_user})\n";
            }
        }
    }
}

echo "\n=== TEST OVERLAP: 2025-12-18 20:10 to 2025-12-20 20:11 ===\n";
$testStart = Carbon::parse('2025-12-18 20:10:00');
$testEnd = Carbon::parse('2025-12-20 20:11:00');

echo "Test Period: {$testStart->format('Y-m-d H:i:s')} to {$testEnd->format('Y-m-d H:i:s')}\n\n";

// Get all unique facility names
$allNames = Fasilitas::distinct('nama')->pluck('nama')->toArray();

foreach ($allNames as $name) {
    echo "=== {$name} ===\n";
    $variants = Fasilitas::where('nama', $name)->get();
    
    foreach ($variants as $var) {
        // Check if booked
        $booked = PemesananFasilitas::whereHas('reservasi', function ($q) use ($testStart, $testEnd) {
            $q->where('waktu_check_in', '<', $testEnd)
              ->where('waktu_check_out', '>', $testStart);
        })->where('fasilitas_id', $var->id)->exists();
        
        $status = $booked ? "BOOKED" : "AVAILABLE";
        echo "  ID {$var->id} ({$var->day} - {$var->jenis_user}): {$status}\n";
    }
    echo "\n";
}

?>
