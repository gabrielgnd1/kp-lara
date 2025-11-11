<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Dokumen Reservasi Table ===\n";
$docs = DB::table('dokumen_reservasi')->limit(10)->get();
echo json_encode($docs->toArray(), JSON_PRETTY_PRINT);

echo "\n\n=== Reservasi Table ===\n";
$reservasi = DB::table('reservasi')->limit(5)->get();
foreach ($reservasi as $r) {
    echo "ID: {$r->id}, Nama: {$r->nama_pemesan}, Status: {$r->status_pembayaran}\n";
}

echo "\n=== Dokumen untuk Reservasi ID 1 ===\n";
$dokForRes1 = DB::table('dokumen_reservasi')->where('reservasi_id', 1)->get();
echo json_encode($dokForRes1->toArray(), JSON_PRETTY_PRINT);
