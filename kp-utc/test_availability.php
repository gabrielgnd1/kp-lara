<?php
require 'vendor/autoload.php';

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Application;
use App\Models\Fasilitas;
use App\Models\PemesananFasilitas;
use Carbon\Carbon;

// Bootstrap Laravel
$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->make('Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables')->bootstrap($app);

$app->make('Illuminate\Foundation\Bootstrap\RegisterFacadeAliases')->bootstrap($app);
$app->make('Illuminate\Foundation\Bootstrap\RegisterProviders')->bootstrap($app);
$app->make('Illuminate\Foundation\Bootstrap\BootProviders')->bootstrap($app);

// Test availability checking
$mulai = Carbon::createFromFormat('Y-m-d H:i', '2025-12-18 20:10');
$selesai = Carbon::createFromFormat('Y-m-d H:i', '2025-12-20 20:11');

echo "=== Testing Multifunction Hall ===\n";
echo "Search Range: " . $mulai->toString() . " to " . $selesai->toString() . "\n\n";

$hall = Fasilitas::where('nama', 'Multifunction Hall')->first();
if (!$hall) {
    echo "Multifunction Hall not found\n";
    exit;
}

echo "Facility ID: " . $hall->id . "\n";
echo "Facility Status: " . $hall->status . "\n";

// Check bookings
$isBooked = PemesananFasilitas::whereHas('reservasi', function ($query) use ($mulai, $selesai) {
    $query->where('waktu_check_in', '<', $selesai)
          ->where('waktu_check_out', '>', $mulai);
})->where('fasilitas_id', $hall->id)->exists();

echo "Is Booked: " . ($isBooked ? 'YES' : 'NO') . "\n";

$isAvailable = ($hall->status === 'Available' && !$isBooked);
echo "Is Available for Period: " . ($isAvailable ? 'YES (Tersedia)' : 'NO (Tidak Tersedia)') . "\n";

// Show all bookings for this facility
echo "\nAll bookings for Multifunction Hall:\n";
$bookings = PemesananFasilitas::where('fasilitas_id', $hall->id)
    ->with('reservasi')
    ->get();

foreach ($bookings as $booking) {
    echo "  - Reservasi ID: " . $booking->reservasi_id . ", Check In: " . $booking->reservasi->waktu_check_in . ", Check Out: " . $booking->reservasi->waktu_check_out . "\n";
}
