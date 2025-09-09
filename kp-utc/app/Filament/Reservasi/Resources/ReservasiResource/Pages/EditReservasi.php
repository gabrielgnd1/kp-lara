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

        // fasilitas
        $data['fasilitas_selected'] = [];
        $data['fasilitas_jumlah']   = [];
        foreach ($record->fasilitas as $f) {
            $id = (string) $f->id;
            $data['fasilitas_selected'][$id] = true;
            $data['fasilitas_jumlah'][$id]   = (int) ($f->pivot->jumlah ?? 1);
        }

        // additional
        $data['additional_selected'] = [];
        $data['additional_jumlah']   = [];
        foreach ($record->additional as $a) {
            $id = (string) $a->id;
            $data['additional_selected'][$id] = true;
            $data['additional_jumlah'][$id]   = (int) ($a->pivot->jumlah ?? 1);
        }

        // menu makan
        $data['menu_makan_selected'] = [];
        $data['menu_makan_jumlah']   = [];
        foreach ($record->menuMakan as $m) {
            $id = (string) $m->id;
            $data['menu_makan_selected'][$id] = true;
            $data['menu_makan_jumlah'][$id]   = (int) ($m->pivot->jumlah ?? 1);
        }

        // optional: set filter so the list appears during edit
        $first = $record->fasilitas->first();
        if ($first) {
            $data['ubaya_member'] = $data['ubaya_member'] ?? $first->jenis_user;
            $data['hari_tipe']    = $data['hari_tipe']    ?? $first->day;
        }

        $data['harga_akhir'] = $data['harga_akhir'] ?? (int) ($this->record->harga_akhir ?? 0);

        return $data;

    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $state = $this->form->getState();

        $fSel = (array) ($state['fasilitas_selected'] ?? []);
        $fJml = (array) ($state['fasilitas_jumlah'] ?? []);
        $aSel = (array) ($state['additional_selected'] ?? []);
        $aJml = (array) ($state['additional_jumlah'] ?? []);
        $mSel = (array) ($state['menu_makan_selected'] ?? []);
        $mJml = (array) ($state['menu_makan_jumlah'] ?? []);
        $disk = (int) ($data['diskon'] ?? 0);
        $jenis = $data['ubaya_member'] ?? ($state['ubaya_member'] ?? 'Internal');

        $split = \App\Filament\Reservasi\Resources\ReservasiResource::hitungHariSplit(
            $data['waktu_check_in'] ?? $state['waktu_check_in'] ?? null,
            $data['waktu_check_out'] ?? $state['waktu_check_out'] ?? null,
        );

        $data['harga_akhir'] = ReservasiResource::hitungTotalHarga(
            $fSel, $fJml, $aSel, $aJml, $mSel, $mJml,
            $disk,
            (int) $split['weekday'], (int) $split['weekend'],
            $jenis
        );

        // buang mirror
        unset(
            $data['fasilitas_selected'], $data['fasilitas_jumlah'],
            $data['additional_selected'], $data['additional_jumlah'],
            $data['menu_makan_selected'], $data['menu_makan_jumlah']
        );

        return $data;
    }
}
