<style>
    @media print {
        .no-print {
            display: none;
        }
    }
    
    @font-face {
        font-family: 'DejaVu Sans';
        src: url("{{ storage_path('fonts/DejaVuSans.ttf') }}") format("truetype");
    }
    
    :root {
        --color-green: #A8DE30;
        --color-black: #31312C;
        --color-white: #FFFFFF;
        --color-purple: #493852;
    }
    
    * {
        font-family: 'DejaVu Sans', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        background-color: var(--color-white);
        color: var(--color-black);
        line-height: 1.6;
        font-size: 14px;
        min-height: 100vh;
    }
    
    .container {
        max-width: 100%;
        padding: 0.5rem;
        margin: 0 auto;
    }
    
    .no-print-section {
        margin-bottom: 1rem;
        text-align: right;
    }
    
    /* Header with Logo and Title */
    .header-section {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid var(--color-green);
        padding-bottom: 1rem;
        position: relative;
    }
    
    .logo-area {
        position: absolute;
        left: 0;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
    }
    
    .logo-area img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .header-text {
        text-align: center;
        flex-grow: 0;
    }
    
    .header-text h1 {
        font-size: 18px;
        font-weight: bold;
        color: var(--color-black);
        margin: 0;
    }
    
    .header-text p {
        font-size: 12px;
        color: var(--color-purple);
        margin: 0.25rem 0 0 0;
    }
    
    /* Content Sections */
    .form-section {
        margin-bottom: 1.5rem;
        page-break-inside: avoid;
    }
    
    .section-title {
        background: var(--color-green);
        color: var(--color-white);
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.75rem;
        font-size: 14px;
        font-weight: bold;
        border-radius: 0.25rem;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .form-grid.full {
        grid-template-columns: 1fr;
    }
    
    .form-group {
        page-break-inside: avoid;
    }
    
    .form-label {
        display: block;
        font-weight: bold;
        color: var(--color-purple);
        font-size: 13px;
        margin-bottom: 0.25rem;
    }
    
    .form-value {
        display: block;
        color: var(--color-black);
        font-size: 13px;
        padding: 0.5rem;
        background: rgba(168, 222, 48, 0.05);
        border: 1px solid rgba(168, 222, 48, 0.2);
        border-radius: 0.25rem;
        min-height: 1.5rem;
    }
    
    /* Facilities Table */
    .facilities-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0.5rem 0;
        font-size: 12px;
    }
    
    .facilities-table th {
        background: var(--color-purple);
        color: var(--color-white);
        padding: 0.5rem;
        text-align: left;
        font-weight: bold;
        border: 1px solid #ddd;
    }
    
    .facilities-table td {
        padding: 0.5rem;
        border: 1px solid #ddd;
    }
    
    .facilities-table tr:nth-child(even) {
        background: rgba(168, 222, 48, 0.05);
    }
    
    /* Additional & Menu Table */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0.5rem 0;
        font-size: 12px;
    }
    
    .items-table th {
        background: var(--color-purple);
        color: var(--color-white);
        padding: 0.5rem;
        text-align: left;
        font-weight: bold;
        border: 1px solid #ddd;
    }
    
    .items-table td {
        padding: 0.5rem;
        border: 1px solid #ddd;
    }
    
    .items-table tr:nth-child(even) {
        background: rgba(168, 222, 48, 0.05);
    }
    
    /* Status badges */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 12px;
        font-weight: bold;
    }
    
    .status-acc {
        background: #c6f6d5;
        color: #22543d;
    }
    
    .status-not-acc {
        background: #fed7d7;
        color: #742a2a;
    }
    
    /* Signature Section */
    .signature-section {
        margin-top: 1rem;
        page-break-inside: avoid;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    
    .signature-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 12px;
    }
    
    .signature-line {
        width: 100%;
        border-top: 1px solid var(--color-black);
        margin-bottom: 0.25rem;
        margin-top: 2rem;
    }
    
    .signature-label {
        font-weight: bold;
        margin-bottom: 75px;
        text-align: center;
    }
    
    .signature-name {
        text-align: center;
        margin-top: 0.25rem;
    }

    .cost-details-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0.5rem 0;
        font-size: 12px;
    }

    .cost-details-table th {
        background: var(--color-purple);
        color: var(--color-white);
        padding: 0.5rem;
        text-align: left;
        font-weight: bold;
        border: 1px solid #ddd;
    }

    .cost-details-table td {
        padding: 0.5rem;
        border: 1px solid #ddd;
    }

    .cost-details-table td:last-child {
        text-align: right;
        width: 150px;
    }

    .cost-details-table tr:nth-child(even) {
        background: rgba(168, 222, 48, 0.05);
    }

    .cost-details-total {
        font-weight: bold;
        background: rgba(168, 222, 48, 0.15) !important;
        border-top: 2px solid var(--color-black);
        border-bottom: 2px solid var(--color-black);
    }

    .cost-details-total td {
        border: 1px solid #ddd;
    }

    .cost-details-subtotal {
        font-weight: bold;
        background: rgba(168, 222, 48, 0.1);
        border-top: 1px solid var(--color-black);
    }

    .cost-details-category {
        background: rgba(73, 56, 82, 0.1);
        font-weight: bold;
        color: var(--color-purple);
        text-align: center;
    }

    /* Payment Information Section */
    .payment-info {
        margin: 1.5rem 0;
        padding: 1rem;
        background: rgba(168, 222, 48, 0.05);
        border-left: 3px solid var(--color-green);
        font-size: 11px;
        line-height: 1.6;
    }

    .payment-info-title {
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: var(--color-purple);
    }

    .payment-info p {
        margin: 0.25rem 0;
    }

    .payment-info ol {
        margin: 0.5rem 0 0.5rem 1.5rem;
        padding: 0;
    }

    .payment-info ol li {
        margin-bottom: 0.5rem;
    }

    /* Date Location Section */
    .date-location {
        text-align: right;
        margin-bottom: 1px;
        font-size: 12px;
        font-weight: bold;
    }
    
    @page {
        margin: 20px;
        size: A4;
    }
