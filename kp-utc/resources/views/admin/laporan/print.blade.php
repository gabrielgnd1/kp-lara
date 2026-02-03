<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Laporan - {{ $laporan->nama_laporan }}</title>
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
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            position: relative;
            padding-top: 30px;
        }
        
        .header h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            color: #666;
        }
        
        .detail-section {
            margin-bottom: 30px;
        }
        
        .detail-section h2 {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .detail-item {
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }
        
        .detail-item label {
            font-weight: 600;
            color: #2c3e50;
            display: block;
            margin-bottom: 5px;
        }
        
        .detail-item span {
            color: #555;
            font-size: 14px;
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .deskripsi-box {
            padding: 15px;
            background-color: #ecf0f1;
            border-radius: 4px;
            margin: 15px 0;
            line-height: 1.8;
        }
        
        .images-section {
            margin-top: 30px;
        }
        
        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .image-container {
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            background-color: #f9f9f9;
        }
        
        .image-container img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-right: 8px;
        }
        
        .badge-tinggi {
            background-color: #fee;
            color: #c00;
        }
        
        .badge-sedang {
            background-color: #ffeaa7;
            color: #d63031;
        }
        
        .badge-rendah {
            background-color: #d5f4e6;
            color: #00b894;
        }
        
        .badge-belum {
            background-color: #dfe6e9;
            color: #2d3436;
        }
        
        .badge-selesai {
            background-color: #d5f4e6;
            color: #00b894;
        }
        
        .badge-diproses {
            background-color: #ffeaa7;
            color: #d63031;
        }
        
        .badge-belumproses {
            background-color: #fee;
            color: #c00;
        }
        
        .footer {
            margin-top: 40px;
            text-align: right;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
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
                padding: 20px;
            }
            
            .no-print {
                display: none !important;
            }
            
            .images-grid {
                page-break-inside: avoid;
            }
        }
        
        .print-button-container {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .print-button-container button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .print-button-container button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="print-button-container no-print">
            <button onclick="window.print()">🖨️ Print</button>
        </div>
        
        <div class="header">
            <div style="text-align: left; position: absolute; top: 40px; left: 40px; font-size: 24px; font-weight: bold; color: #2c3e50;">
                {{ $laporan->kode_laporan ?? 'N/A' }}
            </div>
            <h1>📋 DETAIL LAPORAN</h1>
            <p>{{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
        </div>
        
        <!-- Detail Laporan -->
        <div class="detail-section">
            <h2>📝 Informasi Laporan</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Nama Laporan</label>
                    <span>{{ $laporan->nama_laporan }}</span>
                </div>
                
                <div class="detail-item">
                    <label>Tipe Laporan</label>
                    <span>{{ $laporan->tipe_laporan ?? 'N/A' }}</span>
                </div>
                
                <div class="detail-item">
                    <label>Area</label>
                    <span>{{ $laporan->area?->nama_area ?? 'N/A' }}</span>
                </div>
                
                <div class="detail-item">
                    <label>Tanggal Lapor</label>
                    <span>{{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('d F Y H:i') }}</span>
                </div>
                
                <div class="detail-item">
                    <label>Tanggal Deadline</label>
                    <span>{{ $laporan->tanggal_deadline ? \Carbon\Carbon::parse($laporan->tanggal_deadline)->format('d F Y') : 'Belum ditentukan' }}</span>
                </div>
                
                <div class="detail-item">
                    <label>Tanggal Selesai</label>
                    <span>{{ $laporan->tanggal_selesai ? \Carbon\Carbon::parse($laporan->tanggal_selesai)->format('d F Y') : 'Belum selesai' }}</span>
                </div>
                
                <div class="detail-item">
                    <label>Pelapor</label>
                    <span>{{ $laporan->user?->name ?? 'N/A' }} ({{ $laporan->user?->email ?? 'N/A' }})</span>
                </div>
                
                <div class="detail-item">
                    <label>Status</label>
                    <span>
                        <span class="status-badge badge-{{ strtolower($laporan->prioritas == 'Tinggi' ? 'tinggi' : ($laporan->prioritas == 'Sedang' ? 'sedang' : 'rendah')) }}">
                            Prioritas: {{ $laporan->prioritas }}
                        </span>
                        <span class="status-badge badge-{{ strtolower($laporan->decision == 'Selesai' ? 'selesai' : ($laporan->decision == 'Diproses' ? 'diproses' : 'belumproses')) }}">
                            {{ $laporan->decision }}
                        </span>
                    </span>
                </div>
                
                <div class="detail-item full-width">
                    <label>Deskripsi</label>
                    <div class="deskripsi-box">
                        {{ $laporan->deskripsi }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Images Section -->
        @if ($laporan->foto_laporan && (is_array($laporan->foto_laporan) ? count($laporan->foto_laporan) > 0 : $laporan->foto_laporan))
        <div class="detail-section images-section">
            <h2>📸 Foto Laporan</h2>
            <div class="images-grid">
                @if (is_array($laporan->foto_laporan))
                    @foreach ($laporan->foto_laporan as $foto)
                        <div class="image-container">
                            @php
                                $fotoPath = strpos($foto, 'laporan/') === false ? 'laporan/' . $foto : $foto;
                            @endphp
                            <img src="{{ asset('storage/' . $fotoPath) }}" alt="Foto Laporan">
                        </div>
                    @endforeach
                @else
                    <div class="image-container">
                        @php
                            $fotoPath = strpos($laporan->foto_laporan, 'laporan/') === false ? 'laporan/' . $laporan->foto_laporan : $laporan->foto_laporan;
                        @endphp
                        <img src="{{ asset('storage/' . $fotoPath) }}" alt="Foto Laporan">
                    </div>
                @endif
            </div>
        </div>
        @endif
        
        <div class="footer">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y \p\u\k\u\l H:i:s') }}</p>
            <p>Laporan ID: #{{ $laporan->id }}</p>
        </div>
    </div>
</body>
</html>
