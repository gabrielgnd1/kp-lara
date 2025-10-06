<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Reservasi</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url("{{ storage_path('fonts/DejaVuSans.ttf') }}") format("truetype");
        }
        
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }
        
        body {
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }
        
        .header .subtitle {
            color: #666;
            margin-top: 5px;
        }
        
        .reservasi-item {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            page-break-inside: avoid;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .label, .value {
            display: table-cell;
            padding: 4px 8px;
            vertical-align: top;
        }
        
        .label {
            font-weight: bold;
            color: #4a5568;
            width: 35%;
        }
        
        .value {
            color: #2d3748;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .status-baru {
            background: #e5e7eb;
            color: #374151;
        }
        
        .status-acc {
            background: #c6f6d5;
            color: #22543d;
        }
        
        .status-not-acc {
            background: #fed7d7;
            color: #742a2a;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }
        
        body {
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }

        .header .subtitle {
            color: #666;
            margin-top: 5px;
        }

        .reservasi-item {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            page-break-inside: avoid;
        }

        .reservasi-item h2 {
            color: #2c5282;
            font-size: 16px;
            margin: 0 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .label, .value {
            display: table-cell;
            padding: 4px 8px;
        }

        .label {
            font-weight: bold;
            color: #4a5568;
            width: 35%;
        }

        .value {
            color: #2d3748;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-pending {
            background: #fefcbf;
            color: #744210;
        }

        .status-approved {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-rejected {
            background: #fed7d7;
            color: #742a2a;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @font-face {
            font-family: 'DejaVu Sans';
            src: url("{{ storage_path('fonts/DejaVuSans.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }
        
        body {
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }
        .header .subtitle {
            color: #666;
            margin-top: 5px;
        }
        .meta-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f8f8;
            border-radius: 4px;
        }
        .reservasi-item {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            page-break-inside: avoid;
        }
        .reservasi-item h2 {
            color: #2c5282;
            font-size: 16px;
            margin: 0 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .label, .value {
            display: table-cell;
            padding: 4px 8px;
        }
        .label {
            font-weight: bold;
            color: #4a5568;
            width: 35%;
        }
        .value {
            color: #2d3748;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-pending {
            background: #fefcbf;
            color: #744210;
        }
        .status-approved {
            background: #c6f6d5;
            color: #22543d;
        }
        .status-rejected {
            background: #fed7d7;
            color: #742a2a;
        }
        .summary-box {
            background: #ebf8ff;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #bee3f8;
        }
        @page {
            margin: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detail Reservasi</h1>
        <div class="subtitle">Generated on {{ now()->format('d F Y, H:i') }}</div>
    </div>

    @if(isset($reservasi_list))
    <div class="summary-box">
        <strong>Summary:</strong> {{ count($reservasi_list) }} reservations exported
    </div>
    @endif
    
    @if(isset($reservasi_list))
        @foreach($reservasi_list as $reservasi)
            <div class="reservasi-item">
                <h2>Reservasi #{{ $reservasi['id'] }} - {{ $reservasi['judul_kegiatan'] }}</h2>
                
                <div class="info-grid">
                    <div class="info-row">
                        <div class="label">Status Reservasi:</div>
                        <div class="value">
                            <span class="status-badge status-{{ strtolower($reservasi['status_reservasi']) }}">
                                {{ $reservasi['status_reservasi'] }}
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="label">Status Pembayaran:</div>
                        <div class="value">
                            <span class="status-badge status-{{ strtolower($reservasi['status_pembayaran']) }}">
                                {{ $reservasi['status_pembayaran'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-row">
                        <div class="label">Nama Pemesan:</div>
                        <div class="value">{{ $reservasi['nama_pemesan'] }}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">No Telepon:</div>
                        <div class="value">{{ $reservasi['no_telepon'] }}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">Email:</div>
                        <div class="value">{{ $reservasi['email'] }}</div>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-row">
                        <div class="label">Check In:</div>
                        <div class="value">{{ \Carbon\Carbon::parse($reservasi['waktu_check_in'])->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">Check Out:</div>
                        <div class="value">{{ \Carbon\Carbon::parse($reservasi['waktu_check_out'])->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">Durasi:</div>
                        <div class="value">
                            {{ \Carbon\Carbon::parse($reservasi['waktu_check_in'])->diffForHumans(\Carbon\Carbon::parse($reservasi['waktu_check_out']), true) }}
                        </div>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-row">
                        <div class="label">Jumlah Peserta:</div>
                        <div class="value">
                            {{ $reservasi['jumlah_laki'] + $reservasi['jumlah_perempuan'] }} orang
                            (L: {{ $reservasi['jumlah_laki'] }}, P: {{ $reservasi['jumlah_perempuan'] }})
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="label">Tanggal Dibuat:</div>
                        <div class="value">{{ \Carbon\Carbon::parse($reservasi['tanggal_dibuat'])->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="reservasi-item">
            <h2>Reservasi #{{ $reservasi['id'] }} - {{ $reservasi['judul_kegiatan'] }}</h2>
            
            <div class="info-grid">
                <div class="info-row">
                    <div class="label">Status Reservasi:</div>
                    <div class="value">
                        <span class="status-badge status-{{ strtolower($reservasi['status_reservasi']) }}">
                            {{ $reservasi['status_reservasi'] }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="label">Status Pembayaran:</div>
                    <div class="value">
                        <span class="status-badge status-{{ strtolower($reservasi['status_pembayaran']) }}">
                            {{ $reservasi['status_pembayaran'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="label">Nama Pemesan:</div>
                    <div class="value">{{ $reservasi['nama_pemesan'] }}</div>
                </div>
                <div class="info-row">
                    <div class="label">No Telepon:</div>
                    <div class="value">{{ $reservasi['no_telepon'] }}</div>
                </div>
                <div class="info-row">
                    <div class="label">Email:</div>
                    <div class="value">{{ $reservasi['email'] }}</div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="label">Check In:</div>
                    <div class="value">{{ \Carbon\Carbon::parse($reservasi['waktu_check_in'])->format('d M Y, H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="label">Check Out:</div>
                    <div class="value">{{ \Carbon\Carbon::parse($reservasi['waktu_check_out'])->format('d M Y, H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="label">Durasi:</div>
                    <div class="value">
                        {{ \Carbon\Carbon::parse($reservasi['waktu_check_in'])->diffForHumans(\Carbon\Carbon::parse($reservasi['waktu_check_out']), true) }}
                    </div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="label">Jumlah Peserta:</div>
                    <div class="value">
                        {{ $reservasi['jumlah_laki'] + $reservasi['jumlah_perempuan'] }} orang
                        (L: {{ $reservasi['jumlah_laki'] }}, P: {{ $reservasi['jumlah_perempuan'] }})
                    </div>
                </div>
                <div class="info-row">
                    <div class="label">Tanggal Dibuat:</div>
                    <div class="value">{{ \Carbon\Carbon::parse($reservasi['tanggal_dibuat'])->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="footer">
        <p>Generated by {{ config('app.name') }} on {{ now()->format('d F Y, H:i') }}</p>
        <p>Page 1</p>
    </div>
</body>
</html>