</style>

<!-- Header with Logo -->
<div class="header-section">
    <div class="logo-area">
        <img src="{{ asset('images/ioc_utc_upc.png') }}" alt="UTC Logo">
    </div>
    <div class="header-text">
        <h1>RESERVASI FORM</h1>
        <p>UBAYA TRAINING CENTER - TRAWAS</p>
    </div>
</div>

<!-- Personal Information -->
<div class="form-section">
    <div class="section-title">DATA PEMESAN</div>
    <div class="form-grid full">
        <div class="form-group">
            <label class="form-label">Kode Reservasi</label>
            <div class="form-value">{{ $reservasi->kode_reservasi ?? '-' }}</div>
        </div>
    </div>
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Nama Pemesan</label>
            <div class="form-value">{{ $reservasi->nama_pemesan ?? '-' }}</div>
        </div>
        <div class="form-group">
            <label class="form-label">Nomor Telepon</label>
            <div class="form-value">{{ $reservasi->no_telepon ?? '-' }}</div>
        </div>
    </div>
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Email</label>
            <div class="form-value">{{ $reservasi->email ?? '-' }}</div>
        </div>
        <div class="form-group">
            <label class="form-label">Judul Kegiatan</label>
            <div class="form-value">{{ $reservasi->judul_kegiatan ?? '-' }}</div>
        </div>
    </div>
</div>

<!-- Reservation Details -->
<div class="form-section">
    <div class="section-title">DETAIL RESERVASI</div>
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Tanggal Check In</label>
            <div class="form-value">{{ isset($reservasi->waktu_check_in) ? $reservasi->waktu_check_in->format('d-m-Y') : '-' }}</div>
        </div>
        <div class="form-group">
            <label class="form-label">Jam Check In</label>
            <div class="form-value">{{ isset($reservasi->waktu_check_in) ? $reservasi->waktu_check_in->format('H:i') : '-' }}</div>
        </div>
    </div>
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Tanggal Check Out</label>
            <div class="form-value">{{ isset($reservasi->waktu_check_out) ? $reservasi->waktu_check_out->format('d-m-Y') : '-' }}</div>
        </div>
        <div class="form-group">
            <label class="form-label">Jam Check Out</label>
            <div class="form-value">{{ isset($reservasi->waktu_check_out) ? $reservasi->waktu_check_out->format('H:i') : '-' }}</div>
        </div>
    </div>
</div>

