<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdminLaporanResource\Pages;
use App\Models\Laporan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdminLaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Manajemen Laporan';
    protected static ?string $navigationLabel = 'Kelola Laporan';

    public static function getModelLabel(): string
    {
        return 'Laporan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Laporan';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Laporan')
                ->schema([
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
                        ->label('Foto Laporan')
                        ->image()
                        ->disk('public')
                        ->directory('laporan')
                        ->visibility('public')
                        ->multiple()
                        ->maxFiles(4)
                        ->reorderable(false)
                        ->appendFiles()
                        ->previewable(true)
                        ->downloadable(false)
                        ->required(),
                    Forms\Components\Select::make('area_id')
                        ->relationship('area', 'nama_area')
                        ->label('Area')
                        ->required(),
                    Forms\Components\Select::make('prioritas')
                        ->label('Prioritas')
                        ->required()
                        ->options([
                            'Belum Ditentukan' => 'Belum Ditentukan',
                            'Rendah' => 'Rendah',
                            'Sedang' => 'Sedang',
                            'Tinggi' => 'Tinggi',
                        ]),
                    Forms\Components\DatePicker::make('tanggal_lapor')
                        ->label('Tanggal Pelaporan')
                        ->required()
                        ->default(now())
                        ->disabled()
                        ->dehydrated(true),
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
                    Forms\Components\Select::make('decision')
                        ->label('Keputusan')
                        ->options([
                            'Belum Diproses' => 'Belum Diproses',
                            'Diproses' => 'Diproses',
                            'Selesai' => 'Selesai',
                        ])
                        ->required(),
                    Forms\Components\Select::make('notifikasi')
                        ->label('Notifikasi')
                        ->options([
                            'Belum Dibaca' => 'Belum Dibaca',
                            'Dibaca' => 'Dibaca',
                        ])
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_laporan')
                    ->label('Nama Laporan')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('foto_laporan')
                    ->label('Foto')
                    ->disk('public')
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
                    }),
                Tables\Columns\TextColumn::make('prioritas')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tinggi' => 'danger',
                        'Sedang' => 'warning',
                        'Rendah' => 'info',
                        'Belum Ditentukan' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tipe_laporan')
                    ->label('Tipe Laporan'),
                Tables\Columns\TextColumn::make('decision')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Selesai' => 'success',
                        'Diproses' => 'warning',
                        'Belum Diproses' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tanggal_lapor')
                    ->label('Tanggal Lapor')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('prioritas')
                    ->options([
                        'Rendah' => 'Low',
                        'Sedang' => 'Medium',
                        'Tinggi' => 'High',
                    ]),
                Tables\Filters\SelectFilter::make('decision')
                    ->options([
                        'Belum Diproses' => 'Not Processed',
                        'Diproses' => 'Being Processed',
                        'Selesai' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('discussion')
                    ->label('Diskusi')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->url(fn (Laporan $record) => route('discussion.show', $record->id))
                    ->openUrlInNewTab(false),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListAdminLaporans::route('/'),
            'create' => Pages\CreateAdminLaporan::route('/create'),
            'edit' => Pages\EditAdminLaporan::route('/{record}/edit'),
            'discussion' => Pages\DiscussionAdminLaporan::route('/{record}/discussion'),
        ];
    }
}