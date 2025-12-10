@php
    // Get pricing data for this facility
    $hargaHarian = $fasilitas->harga_harian ?? 0;
    $hargaMenum = $fasilitas->harga_menginap ?? 0;
    $hargaMenitMenginap = $fasilitas->harga_menginap_menit ?? 0;
    $hargaMenitTidakMenginap = $fasilitas->harga_tidak_menginap_menit ?? 0;
    
    // Determine status color
    $isAvailable = $fasilitas->status === 'Available';
    $statusColor = $isAvailable ? '#10b981' : '#ef4444';
    $statusText = $isAvailable ? '✓ Tersedia' : '✗ Tidak Tersedia';
    $cardHeaderBg = $isAvailable ? '#A8DE30' : '#ef4444';
    $cardHeaderTextColor = $isAvailable ? 'black' : 'white';
@endphp

<div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); overflow: hidden;">
    <!-- Card Header -->
    <div style="background-color: {{ $cardHeaderBg }}; padding: 1rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: {{ $cardHeaderTextColor }}; margin: 0;">{{ $fasilitas->nama }}</h3>
    </div>

    <!-- Card Body -->
    <div style="padding: 1rem; display: flex; flex-direction: column; gap: 1rem;">
        <!-- Description -->
        <div>
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin: 0;">Description</p>
            <p style="font-size: 0.875rem; color: #374151; margin-top: 0.25rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                {{ $fasilitas->keterangan ?: 'No description' }}
            </p>
        </div>

        <!-- Capacity -->
        <div>
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin: 0;">Capacity</p>
            <p style="font-size: 0.875rem; color: #111827; font-weight: 500; margin-top: 0.25rem;">{{ $fasilitas->kapasitas }} persons</p>
        </div>

        <!-- Price Section - Only show if available -->
        @if ($isAvailable)
            <div style="border-top: 1px solid #e5e7eb; padding-top: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin: 0;">Price</p>
                
                <!-- Weekday -->
                <div style="background-color: #eff6ff; border-radius: 0.25rem; padding: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <p style="font-weight: 600; color: #111827; font-size: 0.875rem; margin: 0;">Weekday</p>
                    <div style="font-size: 0.875rem; color: #374151; display: flex; flex-direction: column; gap: 0.25rem;">
                        <p style="display: flex; justify-content: space-between; margin: 0;">
                            <span>• Menginap</span>
                            <span style="font-weight: 500;">Rp{{ number_format($hargaMenitMenginap, 0, ',', '.') }}</span>
                        </p>
                        <p style="display: flex; justify-content: space-between; margin: 0;">
                            <span>• Tidak Menginap</span>
                            <span style="font-weight: 500;">Rp{{ number_format($hargaMenitTidakMenginap, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Weekend -->
                <div style="background-color: #fff7ed; border-radius: 0.25rem; padding: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <p style="font-weight: 600; color: #111827; font-size: 0.875rem; margin: 0;">Weekend</p>
                    <div style="font-size: 0.875rem; color: #374151; display: flex; flex-direction: column; gap: 0.25rem;">
                        <p style="display: flex; justify-content: space-between; margin: 0;">
                            <span>• Menginap</span>
                            <span style="font-weight: 500;">Rp{{ number_format($hargaMenitMenginap, 0, ',', '.') }}</span>
                        </p>
                        <p style="display: flex; justify-content: space-between; margin: 0;">
                            <span>• Tidak Menginap</span>
                            <span style="font-weight: 500;">Rp{{ number_format($hargaMenitTidakMenginap, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Card Footer - Status Only -->
    <div style="background-color: #f3f4f6; padding: 0.75rem 1rem; border-top: 1px solid #e5e7eb; text-align: center;">
        @if ($fasilitas->is_available_for_period ?? $fasilitas->status === 'Available')
            <span style="color: #10b981; font-weight: 600; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Tersedia
            </span>
        @else
            <span style="color: #ef4444; font-weight: 600; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                Tidak Tersedia
            </span>
        @endif
    </div>
</div>
