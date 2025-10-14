<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification as FilamentNotification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('restart_account')
                ->label('Restart Account')
                ->color('warning')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->action(function () {
                    $newPassword = Str::random(10);
                    $this->record->password = Hash::make($newPassword); // Store as hash for login
                    $this->record->save();
                    $this->form->fill(array_merge($this->form->getState(), ['password' => $newPassword])); // Show plain password to admin
                    FilamentNotification::make()
                        ->title('Account restarted')
                        ->body('New password is visible in the password field.')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}