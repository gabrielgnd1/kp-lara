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
                    ->label('Nama Laporan')
                    ->required()
                    ->maxLength(100),
               Forms\Components\FileUpload::make('foto_laporan')
                    ->label('Foto Laporan')
                    ->image()
                    ->required(), //do this
                Forms\Components\Select::make('prioritas')
                    ->label('Prioritas')
                    ->required()
                    ->options([
                        'Rendah' => 'Rendah',
                        'Sedang' => 'Sedang',
                        'Tinggi' => 'Tinggi',
                    ])
                    
                    ->label('Prioritas'),
                Forms\Components\DatePicker::make('tanggal_lapor')
                    ->label('Tanggal Lapor')
                    ->default(now())
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_deadline')
                    ->label('Tanggal Deadline'),
                Forms\Components\Select::make('tipe_laporan')
                    ->label('Tipe Laporan')
                    ->required()
                    ->options([
                        'Kebersihan' => 'Kebersihan',
                        'Kerusakan' => 'Kerusakan',
                        'Perbaikan' => 'Perbaikan',
                        'Lainnya' => 'Lainnya',
                    ]),
               Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id())
                    ->required(),
                Forms\Components\Select::make('area_id') //diisi dari tabel area
                    ->relationship('area', 'nama_area') //nama model, nama tabel
                    ->required(),
                Forms\Components\Hidden::make('decision')
                    ->default('Belum Diproses'),
                    
                Forms\Components\Hidden::make('tanggal_selesai')
                    ->default(fn () => now()->addMonth()->toDateString()),
                Forms\Components\Hidden::make('notifikasi')
                    ->default('Belum Dibaca')
            ]);
    }

    public static function table(Table $table): Table //ini buat SELECT
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_laporan')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('foto_laporan')
    ->disk('public') // pastikan sesuai disk yang kamu pakai di config/filesystems.php
    ->label('Foto'),
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
