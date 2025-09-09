<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;

class ReservasiController extends Controller
{
    public function edit($id)
    {
        $reservasi = Reservasi::with(['fasilitas'])->findOrFail($id);

        // subtotal dari fasilitas
        $subtotal = 0.0;
        foreach ($reservasi->fasilitas as $f) {
            $qty   = (float) ($f->pivot->jumlah ?? 1);
            $harga = (float) ($f->harga ?? 0);
            $subtotal += $qty * $harga;
        }

        $diskonPersen = (float) ($reservasi->diskon ?? 0);
        $reservasi->harga_akhir = max(0, $subtotal - ($subtotal * ($diskonPersen / 100)));

        return view('reservasi.edit', compact('reservasi', 'subtotal'));
    }

    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::with(['fasilitas'])->findOrFail($id);

        $data = $request->validate([
            // pilih salah satu:
            'diskon_persen' => ['required','numeric','min:0','max:100'],
            // 'diskon' => ['required','numeric','min:0'],
        ]);

        // hitung subtotal dari DB
        $subtotal = 0;
        foreach ($reservasi->fasilitas as $f) {
            $subtotal += (float) $f->harga * (int) ($f->pivot->jumlah ?? 1);
        }

        $validated = $request->validate([
            'diskon' => ['required','numeric','min:0','max:100'],
        ]);
        $diskonPersen = (float) $validated['diskon'];
        $data['harga_akhir'] = max(0, $subtotal - ($subtotal * ($diskonPersen / 100)));

        $reservasi->update($data);

        return redirect()->route('reservasi.show', $reservasi)->with('success', 'Reservasi diperbarui.');
    }

}
