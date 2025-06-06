<?php

namespace App\Filament\Reservasi\Resources\ReservasiResource\Pages;

use App\Filament\Reservasi\Resources\ReservasiResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables\Columns\ViewColumn;
use Filament\Actions\Action;

class ListReservasis extends ListRecords
{
     protected static string $resource = ReservasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Create')
                ->url(ReservasiResource::getUrl('create'))
                ->label('Reservasi Baru')
                ->button(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ViewColumn::make('id') // gunakan kolom nyata yang pasti ada
                    ->label('') // opsional: hilangkan label
                    ->view('filament.reservasi.resources.reservasi-resource.components.reservasi-card')
            ])
            ->paginated(false)
            ->striped(false)
            ->defaultSort('tanggal_dibuat', 'desc');
    }
}
