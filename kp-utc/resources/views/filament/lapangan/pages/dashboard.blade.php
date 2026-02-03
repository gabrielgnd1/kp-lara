<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Maintenance Image Banner -->
        <div class="bg-white rounded-lg shadow-sm p-6 flex justify-center">
            <img src="{{ asset('assets/laporan/maintenan.png') }}" 
                 alt="Maintenance" 
                 class="max-w-full h-auto rounded-lg"
                 style="max-height: 200px; max-width: 600px;"
                 loading="lazy">
        </div>

        <!-- Detail Laporan - Card Grid Layout (same as Detail Laporan page) -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Daftar Laporan</h2>
            </div>
            <div class="p-6 bg-gray-50">
                @php
                    $records = $this->getLaporans();
                @endphp

                @if($records->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($records as $laporan)
                            <div class="bg-white rounded-xl shadow p-4 border border-gray-200 flex flex-col">
                                <div class="flex flex-col items-center text-center gap-2 flex-grow">
                                    @php
                                        // Get first photo from array, fallback to old string format
                                        $fotoUrl = asset('assets/images/placeholder-laporan.png');
                                        if (is_array($laporan->foto_laporan) && count($laporan->foto_laporan) > 0) {
                                            $firstPhoto = $laporan->foto_laporan[0];
                                            if (strpos($firstPhoto, 'laporan/') === false) {
                                                $fotoUrl = asset('storage/laporan/' . $firstPhoto);
                                            } else {
                                                $fotoUrl = asset('storage/' . $firstPhoto);
                                            }
                                        } elseif (is_string($laporan->foto_laporan) && !empty($laporan->foto_laporan)) {
                                            $fotoUrl = asset('storage/laporan/' . $laporan->foto_laporan);
                                        }
                                    @endphp
                                    <img src="{{ $fotoUrl }}"
                                        style="height: 160px; width: 160px; object-fit: cover;"
                                        class="rounded-md shadow mx-auto"
                                        loading="lazy"
                                        alt="{{ $laporan->nama_laporan }}"
                                        decoding="async"
                                    />
                                    <div class="font-bold text-base text-gray-900 line-clamp-2">{{ $laporan->nama_laporan }}</div>
                                    <div class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('d M Y') }}</div>
                                    
                                    <div class="flex gap-1 mt-2 flex-wrap justify-center">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                            @if($laporan->prioritas == 'Tinggi') bg-red-100 text-red-800
                                            @elseif($laporan->prioritas == 'Sedang') bg-yellow-100 text-yellow-800
                                            @elseif($laporan->prioritas == 'Rendah') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $laporan->prioritas }}
                                        </span>
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                            @if($laporan->decision == 'Selesai') bg-green-100 text-green-800
                                            @elseif($laporan->decision == 'Diproses') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $laporan->decision }}
                                        </span>
                                    </div>
                                </div>

                                <a href="{{ route('discussion.show', $laporan->id) }}"
                                   class="mt-3 inline-block px-3 py-1 bg-orange-600 text-white text-xs rounded hover:bg-orange-700 transition-colors">
                                    Detail
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">Belum ada laporan. Klik tombol "Tambah Laporan" untuk membuat laporan baru.</p>
                    </div>
                @endif
            </div>
        </div>

        @if ($this->getWidgets())
            <x-filament-widgets::widgets
                :columns="$this->getColumns()"
                :widgets="$this->getWidgets()"
            />
        @endif
    </div>
</x-filament-panels::page>
