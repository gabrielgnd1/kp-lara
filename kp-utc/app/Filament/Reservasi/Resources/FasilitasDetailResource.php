<?php

namespace App\Filament\Reservasi\Resources;

use App\Filament\Reservasi\Resources\FasilitasDetailResource\Pages;
use App\Models\Fasilitas;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;

class FasilitasDetailResource extends Resource
{
    protected static ?string $model = Fasilitas::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = null;
    protected static ?string $navigationLabel = 'Daftar Fasilitas';
    protected static ?int $navigationSort = 2;

    /**
     * Only Admin UTC (id_role 2) and Admin IOC (id_role 4) can access this
     */
    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->id_role, [2, 4]); // 2 = Admin UTC, 4 = Admin IOC
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFasilitasDetails::route('/'),
        ];
    }
}
