<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Manual - KP Lapangan UTC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .cover-page {
            text-align: center;
            padding: 80px 0;
            border-bottom: 3px solid #2c3e50;
            margin-bottom: 50px;
        }
        
        .cover-page h1 {
            font-size: 48px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .cover-page p {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .cover-page .version {
            margin-top: 30px;
            font-size: 14px;
            color: #999;
        }
        
        .toc {
            page-break-after: always;
            margin-bottom: 50px;
        }
        
        .toc h2 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }
        
        .toc ul {
            list-style: none;
        }
        
        .toc li {
            margin-bottom: 12px;
            font-size: 16px;
        }
        
        .toc a {
            color: #3498db;
            text-decoration: none;
        }
        
        .section {
            page-break-before: always;
            margin-bottom: 40px;
        }
        
        .section h2 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        
        .section h3 {
            font-size: 20px;
            color: #34495e;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-left: 10px;
            border-left: 4px solid #3498db;
        }
        
        .section p {
            margin-bottom: 15px;
            text-align: justify;
        }
        
        .section ul, .section ol {
            margin-left: 30px;
            margin-bottom: 15px;
        }
        
        .section li {
            margin-bottom: 10px;
        }
        
        .feature-box {
            background-color: #ecf0f1;
            padding: 20px;
            border-left: 4px solid #3498db;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .feature-box h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .step-box {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 15px 0;
        }
        
        .step-box strong {
            color: #3498db;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        table th {
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        table td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .note {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .note strong {
            color: #856404;
        }
        
        .tip {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .tip strong {
            color: #0c5460;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                padding: 30px;
            }
            
            .section {
                page-break-before: always;
            }
        }
        
        .print-button {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .print-button button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        
        .print-button button:hover {
            background-color: #2980b9;
        }
        
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="print-button">
            <button onclick="window.print()">🖨️ Print to PDF</button>
        </div>
        
        <!-- Cover Page -->
        <div class="cover-page">
            <h1>📚 USER MANUAL</h1>
            <p>KP Lapangan UTC Management System</p>
            <p>Sistem Manajemen Reservasi & Laporan</p>
            <div class="version">
                <p>Version 1.0</p>
                <p>January 2026</p>
            </div>
        </div>
        
        <!-- Table of Contents -->
        <div class="toc">
            <h2>📑 Daftar Isi</h2>
            <ul>
                <li><a href="#1">1. Pendahuluan</a></li>
                <li><a href="#2">2. Panduan Reservasi</a></li>
                <li><a href="#3">3. Panduan Laporan</a></li>
                <li><a href="#4">4. Panel Admin</a></li>
                <li><a href="#5">5. Fitur Cetak</a></li>
                <li><a href="#6">6. Tips & Trik</a></li>
                <li><a href="#7">7. Troubleshooting</a></li>
            </ul>
        </div>
        
        <!-- Section 1: Introduction -->
        <div class="section" id="1">
            <h2>1. Pendahuluan</h2>
            <p>
                Selamat datang di KP Lapangan UTC Management System. Sistem ini dirancang untuk memudahkan 
                manajemen reservasi fasilitas dan pembuatan laporan dengan antarmuka yang user-friendly.
            </p>
            
            <h3>1.1 Fitur Utama</h3>
            <ul>
                <li><strong>Reservasi Fasilitas:</strong> Melakukan pemesanan fasilitas dengan sistem otomatis</li>
                <li><strong>Manajemen Laporan:</strong> Membuat dan mengelola laporan dengan foto multiple</li>
                <li><strong>Dashboard:</strong> Visualisasi data dan informasi real-time</li>
                <li><strong>Sistem Cetak:</strong> Mencetak dokumen ke format PDF</li>
                <li><strong>Kode Otomatis:</strong> Sistem penomoran otomatis untuk laporan</li>
            </ul>
            
            <h3>1.2 Persyaratan Sistem</h3>
            <ul>
                <li>Browser modern (Chrome, Firefox, Safari, Edge)</li>
                <li>Koneksi internet yang stabil</li>
                <li>JavaScript harus diaktifkan</li>
                <li>Ukuran file upload maksimal: 5MB per file</li>
            </ul>
        </div>
        
        <!-- Section 2: Reservasi -->
        <div class="section" id="2">
            <h2>2. Panduan Reservasi</h2>
            <p>
                Fitur reservasi memungkinkan Anda untuk memesan fasilitas dengan mudah dan cepat. 
                Sistem akan secara otomatis menghitung harga berdasarkan tipe member dan hari.
            </p>
            
            <h3>2.1 Membuat Reservasi Baru</h3>
            <div class="step-box">
                <strong>Langkah 1:</strong> Klik tombol "Tambah Reservasi" di halaman utama
            </div>
            <div class="step-box">
                <strong>Langkah 2:</strong> Pilih jenis member:
                <ul style="margin-top: 10px;">
                    <li><strong>Internal - Mahasiswa:</strong> Untuk pemesanan dari kalangan mahasiswa</li>
                    <li><strong>Internal - Karyawan:</strong> Untuk pemesanan dari kalangan karyawan</li>
                    <li><strong>Eksternal:</strong> Untuk pemesanan dari luar institusi</li>
                </ul>
            </div>
            <div class="step-box">
                <strong>Langkah 3:</strong> Isi informasi pemesan (nama, telepon, email)
            </div>
            <div class="step-box">
                <strong>Langkah 4:</strong> Pilih tanggal dan waktu check-in dan check-out
            </div>
            <div class="step-box">
                <strong>Langkah 5:</strong> Pilih fasilitas yang diinginkan
            </div>
            <div class="step-box">
                <strong>Langkah 6:</strong> Klik "Simpan" untuk menyimpan reservasi
            </div>
            
            <h3>2.2 Perhitungan Harga</h3>
            <p>Harga fasilitas dihitung berdasarkan:</p>
            <ul>
                <li><strong>Tipe Hari:</strong> Weekday (Senin-Jumat) atau Weekend (Sabtu-Minggu)</li>
                <li><strong>Jenis Member:</strong> Setiap tipe member memiliki harga berbeda</li>
                <li><strong>Diskon:</strong> Diskon dapat diberikan dalam persentase</li>
            </ul>
            
            <div class="feature-box">
                <h4>💡 Fitur Otomatis</h4>
                <p>
                    Sistem akan secara otomatis menyesuaikan harga ketika Anda mengubah tanggal atau fasilitas. 
                    Harga akan ditampilkan secara real-time di bagian "Harga Akhir".
                </p>
            </div>
            
            <h3>2.3 Status Reservasi</h3>
            <table>
                <tr>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
                <tr>
                    <td>ACC</td>
                    <td>Reservasi telah diterima dan disetujui</td>
                </tr>
                <tr>
                    <td>NOT ACC</td>
                    <td>Reservasi menunggu persetujuan atau ditolak</td>
                </tr>
                <tr>
                    <td>BARU</td>
                    <td>Reservasi baru yang belum diproses</td>
                </tr>
            </table>
        </div>
        
        <!-- Section 3: Laporan -->
        <div class="section" id="3">
            <h2>3. Panduan Laporan</h2>
            <p>
                Fitur laporan memungkinkan Anda untuk membuat laporan lengkap dengan foto dan dokumentasi. 
                Setiap laporan akan mendapatkan kode unik secara otomatis.
            </p>
            
            <h3>3.1 Membuat Laporan Baru</h3>
            <div class="step-box">
                <strong>Langkah 1:</strong> Navigasi ke menu "Laporan" atau "Tambah Laporan"
            </div>
            <div class="step-box">
                <strong>Langkah 2:</strong> Isi nama laporan (contoh: "Kerusakan AC di Ruang Rapat A")
            </div>
            <div class="step-box">
                <strong>Langkah 3:</strong> Tulis deskripsi detail tentang kondisi atau masalah
            </div>
            <div class="step-box">
                <strong>Langkah 4:</strong> Upload foto (hingga 4 foto). Klik tombol upload dan pilih file gambar
            </div>
            <div class="step-box">
                <strong>Langkah 5:</strong> Pilih area lokasi laporan
            </div>
            <div class="step-box">
                <strong>Langkah 6:</strong> Atur prioritas dan tipe laporan
            </div>
            <div class="step-box">
                <strong>Langkah 7:</strong> Klik "Simpan" untuk membuat laporan
            </div>
            
            <h3>3.2 Upload Foto Multiple</h3>
            <p>Sistem memungkinkan Anda untuk upload hingga 4 foto per laporan.</p>
            <ul>
                <li>Klik area upload untuk memilih file dari komputer Anda</li>
                <li>Anda dapat menambahkan foto satu per satu atau sekaligus</li>
                <li>Format foto yang didukung: JPG, PNG, GIF, WebP</li>
                <li>Ukuran maksimal: 5MB per file</li>
            </ul>
            
            <div class="tip">
                <strong>💡 Tips:</strong> Ambil foto dari berbagai sudut untuk dokumentasi yang lebih baik. 
                Pastikan pencahayaan cukup dan objek terlihat jelas.
            </div>
            
            <h3>3.3 Kode Laporan Otomatis</h3>
            <p>Setiap laporan akan mendapatkan kode unik dengan format:</p>
            <div class="feature-box">
                <h4>Format: ME[COUNTER][MONTH][YEAR]</h4>
                <ul style="margin-top: 10px;">
                    <li><strong>ME:</strong> Prefix untuk laporan (Maintenance/Laporan)</li>
                    <li><strong>[COUNTER]:</strong> Nomor urut (0001-9999)</li>
                    <li><strong>[MONTH]:</strong> Bulan (01-12)</li>
                    <li><strong>[YEAR]:</strong> Tahun 2 digit (26 untuk 2026)</li>
                </ul>
                <p style="margin-top: 15px;"><strong>Contoh:</strong> ME000101026 (Laporan pertama bulan Januari 2026)</p>
            </div>
            
            <h3>3.4 Status Laporan</h3>
            <table>
                <tr>
                    <th>Status</th>
                    <th>Prioritas</th>
                    <th>Keterangan</th>
                </tr>
                <tr>
                    <td>Belum Diproses</td>
                    <td>Rendah/Sedang/Tinggi</td>
                    <td>Laporan baru, belum ditindaklanjuti</td>
                </tr>
                <tr>
                    <td>Diproses</td>
                    <td>Rendah/Sedang/Tinggi</td>
                    <td>Laporan sedang dikerjakan</td>
                </tr>
                <tr>
                    <td>Selesai</td>
                    <td>Rendah/Sedang/Tinggi</td>
                    <td>Laporan telah dityelesaikan</td>
                </tr>
            </table>
        </div>
        
        <!-- Section 4: Admin -->
        <div class="section" id="4">
            <h2>4. Panel Admin</h2>
            <p>
                Panel admin memberikan akses penuh untuk mengelola reservasi dan laporan. 
                Hanya admin yang dapat mengakses fitur ini.
            </p>
            
            <h3>4.1 Dashboard Admin</h3>
            <ul>
                <li>Melihat ringkasan data reservasi dan laporan</li>
                <li>Melihat statistik dan grafik analitik</li>
                <li>Akses cepat ke daftar reservasi dan laporan terbaru</li>
            </ul>
            
            <h3>4.2 Mengelola Laporan</h3>
            <p>Admin dapat:</p>
            <ul>
                <li>Melihat semua laporan dari semua user</li>
                <li>Mengubah prioritas laporan</li>
                <li>Mengubah status laporan (Belum Diproses → Diproses → Selesai)</li>
                <li>Menambahkan catatan atau komentar</li>
                <li>Melihat dan menghapus foto</li>
            </ul>
            
            <h3>4.3 Mengelola Reservasi</h3>
            <p>Admin dapat:</p>
            <ul>
                <li>Melihat semua reservasi</li>
                <li>Menyetujui atau menolak reservasi</li>
                <li>Mengubah detail reservasi</li>
                <li>Melihat ringkasan pembayaran</li>
            </ul>
        </div>
        
        <!-- Section 5: Cetak -->
        <div class="section" id="5">
            <h2>5. Fitur Cetak</h2>
            <p>
                Sistem menyediakan fitur cetak untuk menghasilkan dokumen profesional yang dapat disimpan 
                sebagai PDF atau dicetak langsung ke printer.
            </p>
            
            <h3>5.1 Cara Mencetak Laporan</h3>
            <div class="step-box">
                <strong>Langkah 1:</strong> Buka detail laporan di halaman edit admin
            </div>
            <div class="step-box">
                <strong>Langkah 2:</strong> Klik tombol "Print" di bagian atas halaman
            </div>
            <div class="step-box">
                <strong>Langkah 3:</strong> Halaman print akan terbuka di tab baru
            </div>
            <div class="step-box">
                <strong>Langkah 4:</strong> Klik tombol "Print" di halaman atau gunakan Ctrl+P
            </div>
            <div class="step-box">
                <strong>Langkah 5:</strong> Pilih printer atau "Save as PDF" untuk menyimpan
            </div>
            
            <h3>5.2 Konten yang Dicetak</h3>
            <p>Laporan cetak mencakup:</p>
            <ul>
                <li>Kode laporan (di sudut kiri atas)</li>
                <li>Informasi detail laporan (nama, deskripsi, area)</li>
                <li>Data tanggal (lapor, deadline, selesai)</li>
                <li>Informasi pelapor</li>
                <li>Status dan prioritas</li>
                <li>Semua foto yang diupload</li>
                <li>Tanggal dan waktu cetak</li>
            </ul>
            
            <div class="tip">
                <strong>💡 Tips:</strong> Format cetak sudah dioptimalkan untuk kertas A4. 
                Gunakan margin normal atau narrow untuk hasil terbaik.
            </div>
        </div>
        
        <!-- Section 6: Tips & Trik -->
        <div class="section" id="6">
            <h2>6. Tips & Trik</h2>
            
            <h3>6.1 Reservasi</h3>
            <ul>
                <li><strong>Pesan Lebih Awal:</strong> Pesan fasilitas minimal 1-2 hari sebelumnya</li>
                <li><strong>Perhatikan Harga:</strong> Harga Weekend lebih mahal dari Weekday</li>
                <li><strong>Gunakan Diskon:</strong> Tanyakan kepada admin tentang program diskon</li>
                <li><strong>Konfirmasi Booking:</strong> Hubungi admin untuk konfirmasi sebelum tanggal acara</li>
            </ul>
            
            <h3>6.2 Laporan</h3>
            <ul>
                <li><strong>Deskripsi Lengkap:</strong> Semakin detail deskripsi, semakin cepat ditangani</li>
                <li><strong>Foto Berkualitas:</strong> Ambil foto yang jelas dari berbagai sudut</li>
                <li><strong>Prioritas Tepat:</strong> Gunakan prioritas yang sesuai dengan urgensi</li>
                <li><strong>Pantau Status:</strong> Cek dashboard untuk update status laporan Anda</li>
            </ul>
            
            <h3>6.3 Dashboard</h3>
            <ul>
                <li><strong>Update Real-time:</strong> Dashboard diperbarui secara otomatis setiap 5 menit</li>
                <li><strong>Filter Data:</strong> Gunakan filter untuk menemukan data spesifik</li>
                <li><strong>Export Data:</strong> Beberapa modul mendukung export ke Excel</li>
            </ul>
        </div>
        
        <!-- Section 7: Troubleshooting -->
        <div class="section" id="7">
            <h2>7. Troubleshooting</h2>
            
            <h3>7.1 Masalah Upload Foto</h3>
            <div class="feature-box">
                <h4>❌ Foto tidak bisa diupload</h4>
                <p>
                    <strong>Solusi:</strong> Periksa ukuran file (maksimal 5MB), pastikan format yang didukung 
                    (JPG, PNG, GIF, WebP), dan pastikan koneksi internet stabil.
                </p>
            </div>
            
            <h3>7.2 Masalah Simpan Data</h3>
            <div class="feature-box">
                <h4>❌ Data tidak bisa disimpan</h4>
                <p>
                    <strong>Solusi:</strong> Pastikan semua field yang wajib sudah diisi (bertanda bintang merah *). 
                    Refresh halaman dan coba lagi, atau hubungi admin.
                </p>
            </div>
            
            <h3>7.3 Masalah Cetak</h3>
            <div class="feature-box">
                <h4>❌ Halaman cetak tidak terbuka</h4>
                <p>
                    <strong>Solusi:</strong> Pastikan browser tidak memblokir pop-up windows. 
                    Cek pengaturan browser atau gunakan browser berbeda.
                </p>
            </div>
            
            <h3>7.4 Masalah Login</h3>
            <div class="feature-box">
                <h4>❌ Tidak bisa login</h4>
                <p>
                    <strong>Solusi:</strong> Pastikan email dan password benar. 
                    Jika lupa password, hubungi admin untuk reset. Coba gunakan browser incognito.
                </p>
            </div>
            
            <h3>7.5 Kontak Support</h3>
            <p>
                Jika mengalami masalah yang tidak terdaftar di atas, silakan hubungi admin melalui:
            </p>
            <ul>
                <li><strong>Email:</strong> admin@utc.com</li>
                <li><strong>Telepon:</strong> +62-xxx-xxxx-xxxx</li>
                <li><strong>Chat/WhatsApp:</strong> +62-xxx-xxxx-xxxx</li>
            </ul>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>© 2026 KP Lapangan UTC. All rights reserved.</p>
            <p>Manual Version 1.0 - January 2026</p>
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
