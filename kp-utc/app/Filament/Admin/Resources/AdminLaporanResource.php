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
    protected static ?string $navigationLabel = 'Manage Laporan';

    public static function getModelLabel(): string
    {
        return 'Laporan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Laporan';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Report Details')
                ->schema([
                    Forms\Components\TextInput::make('nama_laporan')
                        ->label('Report Name')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\Textarea::make('deskripsi')
                        ->label('Description')
                        ->required()
                        ->rows(3)
                        ->maxLength(1000),
                    Forms\Components\FileUpload::make('foto_laporan')
                        ->label('Report Photo')
                        ->image()
                        ->disk('public')
                        ->directory('laporan')
                        ->visibility('public')
                        ->required(),
                    Forms\Components\Select::make('area_id')
                        ->relationship('area', 'nama_area')
                        ->label('Area')
                        ->required(),
                    Forms\Components\Select::make('prioritas')
                        ->required()
                        ->options([
                            'Belum Ditentukan' => 'Not Determined',
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
                    ->label('Nama Laporan')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('foto_laporan')
                    ->label('Foto')
                    ->disk('public')
                    ->getStateUsing(fn ($record) => $record->foto_laporan ? asset('storage/' . $record->foto_laporan) : null),
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