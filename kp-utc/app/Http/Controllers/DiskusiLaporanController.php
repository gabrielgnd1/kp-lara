<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiskusiLaporan;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;

class DiskusiLaporanController extends Controller
{
    /**
     * Get all discussions for a specific laporan
     */
    public function getDiscussions($laporanId)
    {
        try {
            $laporan = Laporan::findOrFail($laporanId);
            
            $discussions = $laporan->diskusiLaporan()
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $discussions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil diskusi: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Store a new discussion
     */
    public function store(Request $request)
    {
        $request->validate([
            'laporan_id' => 'required|exists:laporan,id',
            'diskusi' => 'required|string|min:1'
        ]);

        try {
            $diskusi = DiskusiLaporan::create([
                'user_id' => Auth::id(),
                'laporan_id' => $request->laporan_id,
                'diskusi' => $request->diskusi
            ]);

            $diskusi->load('user');

            return response()->json([
                'status' => 'success',
                'message' => 'Diskusi berhasil ditambahkan',
                'data' => $diskusi
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan diskusi: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Delete a discussion (only owner or admin)
     */
    public function destroy($diskusiId)
    {
        try {
            $diskusi = DiskusiLaporan::findOrFail($diskusiId);

            // Check if user is the owner or admin
            if ($diskusi->user_id !== Auth::id() && Auth::user()->role_id !== 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses untuk menghapus diskusi ini'
                ], 403);
            }

            $diskusi->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Diskusi berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus diskusi: ' . $e->getMessage()
            ], 400);
        }
    }
}
