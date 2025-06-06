<?php

namespace App\Filament\Reservasi\Resources\ReservasiResource\Pages;

use App\Filament\Reservasi\Resources\ReservasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
  use App\Models\Fasilitas;

class CreateReservasi extends CreateRecord
{
    protected static string $resource = ReservasiResource::class;
  

protected function afterCreate(): void
{
    foreach ($this->form->getState()['fasilitas_selected'] ?? [] as $id => $selected) {
        if ($selected) {
            $jumlah = $this->form->getState()['fasilitas_jumlah'][$id] ?? 1;
            $this->record->fasilitas()->attach($id, ['jumlah' => $jumlah]);
        }
    }
}

}

