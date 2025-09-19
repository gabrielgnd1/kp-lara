<?php

namespace App\Filament\Reservasi\Resources\ReservasiResource\Pages;

use App\Filament\Reservasi\Resources\ReservasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon;

class EditReservasi extends EditRecord
{
    protected static string $resource = ReservasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Ambil seluruh state form (termasuk mirror arrays)
        $state = $this->form->getState();

        // Sync pivot dari helper yang sudah kamu punya
        ReservasiResource::syncPivotsFromFormState($this->record, $state);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record->loadMissing(['fasilitas','additional','menuMakan']);

        $data['diskon'] = $data['diskon'] ?? (int) ($this->record->diskon ?? 0);

        // ------- FASILITAS (pakai groupKey = strtolower(nama)) -------
        $data['fasilitas_selected'] = [];
        $data['fasilitas_mulai']    = [];
        $data['fasilitas_selesai']  = [];
        foreach ($record->fasilitas as $f) {
            $groupKey = trim(mb_strtolower($f->nama));
            $data['fasilitas_selected'][$groupKey] = true;
            $data['fasilitas_mulai'][$groupKey]    = $f->pivot->mulai ?? $data['waktu_check_in'] ?? null;
            $data['fasilitas_selesai'][$groupKey]  = $f->pivot->selesai ?? $data['waktu_check_out'] ?? null;
        }

        // ------- ADDITIONAL (key = id) -------
        $data['additional_selected'] = [];
        $data['additional_mulai']    = [];
        $data['additional_selesai']  = [];
        foreach ($record->additional as $a) {
            $id = (string) $a->id;
            $data['additional_selected'][$id] = true;
            $data['additional_mulai'][$id]    = $a->pivot->mulai ?? $data['waktu_check_in'] ?? null;
            $data['additional_selesai'][$id]  = $a->pivot->selesai ?? $data['waktu_check_out'] ?? null;
        }

        // ------- MENU MAKAN -------
        $data['menu_makan_selected'] = [];
        $data['menu_makan_jumlah']   = [];
        foreach ($record->menuMakan as $m) {
            $id = (string) $m->id;
            $data['menu_makan_selected'][$id] = true;
            $data['menu_makan_jumlah'][$id]   = (int) ($m->pivot->jumlah ?? 1);
        }

        // Optional: isi default radio & hari_tipe agar section fasilitas tampil
        if ($record->fasilitas->first()) {
            $first = $record->fasilitas->first();
            $data['ubaya_member'] = $data['ubaya_member'] ?? $first->jenis_user;
            $data['hari_tipe']    = $data['hari_tipe']    ?? $first->day;
        }

        $data['harga_akhir'] = $data['harga_akhir'] ?? (int) ($this->record->harga_akhir ?? 0);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $state = $this->form->getState();

        $fSel = (array) ($state['fasilitas_selected']  ?? []);
        $fMul = (array) ($state['fasilitas_mulai']     ?? []);
        $fSelis = (array) ($state['fasilitas_selesai'] ?? []);

        $aSel = (array) ($state['additional_selected']  ?? []);
        $aMul = (array) ($state['additional_mulai']     ?? []);
        $aSelis = (array) ($state['additional_selesai'] ?? []);

        $mSel = (array) ($state['menu_makan_selected'] ?? []);
        $mJml = (array) ($state['menu_makan_jumlah']   ?? []);

        $disk  = (int)   ($data['diskon'] ?? 0);
        $jenis = (string)($data['ubaya_member'] ?? $state['ubaya_member'] ?? 'Internal');
        $cin   = $data['waktu_check_in']  ?? $state['waktu_check_in']  ?? null;
        $cout  = $data['waktu_check_out'] ?? $state['waktu_check_out'] ?? null;

        $data['harga_akhir'] = \App\Filament\Reservasi\Resources\ReservasiResource::hitungTotalHarga(
            $fSel, $fMul, $fSelis,
            $aSel, $aMul, $aSelis,
            $mSel, $mJml,
            $disk,
            $jenis,
            $cin, $cout
        );

        // Buang mirror fields agar tidak disimpan ke kolom non-eksis
        unset(
            $data['fasilitas_selected'], $data['fasilitas_mulai'], $data['fasilitas_selesai'],
            $data['additional_selected'], $data['additional_mulai'], $data['additional_selesai'],
            $data['menu_makan_selected'], $data['menu_makan_jumlah'],
            $data['hari_tipe']
        );

        return $data;
    }
}
