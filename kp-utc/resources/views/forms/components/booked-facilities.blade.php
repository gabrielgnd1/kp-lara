@php
    // Get the current record from the Livewire component context
    // In Filament EditRecord/ViewRecord pages, use $this->record
    $record = $this->record ?? null;
    $pemesananFasilitas = $record?->pemesananFasilitas ?? [];
@endphp

<div>
    @if(empty($pemesananFasilitas) || $pemesananFasilitas->isEmpty())
        <div style="padding: 1rem; color: #6b7280; font-size: 0.875rem; text-align: center;">
            Tidak ada fasilitas yang dipesan
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($pemesananFasilitas as $item)
                <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; background-color: #f9fafb;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <p style="font-weight: 600; color: #111827; margin: 0;">{{ $item->fasilitas->nama ?? '-' }}</p>
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Qty: {{ $item->jumlah ?? 1 }}</p>
                    </div>
                    <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">
                        Kapasitas: {{ $item->fasilitas->kapasitas ?? '-' }} orang
                    </p>
                    @if($item->fasilitas->keterangan)
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0.5rem 0 0 0;">
                            {{ $item->fasilitas->keterangan }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
