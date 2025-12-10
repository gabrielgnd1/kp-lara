@php
    $pemesananAdditional = $record->pemesananAdditional ?? [];
@endphp

@if($pemesananAdditional->isEmpty())
    <div style="padding: 1rem; color: #6b7280; font-size: 0.875rem;">
        Tidak ada additional yang dipesan
    </div>
@else
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @foreach($pemesananAdditional as $item)
            <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; background-color: #f9fafb;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <p style="font-weight: 600; color: #111827; margin: 0;">{{ $item->additional->nama ?? '-' }}</p>
                    <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Qty: {{ $item->jumlah ?? 1 }}</p>
                </div>
                @if($item->additional->keterangan)
                    <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">
                        {{ $item->additional->keterangan }}
                    </p>
                @endif
                @if($item->additional->harga)
                    <p style="color: #059669; font-weight: 600; font-size: 0.875rem; margin: 0.5rem 0 0 0;">
                        Rp{{ number_format($item->additional->harga, 0, ',', '.') }}
                    </p>
                @endif
            </div>
        @endforeach
    </div>
@endif
