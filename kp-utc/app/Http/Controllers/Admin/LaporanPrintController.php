<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanPrintController extends Controller
{
    public function print($id)
    {
        $laporan = Laporan::with(['user', 'area'])->findOrFail($id);
        return view('admin.laporan.print', compact('laporan'));
    }
}
