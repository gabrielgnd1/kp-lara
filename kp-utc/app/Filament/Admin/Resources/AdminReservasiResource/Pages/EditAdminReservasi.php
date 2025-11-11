<?php

namespace App\Filament\Admin\Resources\AdminReservasiResource\Pages;

use App\Filament\Admin\Resources\AdminReservasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon;

class EditAdminReservasi extends EditRecord
{
    protected static string $resource = AdminReservasiResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Check if edit is allowed (not within H-3 before check in)
        if (!$this->canEdit()) {
            \Filament\Notifications\Notification::make()
                ->title('Tidak dapat mengedit')
                ->body('Reservasi tidak dapat diedit mulai H-3 sebelum tanggal check in')
                ->danger()
                ->send();
            
            $this->redirect($this->getResource()::getUrl('index'));
        }
    }

    protected function canEdit(): bool
    {
        $checkIn = $this->record->waktu_check_in;
        if (!$checkIn) {
            return true; // Jika tidak ada check in, allow edit
        }

        $checkInDate = Carbon::parse($checkIn)->startOfDay();
        $now = Carbon::now()->startOfDay();
        $daysUntilCheckIn = $now->diffInDays($checkInDate, false); // Negative jika sudah lewat

        // Jika H-3 atau kurang dari check in, tidak bisa edit
        // daysUntilCheckIn <= 2 berarti H-2 atau lebih dekat
        return $daysUntilCheckIn > 2;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}