<?php

namespace App\Filament\Lapangan\Resources;

use App\Filament\Lapangan\Resources\LaporanResource\Pages;
use App\Models\Laporan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Card;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;


class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama_laporan')
                ->label('Nama Laporan')
                ->required()
                ->maxLength(100),

            Forms\Components\FileUpload::make('foto_laporan')
                ->label('Foto Laporan')
                ->image()
                ->required(),

            Forms\Components\Select::make('prioritas')
                ->label('Prioritas')
                ->required()
                ->options([
                    'Rendah' => 'Rendah',
                    'Sedang' => 'Sedang',
                    'Tinggi' => 'Tinggi',
                ]),

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

            Forms\Components\Select::make('area_id')
                ->relationship('area', 'nama_area')
                ->required(),

            Forms\Components\Hidden::make('decision')
                ->default('Belum Diproses'),

            Forms\Components\Hidden::make('tanggal_selesai')
                ->default(fn () => now()->addMonth()->toDateString()),

            Forms\Components\Hidden::make('notifikasi')
                ->default('Belum Dibaca'),
        ]);
    }

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Card::make([
                ImageColumn::make('foto_laporan')
                    ->disk('public')
                    ->path('laporan')
                    ->height(180)
                    ->width(180)
                    ->extraAttributes(['class' => 'mx-auto rounded-md object-cover']),

                TextColumn::make('nama_laporan')
                    ->weight('bold')
                    ->label('Nama'),

                TextColumn::make('tanggal_lapor')
                    ->label('Tanggal')
                    ->date(),

                TextColumn::make('prioritas')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Tinggi' => 'danger',
                        'Sedang' => 'warning',
                        'Rendah' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('decision')
                    ->label('Status')
                    ->badge(),
            ]),
        ])
        ->contentGrid([
            'default' => 1,
            'md' => 2,
            'xl' => 3,
        ])
        ->paginated()
        ->striped(false)
        ->actions([]) // hide edit/delete tombol default
        ->bulkActions([]);
}




    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
{
    return [
        'index' => Pages\ListLaporans::route('/'),
        'create' => Pages\CreateLaporan::route('/create'),
        'edit' => Pages\EditLaporan::route('/{record}/edit'),
        //'tambah' => Pages\TambahLaporan::routes('/tambah'), // ✅ ini custom page
    ];
}
}
