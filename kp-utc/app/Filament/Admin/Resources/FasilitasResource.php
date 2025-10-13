<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FasilitasResource\Pages;
use App\Models\Fasilitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FasilitasResource extends Resource
{
    protected static ?string $model = Fasilitas::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Facility Management';
    protected static ?string $navigationLabel = 'Manage Facilities';

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
            Forms\Components\Select::make('jenis_user')
                ->options([
                    'Internal' => 'Internal',
                    'Eksternal' => 'External',
                ])
                ->required(),
            Forms\Components\Select::make('menginap')
                ->options([
                    'Menginap' => 'Overnight Stay',
                    'Tidak Menginap' => 'Day Use',
                ])
                ->required(),
            Forms\Components\Select::make('day')
                ->options([
                    'Weekday' => 'Weekday',
                    'Weekend' => 'Weekend',
                ])
                ->required(),
            Forms\Components\TextInput::make('harga')
                ->label('Price')
                ->numeric()
                ->required()
                ->prefix('Rp'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Fasilitas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kapasitas')
                    ->label('Kapasitas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Available' => 'success',
                        'Not Available' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_user')
                    ->label('Jenis Pengguna')
                    ->sortable(),
                Tables\Columns\TextColumn::make('menginap')
                    ->label('Tipe Menginap')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menginap' => 'info',
                        'Tidak Menginap' => 'warning',
                        default => 'secondary',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('day')
                    ->label('Tipe Hari')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Weekday' => 'success',
                        'Weekend' => 'primary',
                        default => 'secondary',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Available' => 'Available',
                        'Not Available' => 'Not Available',
                    ]),
                Tables\Filters\SelectFilter::make('menginap')
                    ->label('Stay Type')
                    ->options([
                        'Menginap' => 'Overnight Stay',
                        'Tidak Menginap' => 'Day Use',
                    ]),
                Tables\Filters\SelectFilter::make('day')
                    ->label('Day Type')
                    ->options([
                        'Weekday' => 'Weekday',
                        'Weekend' => 'Weekend',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_user')
                    ->options([
                        'Internal' => 'Internal',
                        'Eksternal' => 'External',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListFasilitas::route('/'),
            'create' => Pages\CreateFasilitas::route('/create'),
            'edit' => Pages\EditFasilitas::route('/{record}/edit'),
        ];
    }
}