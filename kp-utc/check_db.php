<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Check all reservations with facilities
echo "=== All Reservations with Facilities ===\n";
$all = DB::table('pemesanan_fasilitas')
    ->select('reservasi_id', 'fasilitas_id', 'mulai', 'selesai')
    ->orderBy('reservasi_id')
    ->get();

foreach ($all as $row) {
    echo "Res ID: {$row->reservasi_id}, Fac ID: {$row->fasilitas_id}, ";
    echo "Mulai: {$row->mulai}, Selesai: {$row->selesai}\n";
}

echo "\n=== Facility Names ===\n";
$facs = DB::table('fasilitas')->select('id', 'nama')->get();
foreach ($facs as $f) {
    echo "ID {$f->id}: {$f->nama}\n";
}

echo "\n=== Testing with Reservasi ID 5 ===\n";
$res5 = DB::table('pemesanan_fasilitas')
    ->select('*')
    ->where('reservasi_id', 5)
    ->get();

echo "Count for Res 5: " . count($res5) . "\n";
foreach ($res5 as $r) {
    echo "- Fac {$r->fasilitas_id}: {$r->mulai} to {$r->selesai}\n";
}

?>
