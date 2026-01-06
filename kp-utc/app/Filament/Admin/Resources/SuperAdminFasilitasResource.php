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
    protected static ?string $navigationGroup = 'Manajemen Reservasi';
    protected static ?string $navigationLabel = 'Kelola Fasilitas';
    protected static ?int $navigationSort = 1;

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Fasilitas';
    }

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
                ->label('Nama Fasilitas')
                ->required()
                ->maxLength(100),
            Forms\Components\TextInput::make('kapasitas')
                ->label('Kapasitas')
                ->numeric()
                ->required(),
            Forms\Components\Textarea::make('keterangan')
                ->label('Keterangan')
                ->maxLength(500),
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'Available' => 'Available',
                    'Not Available' => 'Not Available',
                ])
                ->required(),
            
            Forms\Components\Section::make('Harga')
                ->schema([
                    Forms\Components\TextInput::make('harga_weekday_internal')
                        ->label('Harga Weekday Internal')
                        ->numeric()
                        ->required()
                        ->prefix('Rp'),
                    Forms\Components\TextInput::make('harga_weekday_eksternal')
                        ->label('Harga Weekday Eksternal')
                        ->numeric()
                        ->required()
                        ->prefix('Rp'),
                    Forms\Components\TextInput::make('harga_weekend_internal')
                        ->label('Harga Weekend Internal')
                        ->numeric()
                        ->required()
                        ->prefix('Rp'),
                    Forms\Components\TextInput::make('harga_weekend_eksternal')
                        ->label('Harga Weekend Eksternal')
                        ->numeric()
                        ->required()
                        ->prefix('Rp'),
                ])
                ->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFasilitasCards::route('/'),
            'create' => Pages\CreateSuperAdminFasilitas::route('/create'),
        ];
    }
}

