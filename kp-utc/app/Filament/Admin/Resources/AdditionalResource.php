<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdditionalResource\Pages;
use App\Filament\Admin\Resources\AdditionalResource\RelationManagers;
use App\Models\Additional;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdditionalResource extends Resource
{
    protected static ?string $model = Additional::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Manajemen Reservasi';
    protected static ?string $navigationLabel = 'Kelola Fasilitas Tambahan';
    protected static ?int $navigationSort = 2;

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Fasilitas Tambahan';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(45),
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(1000)
                    ->rows(3),
                Forms\Components\TextInput::make('harga')
                    ->label('Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Available' => 'Tersedia',
                        'Not Available' => 'Tidak Tersedia',
                    ])
                    ->default('Available')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Available' => 'success',
                        'Not Available' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListAdditionals::route('/'),
            'create' => Pages\CreateAdditional::route('/create'),
            'edit' => Pages\EditAdditional::route('/{record}/edit'),
        ];
    }
}
