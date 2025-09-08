<?php

namespace App\Filament\Reservasi\Resources\ReservasiResource\Pages;

use App\Filament\Reservasi\Resources\ReservasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReservasi extends EditRecord
{
    protected static string $resource = ReservasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record->load(['fasilitas', 'additional', 'menuMakan']);

        if (empty($data['hari_tipe']) && !empty($record->waktu_check_in)) {
            $dow = $record->waktu_check_in->dayOfWeekIso;
            $data['hari_tipe'] = in_array($dow, [6, 7], true) ? 'Weekend' : 'Weekday';
        }
        $data['ubaya_member'] = $data['ubaya_member'] ?? 'Internal';

         // ---------- FASILITAS ----------
        $data['fasilitas_selected'] = [];
        $data['fasilitas_jumlah']   = [];
        foreach ($record->fasilitas as $f) {
            $id = (string) $f->id;
            $data['fasilitas_selected'][$id] = true;
            $data['fasilitas_jumlah'][$id]   = (int) ($f->pivot->jumlah ?? 1);
        }

        // ---------- ADDITIONAL ----------
        $data['additional_selected'] = [];
        $data['additional_jumlah']   = [];
        foreach ($record->additional as $a) {
            $id = (string) $a->id;
            $data['additional_selected'][$id] = true;
            // only if you actually use jumlah for additional:
            $data['additional_jumlah'][$id]   = (int) ($a->pivot->jumlah ?? 1);
        }

        // ---------- MENU MAKAN ----------
        $data['menu_makan_selected'] = [];
        $data['menu_makan_jumlah']   = [];
        foreach ($record->menuMakan as $m) {
            $id = (string) $m->id;
            $data['menu_makan_selected'][$id] = true;
            $data['menu_makan_jumlah'][$id]   = (int) ($m->pivot->jumlah ?? 1);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        ReservasiResource::syncPivotsFromFormState($this->record, $this->form->getState());
    }
}
