<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;

class ReservasiViewController extends Controller
{
    public function show($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $data = $reservasi->toArray();
        
        return response()
            ->view('reservasi.pdf', ['reservasi' => $data])
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }
}