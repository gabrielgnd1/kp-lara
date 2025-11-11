<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;

class ReservasiViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show($id)
    {
        $reservasi = Reservasi::with(['fasilitas', 'additional', 'menuMakan', 'pic_utc', 'pic_ioc'])->findOrFail($id);
        
        return response()
            ->view('reservasi.pdf', ['reservasi' => $reservasi])
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }
}