<?php

namespace App\Filament\Reservasi\Resources\ReservasiResource\Pages;

use App\Filament\Reservasi\Resources\ReservasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
  use App\Models\Fasilitas;

class CreateReservasi extends CreateRecord
{
    protected static string $resource = ReservasiResource::class;

    // Menyimpan data additional yang dipilih
    protected function afterCreate(): void
    {
        // Menyimpan data fasilitas
        foreach ($this->form->getState()['fasilitas_selected'] ?? [] as $id => $selected) {
            if ($selected) {
                $jumlah = $this->form->getState()['fasilitas_jumlah'][$id] ?? 1;
                $this->record->fasilitas()->attach($id, ['jumlah' => $jumlah]);
            }
        }

        // Menyimpan data additional yang dipilih
        foreach ($this->form->getState()['additional_selected'] ?? [] as $id => $selected) {
            if ($selected) {
                $jumlah = $this->form->getState()['additional_jumlah'][$id] ?? 1;
                $this->record->additional()->attach($id, ['jumlah' => $jumlah]);
            }
        }

        // Menyimpan data menu makan yang dipilih
        foreach ($this->form->getState()['menu_makan_selected'] ?? [] as $id => $selected) {
            if ($selected) {
                $jumlah = $this->form->getState()['menu_makan_jumlah'][$id] ?? 1;
                $this->record->menuMakan()->attach($id, ['jumlah' => $jumlah]);
            }
        }
    }
}

