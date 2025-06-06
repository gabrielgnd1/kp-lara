<div class="space-y-4">
    @foreach ($records as $laporan)
        <div class="bg-white rounded-xl shadow p-4 border">
            <div class="flex flex-col items-center text-center gap-2">
                <img
                    src="{{ asset('storage/' . $laporan->foto_laporan) }}"
                    alt="Foto Laporan"
                    class="w-full max-w-xs aspect-square object-cover rounded-md"
                >
                <div class="font-bold text-lg">{{ $laporan->nama_laporan }}</div>
                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->translatedFormat('d F Y') }}</div>
                <a href="{{ route('filament.lapangan.resources.laporan-resource.edit', $laporan->id) }}"
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
