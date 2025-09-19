<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReservasiController extends Controller
{
    public function edit($id)
    {
        $reservasi = Reservasi::with(['fasilitas', 'additional', 'menuMakan'])->findOrFail($id);

        $subtotal = 0.0;

        // ----- Fasilitas: harga per HARI, hitung hanya hari yang match 'day' item -----
        foreach ($reservasi->fasilitas as $f) {
            $mulai   = $f->pivot->mulai ?? null;
            $selesai = $f->pivot->selesai ?? null;
            $split   = $this->splitWeekdayWeekend($mulai, $selesai);

            $matchDays = 0;
            $day = strtolower((string) $f->day);
            if ($day === 'weekday') {
                $matchDays = $split['weekday'];
            } elseif ($day === 'weekend') {
                $matchDays = $split['weekend'];
            } else {
                // kalau ada nilai lain, abaikan/hitung sesuai kebutuhan
                $matchDays = 0;
            }

            $harga = (float) ($f->harga ?? 0);
            $subtotal += $matchDays * $harga;
        }

        // ----- Additional: asumsi per HARI -----
        foreach ($reservasi->additional as $a) {
            $mulai   = $a->pivot->mulai ?? null;
            $selesai = $a->pivot->selesai ?? null;
            $split   = $this->splitWeekdayWeekend($mulai, $selesai);
            $hariTotal = max(0, $split['weekday'] + $split['weekend']);
            $subtotal += (float) ($a->harga ?? 0) * max(1, $hariTotal); // jika bukan per-hari, pakai min(1, $hariTotal)
        }

        // ----- Menu Makan: jumlah × harga × hari_reservasi (global) -----
        $cin  = $reservasi->waktu_check_in  ?? null;
        $cout = $reservasi->waktu_check_out ?? null;
        $splitGlobal = $this->splitWeekdayWeekend($cin, $cout);
        $hariReservasi = max(1, $splitGlobal['weekday'] + $splitGlobal['weekend']);

        foreach ($reservasi->menuMakan as $m) {
            $qty   = max(1, (int) ($m->pivot->jumlah ?? 1));
            $harga = (float) ($m->harga ?? 0);
            $subtotal += $harga * $qty * $hariReservasi;
        }

        // Diskon
        $diskonPersen = (float) ($reservasi->diskon ?? 0);
        $reservasi->harga_akhir = max(0, $subtotal - ($subtotal * ($diskonPersen / 100)));

        return view('reservasi.edit', compact('reservasi', 'subtotal'));
    }

    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::with(['fasilitas', 'additional', 'menuMakan'])->findOrFail($id);

        $data = $request->validate([
            'diskon' => ['required','numeric','min:0','max:100'],
        ]);

        // ---- Hitung ulang subtotal sama seperti di edit() ----
        $subtotal = 0.0;

        foreach ($reservasi->fasilitas as $f) {
            $mulai   = $f->pivot->mulai ?? null;
            $selesai = $f->pivot->selesai ?? null;
            $split   = $this->splitWeekdayWeekend($mulai, $selesai);

            $matchDays = 0;
            $day = strtolower((string) $f->day);
            if ($day === 'weekday')      $matchDays = $split['weekday'];
            elseif ($day === 'weekend')  $matchDays = $split['weekend'];

            $subtotal += (float) ($f->harga ?? 0) * $matchDays;
        }

        foreach ($reservasi->additional as $a) {
            $mulai   = $a->pivot->mulai ?? null;
            $selesai = $a->pivot->selesai ?? null;
            $split   = $this->splitWeekdayWeekend($mulai, $selesai);
            $hariTotal = max(0, $split['weekday'] + $split['weekend']);
            $subtotal += (float) ($a->harga ?? 0) * max(1, $hariTotal); // jika tidak per-hari: min(1, $hariTotal)
        }

        $cin  = $reservasi->waktu_check_in  ?? null;
        $cout = $reservasi->waktu_check_out ?? null;
        $splitGlobal = $this->splitWeekdayWeekend($cin, $cout);
        $hariReservasi = max(1, $splitGlobal['weekday'] + $splitGlobal['weekend']);

        foreach ($reservasi->menuMakan as $m) {
            $qty   = max(1, (int) ($m->pivot->jumlah ?? 1));
            $subtotal += (float) ($m->harga ?? 0) * $qty * $hariReservasi;
        }

        // Diskon & update
        $diskonPersen = (float) $data['diskon'];
        $data['harga_akhir'] = max(0, $subtotal - ($subtotal * ($diskonPersen / 100)));

        $reservasi->update($data);

        return redirect()->route('reservasi.show', $reservasi)->with('success', 'Reservasi diperbarui.');
    }

    private function splitWeekdayWeekend(?string $mulai, ?string $selesai): array
    {
        $wd = 0; $we = 0;
        if (!$mulai || !$selesai) return ['weekday'=>0,'weekend'=>0];

        $start = Carbon::parse($mulai)->startOfDay();
        $end   = Carbon::parse($selesai)->startOfDay();

        if ($end->lessThanOrEqualTo($start)) {
            $start->isWeekend() ? $we++ : $wd++;
            return ['weekday'=>$wd,'weekend'=>$we];
        }

        foreach (CarbonPeriod::create($start, $end->copy()->subDay()) as $d) {
            $d->isWeekend() ? $we++ : $wd++;
        }
        return ['weekday'=>$wd,'weekend'=>$we];
    }
}
