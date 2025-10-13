<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdminReservasiResource\Pages;
use App\Models\Reservasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;

class AdminReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Reservation Management';
    protected static ?string $navigationLabel = 'Reservasi';

    public static function getModelLabel(): string
    {
        return 'Reservasi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Reservasi';
    }

    protected static function hitungTotalHarga(
        array $fasilitas_selected = [],
        array $fasilitas_mulai = [],
        array $fasilitas_selesai = [],
        array $additional_selected = [],
        array $additional_mulai = [],
        array $additional_selesai = [],
        array $menu_makan_selected = [],
        array $menu_makan_jumlah = [],
        int $diskon = 0,
        string $jenis_member = 'Internal',
        ?string $checkIn = null,
        ?string $checkOut = null,
        array $fasilitas_jumlah_orang = []
    ): float {
        $total = 0;
        return $total;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama_pemesan', 'email', 'no_telepon', 'judul_kegiatan'];
    }

    public static function form(Form $form): Form
    {
        $recalc = function (Get $get, callable $set) {
            $total = static::hitungTotalHarga(
                (array) ($get('fasilitas_selected') ?? []),
                (array) ($get('fasilitas_mulai') ?? []),
                (array) ($get('fasilitas_selesai') ?? []),
                (array) ($get('additional_selected') ?? []),
                (array) ($get('additional_mulai') ?? []),
                (array) ($get('additional_selesai') ?? []),
                (array) ($get('menu_makan_selected') ?? []),
                (array) ($get('menu_makan_jumlah') ?? []),
                (int) ($get('diskon') ?? 0),
                ($get('jenis_member') ?? 'Internal'),
                $get('waktu_check_in'),
                $get('waktu_check_out'),
                (array) ($get('fasilitas_jumlah_orang') ?? [])
            );
            $set('harga_akhir', (int) max(0, $total));
        };

        return $form->schema([
            Hidden::make('form_ready')
                ->default(true)
                ->dehydrated(false),

            Section::make('')
                ->schema([
                    Radio::make('jenis_member')
                        ->label('Jenis Member')
                        ->options(['Internal' => 'Internal', 'Eksternal' => 'Eksternal'])
                        ->required()
                        ->reactive()
                ])
                ->columns(1),

            Grid::make([
                'default' => 1,
                'md' => 2,
            ])->schema([
                TextInput::make('nama_pemesan')
                    ->label('Nama Pemesan')
                    ->required()
                    ->maxLength(100),

                TextInput::make('no_telepon')
                    ->label('No Telepon')
                    ->tel()
                    ->required()
                    ->maxLength(20),
            ]),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->maxLength(100),
                
            TextInput::make('judul_kegiatan')
                ->label('Judul Kegiatan')
                ->required()
                ->maxLength(100),

            DateTimePicker::make('waktu_check_in')
                ->label('Waktu Check In')
                ->required()
                ->reactive(),

            DateTimePicker::make('waktu_check_out')
                ->label('Waktu Check Out')
                ->required()
                ->rule('after:waktu_check_in')
                ->reactive(),

            TextInput::make('jumlah_laki')
                ->label('Jumlah Laki-laki')
                ->required()
                ->numeric()
                ->minValue(0),
                
            TextInput::make('jumlah_perempuan')
                ->label('Jumlah Perempuan')
                ->required()
                ->numeric()
                ->minValue(0),

            Textarea::make('informasi_tambahan')
                ->label('Informasi Tambahan')
                ->default('')
                ->columnSpanFull(),

            Section::make('Status')
                ->schema([
                    Select::make('status_reservasi')
                        ->label('Status Reservasi')
                        ->options([
                            'ACC' => 'Accepted',
                            'NOT ACC' => 'Not Accepted',
                            'CANCELLED' => 'Cancelled'
                        ])
                        ->required(),
                    Select::make('status_pembayaran')
                        ->label('Status Pembayaran')
                        ->options([
                            'BARU' => 'New',
                            'DP' => 'Down Payment',
                            'LUNAS' => 'Fully Paid',
                            'BATAL' => 'Cancelled'
                        ])
                        ->required(),
                ])
                ->columns(2),

            TextInput::make('diskon')
                ->label('Diskon (%)')
                ->numeric()
                ->default(0)
                ->minValue(0)
                ->maxValue(100),

            TextInput::make('harga_akhir')
                ->label('Harga Akhir (Rp)')
                ->numeric()
                ->disabled()
                ->prefix('Rp'),

            DateTimePicker::make('tanggal_dibuat')
                ->label('Tanggal Dibuat')
                ->default(now())
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pemesan')
                    ->label('Nama Pemesan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('No Telepon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul_kegiatan')
                    ->label('Judul Kegiatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_check_in')
                    ->label('Waktu Check In')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_check_out')
                    ->label('Waktu Check Out')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_laki')
                    ->label('Jumlah Laki')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_perempuan')
                    ->label('Jumlah Perempuan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_reservasi')
                    ->label('Status Reservasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ACC' => 'success',
                        'NOT ACC' => 'warning',
                        'CANCELLED' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'LUNAS' => 'success',
                        'DP' => 'warning',
                        'BARU' => 'info',
                        'BATAL' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tanggal_dibuat')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_reservasi')
                    ->options([
                        'ACC' => 'Accepted',
                        'NOT ACC' => 'Not Accepted',
                        'CANCELLED' => 'Cancelled'
                    ]),
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->options([
                        'BARU' => 'New',
                        'DP' => 'Down Payment',
                        'LUNAS' => 'Fully Paid',
                        'BATAL' => 'Cancelled'
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
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
            'index' => Pages\ListAdminReservasis::route('/'),
            'create' => Pages\CreateAdminReservasi::route('/create'),
            'edit' => Pages\EditAdminReservasi::route('/{record}/edit'),
            'view' => Pages\ViewAdminReservasi::route('/{record}'),
        ];
    }

    public static function disabledForm(Form $form): Form 
    {
        return static::form($form)->disabled();
    }
}