<!-- Participant Count -->
<div class="form-section">
    <div class="section-title">DATA PESERTA</div>
    <div class="form-grid full">
        <div class="form-group">
            <label class="form-label">Total Jumlah Peserta</label>
            <div class="form-value">{{ ($reservasi->jumlah_laki ?? 0) + ($reservasi->jumlah_perempuan ?? 0) }} orang</div>
        </div>
    </div>
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Jumlah Peserta Laki-Laki</label>
            <div class="form-value">{{ $reservasi->jumlah_laki ?? 0 }} orang</div>
        </div>
        <div class="form-group">
            <label class="form-label">Jumlah Peserta Perempuan</label>
            <div class="form-value">{{ $reservasi->jumlah_perempuan ?? 0 }} orang</div>
        </div>
    </div>
</div>

<!-- Facilities (if available from relationship) -->
@if(isset($reservasi->fasilitas) && is_object($reservasi->fasilitas) && $reservasi->fasilitas->isNotEmpty())
<div class="form-section">
    <div class="section-title">FASILITAS YANG DIPESAN</div>
    <table class="facilities-table">
        <thead>
            <tr>
                <th>Nama Fasilitas</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Jumlah Orang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservasi->fasilitas as $fasilitas)
            <tr>
                <td>{{ $fasilitas->nama ?? '-' }}</td>
                <td>{{ isset($fasilitas->pivot->mulai) ? \Carbon\Carbon::parse($fasilitas->pivot->mulai)->format('d-m-Y') : '-' }}</td>
                <td>{{ isset($fasilitas->pivot->selesai) ? \Carbon\Carbon::parse($fasilitas->pivot->selesai)->format('d-m-Y') : '-' }}</td>
                <td>{{ $fasilitas->pivot->jumlah_orang ?? "-" }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Additional Services (if available) -->
@if(isset($reservasi->additional) && is_object($reservasi->additional) && $reservasi->additional->isNotEmpty())
<div class="form-section">
    <div class="section-title">LAYANAN TAMBAHAN</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Nama Layanan</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservasi->additional as $additional)
            <tr>
                <td>{{ $additional->nama ?? '-' }}</td>
                <td>{{ isset($additional->pivot->mulai) ? \Carbon\Carbon::parse($additional->pivot->mulai)->format('d-m-Y') : '-' }}</td>
                <td>{{ isset($additional->pivot->selesai) ? \Carbon\Carbon::parse($additional->pivot->selesai)->format('d-m-Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Additional Information -->
@if(!empty($reservasi->informasi_tambahan))
<div class="form-section">
    <div class="section-title">INFORMASI TAMBAHAN</div>
    <div class="form-value form-grid full">{{ $reservasi->informasi_tambahan }}</div>
</div>
@endif

<!-- Cost Details Section -->
@if(
    (isset($reservasi->fasilitas) && is_object($reservasi->fasilitas) && $reservasi->fasilitas->isNotEmpty()) ||
    (isset($reservasi->additional) && is_object($reservasi->additional) && $reservasi->additional->isNotEmpty()) ||
    (isset($reservasi->menuMakan) && is_object($reservasi->menuMakan) && $reservasi->menuMakan->isNotEmpty())
)
<div class="form-section">
    <div class="section-title">DETAIL HARGA</div>
    <table class="cost-details-table">
        <thead>
            <tr>
                <th>Item</th>
                <th style="text-align: center;">Jumlah</th>
                <th style="text-align: right;">Harga Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalCost = 0;
                $subtotalFasilitas = 0;
                $subtotalLayanan = 0;
                $subtotalMenu = 0;
            @endphp
            
            {{-- Display Facilities --}}
            @if(isset($reservasi->fasilitas) && is_object($reservasi->fasilitas) && $reservasi->fasilitas->isNotEmpty())
                <tr class="cost-details-category">
                    <td colspan="3" style="text-align: center;">FASILITAS</td>
                </tr>
                @foreach($reservasi->fasilitas as $fasilitas)
                    @php
                        // Calculate weekday and weekend split
                        $weekday = 0;
                        $weekend = 0;
                        if (isset($fasilitas->pivot->mulai) && isset($fasilitas->pivot->selesai)) {
                            $mulai = \Carbon\Carbon::parse($fasilitas->pivot->mulai)->startOfDay();
                            $selesai = \Carbon\Carbon::parse($fasilitas->pivot->selesai)->startOfDay();
                            if ($selesai->lessThanOrEqualTo($mulai)) {
                                ($mulai->isWeekend()) ? $weekend++ : $weekday++;
                            } else {
                                foreach (\Carbon\CarbonPeriod::create($mulai, $selesai->copy()->subDay()) as $d) {
                                    $d->isWeekend() ? $weekend++ : $weekday++;
                                }
                            }
                        }
                        $jumlahMalam = $weekday + $weekend;
                        $jumlahOrang = $fasilitas->pivot->jumlah_orang ?? 1;
                        
                        // Get harga_weekday and harga_weekend from database
                        // These should be populated from fasilitasGroupedByNamaForMember
                        $hargaWeekday = $fasilitas->harga_weekday ?? $fasilitas->harga ?? 0;
                        $hargaWeekend = $fasilitas->harga_weekend ?? $fasilitas->harga ?? 0;
                        
                        // Calculate subtotal same as hitungTotalHarga
                        $base = ($weekday * $hargaWeekday) + ($weekend * $hargaWeekend);
                        $subtotal = $jumlahOrang * $base;
                        
                        $subtotalFasilitas += $subtotal;
                        $totalCost += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $fasilitas->nama ?? '-' }}{{ $jumlahOrang > 1 ? ' (' . $jumlahOrang . ' orang)' : '' }}</td>
                        <td style="text-align: center;">{{ $jumlahMalam }} malam</td>
                        <td style="text-align: right;">{{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="cost-details-subtotal">
                    <td colspan="2" style="text-align: right;">Subtotal Fasilitas</td>
                    <td style="text-align: right;">{{ number_format($subtotalFasilitas, 0, ',', '.') }}</td>
                </tr>
            @endif
            
            {{-- Display Additional Services --}}
            @if(isset($reservasi->additional) && is_object($reservasi->additional) && $reservasi->additional->isNotEmpty())
                <tr class="cost-details-category">
                    <td colspan="3" style="text-align: center;">LAYANAN TAMBAHAN</td>
                </tr>
                @foreach($reservasi->additional as $additional)
                    @php
                        // Calculate weekday and weekend split for additional services
                        $weekdayAdd = 0;
                        $weekendAdd = 0;
                        if (isset($additional->pivot->mulai) && isset($additional->pivot->selesai)) {
                            $mulaiAdd = \Carbon\Carbon::parse($additional->pivot->mulai)->startOfDay();
                            $selesaiAdd = \Carbon\Carbon::parse($additional->pivot->selesai)->startOfDay();
                            if ($selesaiAdd->lessThanOrEqualTo($mulaiAdd)) {
                                ($mulaiAdd->isWeekend()) ? $weekendAdd++ : $weekdayAdd++;
                            } else {
                                foreach (\Carbon\CarbonPeriod::create($mulaiAdd, $selesaiAdd->copy()->subDay()) as $d) {
                                    $d->isWeekend() ? $weekendAdd++ : $weekdayAdd++;
                                }
                            }
                        }
                        $jumlahHariAdd = $weekdayAdd + $weekendAdd;
                        $hargaAdd = $additional->harga ?? 0;
                        $subtotalAdd = $jumlahHariAdd * $hargaAdd;
                        $subtotalLayanan += $subtotalAdd;
                        $totalCost += $subtotalAdd;
                    @endphp
                    <tr>
                        <td>{{ $additional->nama ?? '-' }}</td>
                        <td style="text-align: center;">{{ $jumlahHariAdd }} malam</td>
                        <td style="text-align: right;">{{ number_format($subtotalAdd, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="cost-details-subtotal">
                    <td colspan="2" style="text-align: right;">Subtotal Layanan</td>
                    <td style="text-align: right;">{{ number_format($subtotalLayanan, 0, ',', '.') }}</td>
                </tr>
            @endif
            
            {{-- Display Menu Makan --}}
            @if(isset($reservasi->menuMakan) && is_object($reservasi->menuMakan) && $reservasi->menuMakan->isNotEmpty())
                <tr class="cost-details-category">
                    <td colspan="3" style="text-align: center;">MENU MAKAN</td>
                </tr>
                @foreach($reservasi->menuMakan as $menu)
                    @php
                        $hargaMenu = $menu->harga ?? 0;
                        $jumlahMenu = $menu->pivot->jumlah ?? 1;
                        $subtotalMenu_item = $jumlahMenu * $hargaMenu;
                        $subtotalMenu += $subtotalMenu_item;
                        $totalCost += $subtotalMenu_item;
                    @endphp
                    <tr>
                        <td>{{ $menu->nama ?? '-' }}</td>
                        <td style="text-align: center;">{{ $jumlahMenu }} porsi</td>
                        <td style="text-align: right;">{{ number_format($subtotalMenu_item, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="cost-details-subtotal">
                    <td colspan="2" style="text-align: right;">Subtotal Menu</td>
                    <td style="text-align: right;">{{ number_format($subtotalMenu, 0, ',', '.') }}</td>
                </tr>
            @endif
            
            <tr class="cost-details-total">
                <td colspan="2" style="text-align: right;">SUBTOTAL</td>
                <td style="text-align: right;">{{ number_format($totalCost, 0, ',', '.') }}</td>
            </tr>
            
            @php
                $diskonPersentase = $reservasi->diskon ?? 0;
                $harkaAkhirFromDB = $reservasi->harga_akhir ?? 0;
            @endphp
            
            @if($diskonPersentase > 0)
            <tr>
                <td colspan="2" style="text-align: right; color: var(--color-purple); font-weight: bold;">Diskon ({{ number_format($diskonPersentase, 2, '.', '.') }}%)</td>
                <td style="text-align: right; color: var(--color-purple); font-weight: bold;">{{ number_format($totalCost - $harkaAkhirFromDB, 0, ',', '.') }}</td>
            </tr>
            @endif
            
            <tr class="cost-details-total">
                <td colspan="2" style="text-align: right;">TOTAL</td>
                <td style="text-align: right;">{{ number_format($harkaAkhirFromDB, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endif

<!-- Status -->
<div class="form-section">
    <div class="section-title">STATUS</div>
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Status Pembayaran</label>
            <div class="form-value">
                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $reservasi->status_pembayaran ?? '')) }}">
                    {{ $reservasi->status_pembayaran ?? '-' }}
                </span>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Tanggal Pembuatan</label>
            <div class="form-value">{{ isset($reservasi->tanggal_dibuat) ? $reservasi->tanggal_dibuat->format('d-m-Y H:i') : '-' }}</div>
        </div>
    </div>
</div>

<!-- Cost Details Section -->
@if(
    (isset($reservasi->fasilitas) && is_object($reservasi->fasilitas) && $reservasi->fasilitas->isNotEmpty()) ||
    (isset($reservasi->additional) && is_object($reservasi->additional) && $reservasi->additional->isNotEmpty()) ||
    (isset($reservasi->menuMakan) && is_object($reservasi->menuMakan) && $reservasi->menuMakan->isNotEmpty())
)
<div class="form-section">
    <div class="section-title">DETAIL BIAYA</div>
</div>
@endif

<!-- Payment Information -->
<div class="payment-info">
    <div class="payment-info-title">INFORMASI PEMBAYARAN</div>
    <p>Sebagai tanda pemesanan tempat dapat diberikan Uang Muka sebesar 30% dari perkiraan jumlah pembayaran, dari sisa pembayaran dilakukan 2 hari sebelum waktu Check In. Pembayaran dapat dilakukan melalui:</p>
    
    <ol>
        <li>
            <strong>Kantor IOC (Integrated Outdoor Campus)</strong><br>
            Jl. Ngagel Jaya Selatan No. 169, Surabaya (Lantai 2 - IOC)<br>
            Telp. 031-2981024<br>
            A/N Yayasan Universitas Surabaya
        </li>
        <li>
            <strong>Transfer Bank Account:</strong><br>
            BCA Cab. KCP Manyar<br>
            A/C 130 218 9221
        </li>
    </ol>
    
    <p style="margin-top: 0.75rem;">Harga di atas bisa berubah / mengalami kenaikan sewaktu-waktu tanpa ada pemberitahuan terlebih dahulu. Demikian konfirmasi pemesanan ini kami buat, terima kasih atas kepercayaan dan kerjasamanya, dan kami menunggu konfirmasi dari Bapak/Ibu terkait hal ini.</p>
</div>

<!-- Date Location -->
<div class="date-location">
    Surabaya, {{ now()->translatedFormat('d F Y') }}
</div>

<!-- Signature Section -->
<div class="signature-section">
    <div class="signature-box">
        <div class="signature-label">Reservasi Diterima Oleh</div>  
        <div class="signature-line"></div>
        <div class="signature-name">
            {{ strtoupper(($reservasi->pic_utc->name ?? $reservasi->pic_ioc->name ?? '( _____________________ )')) }}
        </div>
    </div>
    <div class="signature-box">
        <div class="signature-label">Pengguna / Calon Pengguna</div>
        <div class="signature-line"></div>
        <div class="signature-name">
            {{ strtoupper($reservasi->nama_pemesan ?? '( _____________________ )') }}
        </div>
    </div>
</div>