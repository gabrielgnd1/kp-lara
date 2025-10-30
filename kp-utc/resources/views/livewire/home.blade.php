<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Welcome, {{ $user->name }}!</h1>

    <div class="mt-6">
        <a href="{{ route('laporan.list') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
            📋 Lihat Laporan & Diskusi
        </a>
    </div>
</div>
