<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SuperAdminFasilitasResource\Pages;
use App\Models\Fasilitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;

class SuperAdminFasilitasResource extends Resource
{
    protected static ?string $model = Fasilitas::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Reservation Management';
    protected static ?string $navigationLabel = 'Manage Fasilitas';

    /**
     * Only Super Admin (id_role 1) can access this
     */
    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && $user->id_role === 1; // 1 = Super Admin
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama')
                ->label('Facility Name')
                ->required()
                ->maxLength(100),
            Forms\Components\TextInput::make('kapasitas')
                ->label('Capacity')
                ->numeric()
                ->required(),
            Forms\Components\Textarea::make('keterangan')
                ->label('Description')
                ->maxLength(500),
            Forms\Components\Select::make('status')
                ->options([
                    'Available' => 'Available',
                    'Not Available' => 'Not Available',
                ])
                ->required(),
            Forms\Components\TextInput::make('harga')
                ->label('Price')
                ->numeric()
                ->required()
                ->prefix('Rp'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFasilitasCards::route('/'),
        ];
    }
}

