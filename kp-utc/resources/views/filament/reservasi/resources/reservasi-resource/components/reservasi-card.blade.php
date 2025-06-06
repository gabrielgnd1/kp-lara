@php
    $record = $getRecord();
@endphp

<div class="rounded-xl shadow-md p-6 mb-4 w-full border border-gray-200">
    <div class="flex justify-between items-start">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">{{ $record->nama_pemesan }}</h3>
            <p class="text-sm text-gray-500 italic">{{ $record->email }} • {{ $record->no_telepon }}</p>
        </div>
        <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">
            {{ ucfirst(strtolower($record->status_reservasi)) }}
        </span>
    </div>

    <div class="mt-4 text-sm text-gray-700 space-y-1">
        <p><span class="font-medium">Judul Kegiatan:</span> {{ $record->judul_kegiatan }}</p>
        <p><span class="font-medium">Tanggal:</span> {{ \Carbon\Carbon::parse($record->waktu_check_in)->format('D, d M Y H:i') }}
            - {{ \Carbon\Carbon::parse($record->waktu_check_out)->format('D, d M Y H:i') }}</p>
    </div>
</div>
