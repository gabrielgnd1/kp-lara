<div class="p-6 max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-gray-900">Daftar Laporan</h1>

    @if(empty($laporanList))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
            <p class="text-blue-700">Tidak ada laporan yang tersedia</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($laporanList as $laporan)
                <div 
                    wire:click="selectLaporan({{ $laporan['id'] }})"
                    class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer p-4 border-l-4 border-blue-500"
                >
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $laporan['nama_laporan'] }}</h3>
                    
                    <div class="space-y-2 text-sm text-gray-600">
                        <p>
                            <span class="font-semibold">Tanggal:</span> 
                            {{ \Carbon\Carbon::parse($laporan['tanggal_lapor'])->format('d M Y') }}
                        </p>
                        <p>
                            <span class="font-semibold">Tipe:</span> 
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                {{ $laporan['tipe_laporan'] }}
                            </span>
                        </p>
                        <p>
                            <span class="font-semibold">Prioritas:</span>
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                @if($laporan['prioritas'] == 'Tinggi') bg-red-100 text-red-800
                                @elseif($laporan['prioritas'] == 'Sedang') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif
                            ">
                                {{ $laporan['prioritas'] }}
                            </span>
                        </p>
                        <p>
                            <span class="font-semibold">Status:</span>
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                @if($laporan['decision'] == 'Selesai') bg-green-100 text-green-800
                                @elseif($laporan['decision'] == 'Diproses') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif
                            ">
                                {{ $laporan['decision'] ?? 'Belum Diproses' }}
                            </span>
                        </p>
                    </div>

                    <button 
                        wire:click="selectLaporan({{ $laporan['id'] }})"
                        class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition-colors"
                    >
                        💬 Lihat Diskusi
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Discussion Modal -->
    @if($showDiscussionModal && $selectedLaporan)
        <livewire:laporan-discussion :laporanId="$selectedLaporan" :key="'discussion-' . $selectedLaporan" />
    @endif
</div>
