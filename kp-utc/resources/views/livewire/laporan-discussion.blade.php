<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 text-white flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">{{ $laporan->nama_laporan }}</h2>
                <p class="text-blue-100 text-sm mt-1">Diskusi - Laporan #{{ $laporan->id }}</p>
            </div>
            <button 
                wire:click="$parent.closeModal()"
                class="text-white hover:bg-blue-700 p-2 rounded-full transition-colors"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Discussion Messages Area -->
        <div class="flex-1 overflow-y-auto bg-gray-50 p-6 space-y-4">
            @if(empty($diskusiList))
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Belum ada diskusi. Mulai percakapan!</p>
                </div>
            @else
                @foreach($diskusiList as $item)
                    <div class="flex gap-3 animate-fade-in">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($item['user']['name'], 0, 1)) }}
                            </div>
                        </div>
                        
                        <!-- Message Bubble -->
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-800">{{ $item['user']['name'] }}</span>
                                <span class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                                </span>
                            </div>
                            <div class="mt-1 bg-white rounded-lg p-3 border border-gray-200">
                                <p class="text-gray-700">{{ $item['diskusi'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Input Area -->
        <div class="border-t border-gray-200 bg-white px-6 py-4">
            <form wire:submit="addDiskusi" class="flex gap-2">
                <input 
                    type="text"
                    wire:model="newDiskusi"
                    placeholder="Ketik diskusi Anda di sini..."
                    class="flex-1 bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors"
                />
                <button 
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Kirim
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
