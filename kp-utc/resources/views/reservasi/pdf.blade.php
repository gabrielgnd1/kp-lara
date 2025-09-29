<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Reservasi</title>
</head>
<body>
    <h1>Detail Reservasi</h1>
    
    <p>Nama Pemesan: {{ $reservasi->nama_pemesan }}</p>
    <p>No Telepon: {{ $reservasi->no_telepon }}</p>
    <p>Email: {{ $reservasi->email }}</p>
    <p>Judul Kegiatan: {{ $reservasi->judul_kegiatan }}</p>
    <p>Waktu Check In: {{ $reservasi->waktu_check_in }}</p>
    <p>Waktu Check Out: {{ $reservasi->waktu_check_out }}</p>
    <p>Jumlah Laki-laki: {{ $reservasi->jumlah_laki }}</p>
    <p>Jumlah Perempuan: {{ $reservasi->jumlah_perempuan }}</p>
    <p>Status Reservasi: {{ $reservasi->status_reservasi }}</p>
    <p>Status Pembayaran: {{ $reservasi->status_pembayaran }}</p>
    <p>Tanggal Dibuat: {{ $reservasi->tanggal_dibuat }}</p>
</body>
</html>
