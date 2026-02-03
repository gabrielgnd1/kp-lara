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
    protected static ?string $navigationLabel = 'Daftar Laporan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama_laporan')
                ->label('Nama Laporan')
                ->required()
                ->maxLength(100),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->required()
                ->rows(3)
                ->maxLength(1000),

            Forms\Components\FileUpload::make('foto_laporan')
                ->label('Foto Laporan (MAX 4 FOTO)')
                ->image()
                ->disk('public')
                ->directory('laporan')
                ->visibility('public')
                ->multiple()
                ->maxFiles(4)
                ->reorderable(false)
                ->appendFiles()
                ->required(),

            Forms\Components\TextInput::make('kode_laporan')
                ->label('Kode Laporan')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\DatePicker::make('tanggal_lapor')
                ->label('Tanggal Pelaporan')
                ->default(now())
                ->disabled()
                ->required(),

            Forms\Components\Select::make('area_id')
                ->relationship('area', 'nama_area')
                ->label('Area')
                ->required(),

            Forms\Components\Hidden::make('user_id')
                ->default(fn () => auth()->id())
                ->dehydrated(true)
                ->required(),

            Forms\Components\Hidden::make('prioritas')
                ->default('Belum Ditentukan'),

            Forms\Components\Hidden::make('tipe_laporan')
                ->default('Lainnya'),

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
            Tables\Columns\Layout\Stack::make([
                ImageColumn::make('foto_laporan')
                    ->size(180)
                    ->alignCenter()
                    ->defaultImageUrl(asset('assets/images/placeholder-laporan.png'))
                    ->getStateUsing(function ($record) {
                        if ($record->foto_laporan) {
                            // Handle array of photos (get first one)
                            if (is_array($record->foto_laporan) && count($record->foto_laporan) > 0) {
                                $firstPhoto = $record->foto_laporan[0];
                                if (strpos($firstPhoto, 'laporan/') === false) {
                                    return asset('storage/laporan/' . $firstPhoto);
                                } else {
                                    return asset('storage/' . $firstPhoto);
                                }
                            } elseif (is_string($record->foto_laporan)) {
                                return asset('storage/laporan/' . $record->foto_laporan);
                            }
                        }
                        return null;
                    })
                    ->square(),
                
                TextColumn::make('nama_laporan')
                    ->weight('bold')
                    ->alignCenter()
                    ->searchable(),

                TextColumn::make('tanggal_lapor')
                    ->alignCenter()
                    ->date('d F Y'),
                
                Tables\Columns\Layout\Split::make([
                    TextColumn::make('prioritas')
                        ->badge()
                        ->color(fn ($state) => match ($state) {
                            'Tinggi' => 'danger',
                            'Sedang' => 'warning',
                            'Rendah' => 'success',
                            'Belum Ditentukan' => 'gray',
                            default => 'gray',
                        }),
                    
                    TextColumn::make('decision')
                        ->label('Status')
                        ->badge(),
                ])->from('md'),
            ]),
        ])
        ->contentGrid([
            'default' => 1,
            'md' => 2,
            'xl' => 3,
        ])
        ->paginated()
        ->striped(false)
        ->recordUrl(fn (Laporan $record) => route('discussion.show', $record->id))
        ->actions([
            Tables\Actions\Action::make('discussion')
                ->label('Diskusi')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('info')
                ->url(fn (Laporan $record) => route('discussion.show', $record->id))
                ->openUrlInNewTab(false),
        ])
        ->bulkActions([]);
}

    public static function getRelations(): array
    {
        return [];
    }

    public static function getSlug(): string
    {
        return 'laporan';
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
