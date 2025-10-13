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
    protected static ?string $navigationGroup = 'Report Management';
    protected static ?string $navigationLabel = 'All Reports';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Report Details')
                ->schema([
                    Forms\Components\TextInput::make('nama_laporan')
                        ->label('Report Name')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\FileUpload::make('foto_laporan')
                        ->label('Report Photo')
                        ->image()
                        ->required(),
                    Forms\Components\Select::make('prioritas')
                        ->required()
                        ->options([
                            'Rendah' => 'Low',
                            'Sedang' => 'Medium',
                            'Tinggi' => 'High',
                        ]),
                    Forms\Components\DatePicker::make('tanggal_lapor')
                        ->label('Report Date')
                        ->required(),
                    Forms\Components\DatePicker::make('tanggal_deadline')
                        ->label('Deadline'),
                    Forms\Components\Select::make('tipe_laporan')
                        ->label('Report Type')
                        ->required()
                        ->options([
                            'Kebersihan' => 'Cleanliness',
                            'Kerusakan' => 'Damage',
                            'Perbaikan' => 'Repair',
                            'Lainnya' => 'Other',
                        ]),
                    Forms\Components\Select::make('decision')
                        ->label('Decision')
                        ->options([
                            'Belum Diproses' => 'Not Processed',
                            'Diproses' => 'Being Processed',
                            'Selesai' => 'Completed',
                        ])
                        ->required(),
                    Forms\Components\Select::make('notifikasi')
                        ->label('Notification')
                        ->options([
                            'Belum Dibaca' => 'Unread',
                            'Dibaca' => 'Read',
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
                    ->label('Report Name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('foto_laporan')
                    ->label('Photo'),
                Tables\Columns\TextColumn::make('prioritas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tinggi' => 'danger',
                        'Sedang' => 'warning',
                        'Rendah' => 'info',
                    }),
                Tables\Columns\TextColumn::make('tipe_laporan')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('decision')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Selesai' => 'success',
                        'Diproses' => 'warning',
                        'Belum Diproses' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tanggal_lapor')
                    ->label('Report Date')
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
        ];
    }
}