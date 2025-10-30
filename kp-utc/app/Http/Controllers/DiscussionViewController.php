<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\DiskusiLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionViewController extends Controller
{
    public function show($laporanId)
    {
        $laporan = Laporan::findOrFail($laporanId);
        $discussions = $laporan->diskusiLaporan()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('discussion.show', compact('laporan', 'discussions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'laporan_id' => 'required|exists:laporan,id',
            'diskusi' => 'required|string|min:1'
        ]);

        try {
            DiskusiLaporan::create([
                'user_id' => Auth::id(),
                'laporan_id' => $request->laporan_id,
                'diskusi' => $request->diskusi
            ]);

            return redirect()->back()->with('success', 'Diskusi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan diskusi: ' . $e->getMessage());
        }
    }
}
