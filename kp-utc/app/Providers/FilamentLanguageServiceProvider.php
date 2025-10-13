<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\Facades\App;

class FilamentLanguageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Set the application locale to Indonesian
        App::setLocale('id');
        
        // Register translations
        $this->loadTranslationsFrom(__DIR__.'/../../lang/id', 'filament');
        
        // Override common action labels
        \Filament\Tables\Actions\CreateAction::configureUsing(function ($action) {
            $action->modalHeading('Tambah Data Baru')
                  ->label('Tambah')
                  ->successNotificationTitle('Data berhasil ditambahkan');
        });

        \Filament\Tables\Actions\EditAction::configureUsing(function ($action) {
            $action->modalHeading('Edit Data')
                  ->label('Edit')
                  ->successNotificationTitle('Data berhasil diubah');
        });

        \Filament\Tables\Actions\DeleteAction::configureUsing(function ($action) {
            $action->modalHeading('Hapus Data')
                  ->label('Hapus')
                  ->successNotificationTitle('Data berhasil dihapus');
        });

        \Filament\Tables\Actions\ViewAction::configureUsing(function ($action) {
            $action->modalHeading('Detail Data')
                  ->label('Lihat');
        });

        // Configure bulk actions
        \Filament\Tables\Actions\BulkAction::configureUsing(function ($action) {
            if ($action instanceof \Filament\Tables\Actions\DeleteBulkAction) {
                $action->modalHeading('Hapus Data Terpilih')
                      ->label('Hapus Terpilih')
                      ->successNotificationTitle('Data berhasil dihapus');
            }
        });
    }
}