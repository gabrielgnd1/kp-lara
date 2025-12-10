<?php
// Simple debug script to check bookings
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservasi;
use App\Models\PemesananFasilitas;
use App\Models\Fasilitas;
use Carbon\Carbon;

// Get the booking that should block facilities
$booking = Reservasi::find(1); // Or the specific booking ID

if ($booking) {
    echo "=== BOOKING DATA ===\n";
    echo "Check-in: " . ($booking->waktu_check_in ? $booking->waktu_check_in->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "Check-out: " . ($booking->waktu_check_out ? $booking->waktu_check_out->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "\n";
    
    // Get facilities in this booking
    $facilities = $booking->pemesananFasilitas()->pluck('fasilitas_id')->toArray();
    echo "Facilities booked: " . implode(', ', $facilities) . "\n";
    
    // Get facility names
    $facNames = Fasilitas::whereIn('id', $facilities)->pluck('nama')->unique()->toArray();
    echo "Facility names: " . implode(', ', $facNames) . "\n\n";
}

// Test overlap calculation
$testStart = Carbon::createFromFormat('Y-m-d H:i:s', '2025-12-18 20:10:00');
$testEnd = Carbon::createFromFormat('Y-m-d H:i:s', '2025-12-20 20:11:00');

echo "=== TEST SEARCH (12/18 20:10 to 12/20 20:11) ===\n";
echo "Test start: " . $testStart->format('Y-m-d H:i:s') . "\n";
echo "Test end: " . $testEnd->format('Y-m-d H:i:s') . "\n\n";

// Check overlaps for specific facilities
$testFacilityIds = Fasilitas::whereIn('nama', ['Multifunction Hall', 'Avocado Cottage', 'VIP Cottage-Eucalyptus', 'Camping+Tenda'])
    ->pluck('id')
    ->toArray();

foreach ($testFacilityIds as $facId) {
    $overlapCount = PemesananFasilitas::whereHas('reservasi', function ($query) use ($testStart, $testEnd) {
        $query->where('waktu_check_in', '<', $testEnd)
              ->where('waktu_check_out', '>', $testStart);
    })->where('fasilitas_id', $facId)
    ->count();
    
    $facName = Fasilitas::find($facId)->nama;
    echo "{$facName} (ID: {$facId}): " . ($overlapCount > 0 ? "BOOKED" : "AVAILABLE") . "\n";
}
?>
