<?php

namespace App\Filament\Lapangan\Resources;

use App\Filament\Lapangan\Resources\LaporanResource\Pages;
use App\Filament\Lapangan\Resources\LaporanResource\RelationManagers;
use App\Models\Laporan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_laporan')
                    ->required()
                    ->maxLength(100),
               Forms\Components\FileUpload::make('foto_laporan')
                    ->image()
                    ->required(), //do this
                Forms\Components\TextInput::make('decision')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('prioritas')
                    ->required()
                    ->maxLength(45),
                Forms\Components\DatePicker::make('tanggal_lapor')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_deadline')
                    ->required(),
                Forms\Components\TextInput::make('tipe_laporan')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('notifikasi')
                    ->required()
                    ->maxLength(45),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('area_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_laporan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('foto_laporan'),
                Tables\Columns\TextColumn::make('decision')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prioritas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_lapor')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_laporan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notifikasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area_id')
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
            'index' => Pages\ListLaporans::route('/'),
            'create' => Pages\CreateLaporan::route('/create'),
            'edit' => Pages\EditLaporan::route('/{record}/edit'),
        ];
    }
}
