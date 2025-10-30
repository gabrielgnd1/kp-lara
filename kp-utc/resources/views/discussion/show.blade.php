@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="javascript:history.back()" class="inline-flex items-center text-blue-500 hover:text-blue-600 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali
    </a>

    <!-- Header Info -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $laporan->nama_laporan }}</h2>
        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-600">Prioritas</p>
                <p class="font-semibold">
                    <span class="px-2 py-1 rounded text-xs font-bold
                        @if($laporan->prioritas == 'Tinggi') bg-red-100 text-red-800
                        @elseif($laporan->prioritas == 'Sedang') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800
                        @endif
                    ">
                        {{ $laporan->prioritas }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <p class="font-semibold">
                    <span class="px-2 py-1 rounded text-xs font-semibold
                        @if($laporan->decision == 'Selesai') bg-green-100 text-green-800
                        @elseif($laporan->decision == 'Diproses') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif
                    ">
                        {{ $laporan->decision ?? 'Belum Diproses' }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tipe</p>
                <p class="font-semibold">{{ $laporan->tipe_laporan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Lapor</p>
                <p class="font-semibold">{{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Discussion Section -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Discussion Title -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 text-white">
            <h3 class="text-xl font-bold">💬 Diskusi</h3>
            <p class="text-blue-100 text-sm">Jumlah Diskusi: {{ count($discussions) }}</p>
        </div>

        <!-- Messages Container -->
        <div class="p-6 space-y-4 bg-gray-50 max-h-96 overflow-y-auto">
            @forelse($discussions as $item)
                <div class="flex gap-4">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <!-- Message -->
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-800">{{ $item->user->name }}</span>
                            <span class="text-xs text-gray-400">
                                {{ $item->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <p class="text-gray-700 text-sm">{{ $item->diskusi }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">Belum ada diskusi. Mulai percakapan sekarang!</p>
                </div>
            @endforelse
        </div>

        <!-- Input Area -->
        <div class="border-t border-gray-200 bg-white px-6 py-4">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-3 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-3 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('diskusi.store') }}" class="flex gap-2">
                @csrf
                <input type="hidden" name="laporan_id" value="{{ $laporan->id }}">
                <input 
                    type="text"
                    name="diskusi"
                    placeholder="Ketik diskusi Anda..."
                    class="flex-1 bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors text-sm"
                />
                <button 
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors flex items-center gap-2 text-sm"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Kirim
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
