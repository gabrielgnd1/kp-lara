<?php

use App\Models\Reservasi;
use App\Models\PemesananFasilitas;
use Carbon\Carbon;

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check all reservasi
echo "=== All Reservasi Data ===\n";
$reservasi = Reservasi::with('pemesananFasilitas')->get();
foreach ($reservasi as $r) {
    echo "ID: {$r->id}, Check In: {$r->waktu_check_in}, Check Out: {$r->waktu_check_out}\n";
    if ($r->pemesananFasilitas) {
        foreach ($r->pemesananFasilitas as $pf) {
            $fasilitas = $pf->fasilitas;
            if ($fasilitas) {
                echo "  - Fasilitas: {$fasilitas->nama}\n";
            }
        }
    }
}

// Test overlap
echo "\n=== Test Overlap ===\n";
$mulai = Carbon::parse('2025-12-18 20:10:00');  // 12/18/2025 08:10 PM
$selesai = Carbon::parse('2025-12-20 20:11:00'); // 12/20/2025 08:11 PM

echo "User Input Range: {$mulai} to {$selesai}\n";

$overlapping = Reservasi::where('waktu_check_in', '<', $selesai)
    ->where('waktu_check_out', '>', $mulai)
    ->with('pemesananFasilitas')
    ->get();

echo "Overlapping reservations count: " . $overlapping->count() . "\n";
foreach ($overlapping as $r) {
    echo "ID: {$r->id}, Check In: {$r->waktu_check_in}, Check Out: {$r->waktu_check_out}\n";
    if ($r->pemesananFasilitas) {
        foreach ($r->pemesananFasilitas as $pf) {
            $fasilitas = $pf->fasilitas;
            if ($fasilitas) {
                echo "  - Fasilitas: {$fasilitas->nama}\n";
            }
        }
    }
}

// Test specific facility
echo "\n=== Check Multifunction Hall ===\n";
$bookedMultifunction = PemesananFasilitas::whereHas('reservasi', function ($query) use ($mulai, $selesai) {
    $query->where('waktu_check_in', '<', $selesai)
          ->where('waktu_check_out', '>', $mulai);
})->whereHas('fasilitas', function ($query) {
    $query->where('nama', 'Multifunction Hall');
})->with('reservasi', 'fasilitas')->get();

echo "Multifunction Hall bookings in range: " . $bookedMultifunction->count() . "\n";
foreach ($bookedMultifunction as $pf) {
    echo "Fasilitas: {$pf->fasilitas->nama}\n";
    echo "Reservasi Check In: {$pf->reservasi->waktu_check_in}\n";
    echo "Reservasi Check Out: {$pf->reservasi->waktu_check_out}\n";
}
