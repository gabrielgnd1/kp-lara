<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenuMakanResource\Pages;
use App\Models\MenuMakan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuMakanResource extends Resource
{
    protected static ?string $model = MenuMakan::class;
    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?string $navigationGroup = 'Manajemen Reservasi';
    protected static ?string $navigationLabel = 'Kelola Menu Makan';

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Menu Makan';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama')
                ->label('Nama Menu')
                ->required()
                ->maxLength(100),
            Forms\Components\TextInput::make('harga')
                ->label('Harga')
                ->numeric()
                ->required()
                ->prefix('Rp'),
            Forms\Components\Select::make('status')
                ->options([
                    'Available' => 'Available',
                    'Not Available' => 'Not Available',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Menu')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Available' => 'success',
                        'Not Available' => 'danger',
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Available' => 'Available',
                        'Not Available' => 'Not Available',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuMakan::route('/'),
            'create' => Pages\CreateMenuMakan::route('/create'),
            'edit' => Pages\EditMenuMakan::route('/{record}/edit'),
        ];
    }
}
