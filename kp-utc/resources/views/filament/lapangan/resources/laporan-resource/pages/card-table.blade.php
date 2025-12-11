<div class="space-y-4">
    @foreach ($records as $laporan)
        <div class="bg-white rounded-xl shadow p-4 border">
            <div class="flex flex-col items-center text-center gap-2">
                <img src="{{ $laporan->foto_laporan ? asset('storage/' . $laporan->foto_laporan) : asset('assets/images/placeholder-laporan.png') }}"
                    style="height: 180px; width: 180px;"
                    class="object-cover rounded-md shadow mx-auto"
                    onerror="this.src='{{ asset('assets/images/placeholder-laporan.png') }}'"
                />
                <div class="font-bold text-lg">{{ $laporan->nama_laporan }}</div>
                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->translatedFormat('d F Y') }}</div>
                
                <div class="flex gap-2 mt-2">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($laporan->prioritas == 'Tinggi') bg-red-100 text-red-800
                        @elseif($laporan->prioritas == 'Sedang') bg-yellow-100 text-yellow-800
                        @elseif($laporan->prioritas == 'Rendah') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $laporan->prioritas }}
                    </span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($laporan->decision == 'Selesai') bg-green-100 text-green-800
                        @elseif($laporan->decision == 'Diproses') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $laporan->decision }}
                    </span>
                </div>

                <a href="{{ route('discussion.show', $laporan->id) }}"
                   class="mt-2 inline-block px-4 py-1 bg-orange-600 text-white text-sm rounded hover:bg-orange-700">
                    Detail
                </a>
            </div>
        </div>
    @endforeach

    <div class="mt-6">
        {{ $records->links() }}
    </div>
</div>
