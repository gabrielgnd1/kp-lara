<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Maintenance Image Banner -->
        <div class="bg-white rounded-lg shadow-sm p-6 flex justify-center">
            <img src="{{ asset('assets/laporan/maintenan.png') }}" 
                 alt="Maintenance" 
                 class="max-w-full h-auto rounded-lg"
                 style="max-height: 400px;">
        </div>

        <!-- Detail Laporan - Card Grid Layout (same as Detail Laporan page) -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Detail Laporan</h2>
            </div>
            <div class="p-6 bg-gray-50">
                @php
                    $records = $this->getLaporans();
                @endphp

                @if($records->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach ($records as $laporan)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border border-gray-200 dark:border-gray-700">
                                <div class="flex flex-col items-center text-center gap-2">
                                    <img src="{{ $laporan->foto_laporan ? asset('storage/' . $laporan->foto_laporan) : asset('assets/images/placeholder-laporan.png') }}"
                                        style="height: 180px; width: 180px;"
                                        class="object-cover rounded-md shadow mx-auto"
                                        onerror="this.src='{{ asset('assets/images/placeholder-laporan.png') }}'"
                                    />
                                    <div class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ $laporan->nama_laporan }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->translatedFormat('d F Y') }}</div>
                                    
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
