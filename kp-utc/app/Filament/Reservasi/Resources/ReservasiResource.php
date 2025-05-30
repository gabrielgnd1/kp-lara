<?php

namespace App\Filament\Reservasi\Resources;

use App\Filament\Reservasi\Resources\ReservasiResource\Pages;
use App\Filament\Reservasi\Resources\ReservasiResource\RelationManagers;
use App\Models\Reservasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_pemesan')
                    ->label('Nama Pemesan')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('no_telepon')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('judul_kegiatan')
                    ->required()
                    ->maxLength(100),
                Forms\Components\DateTimePicker::make('waktu_check_in')
                    ->required(),
                Forms\Components\DateTimePicker::make('waktu_check_out')
                    ->required(),
                Forms\Components\TextInput::make('jumlah_laki')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jumlah_perempuan')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('informasi_tambahan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\DateTimePicker::make('tanggal_dibuat')
                    ->required(),
                Forms\Components\TextInput::make('id_pic_ioc')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_pic_utc')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pemesan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu_check_in')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_check_out')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_laki')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_perempuan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('tanggal_dibuat')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_pic_ioc')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_pic_utc')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListReservasis::route('/'),
            'create' => Pages\CreateReservasi::route('/create'),
            'edit' => Pages\EditReservasi::route('/{record}/edit'),
        ];
    }
}
