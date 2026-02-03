<?php

namespace App\Filament\Admin\Resources\AdminLaporanResource\Pages;

use App\Filament\Admin\Resources\AdminLaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminLaporan extends EditRecord
{
    protected static string $resource = AdminLaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('admin.laporan.print', $this->record->id))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}