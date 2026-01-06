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
use Illuminate\Support\Facades\Auth;
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
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\HtmlString;

class AdminReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Manajemen Reservasi';
    protected static ?string $navigationLabel = 'Kelola Reservasi';

    public static function getModelLabel(): string
    {
        return 'Reservasi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Reservasi';
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        
        if ($user->role === 'Admin UTC') {
            $data['id_pic_utc'] = $user->id;
            $data['id_pic_ioc'] = null;
            $data['status_reservasi'] = 'ACC';
        } elseif ($user->role === 'Admin IOC') {
            $data['id_pic_ioc'] = $user->id;
            $data['id_pic_utc'] = null;
            $data['status_reservasi'] = 'NOT ACC';
        }
        
        return $data;
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
                        ])
                        ->required(),
                    Select::make('status_pembayaran')
                        ->label('Status Pembayaran')
                        ->options([
                            'BARU' => 'New',
                            'DP' => 'Down Payment',
                            'LUNAS' => 'Fully Paid',
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

            // SECTION: Fasilitas yang Dipesan
            Section::make('Fasilitas yang Dipesan')
                ->description('Daftar fasilitas yang telah dipesan')
                ->schema([
                    Forms\Components\View::make('forms.components.booked-facilities'),
                ]),

            // SECTION: Additional yang Dipesan
            Section::make('Additional yang Dipesan')
                ->description('Daftar additional yang telah dipesan')
                ->schema([
                    Forms\Components\View::make('forms.components.booked-additional'),
                ]),

            // SECTION: Menu Makan yang Dipesan
            Section::make('Menu Makan yang Dipesan')
                ->description('Daftar menu makan yang telah dipesan')
                ->schema([
                    Forms\Components\View::make('forms.components.booked-menu-makan'),
                ]),

            // SECTION: Ringkasan Dokumen (View-Only untuk Super Admin)
            Section::make('📂 Ringkasan Dokumen')
                ->description('Semua file dokumen yang telah diupload')
                ->visible(fn(Get $get) => !empty($get('file_reservation_form')) || !empty($get('file_bukti_dp')) || !empty($get('file_bukti_lunas')))
                ->schema([
                    Group::make()
                        ->schema([
                            Placeholder::make('doc_form')
                                ->label('📋 Reservation Form')
                                ->dehydrated(false)
                                ->content(function(Get $get) {
                                    $file = $get('file_reservation_form');
                                    if ($file && !empty($file)) {
                                        if (is_array($file)) {
                                            $file = $file[0] ?? null;
                                        }
                                        if ($file && is_string($file)) {
                                            $url = asset('storage/' . $file);
                                            return new HtmlString("<a href=\"{$url}\" target=\"_blank\" class=\"inline-flex items-center gap-2 px-4 py-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 font-medium\">📥 Download Reservation Form</a>");
                                        }
                                    }
                                    return '❌ Tidak ada file';
                                }),

                            Placeholder::make('doc_dp')
                                ->label('💳 Bukti Pembayaran DP')
                                ->dehydrated(false)
                                ->content(function(Get $get) {
                                    $file = $get('file_bukti_dp');
                                    if ($file && !empty($file)) {
                                        if (is_array($file)) {
                                            $file = $file[0] ?? null;
                                        }
                                        if ($file && is_string($file)) {
                                            $url = asset('storage/' . $file);
                                            return new HtmlString("<a href=\"{$url}\" target=\"_blank\" class=\"inline-flex items-center gap-2 px-4 py-3 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 font-medium\">📥 Download Bukti DP</a>");
                                        }
                                    }
                                    return '❌ Tidak ada file';
                                }),

                            Placeholder::make('doc_lunas')
                                ->label('✅ Bukti Pembayaran Lunas')
                                ->dehydrated(false)
                                ->content(function(Get $get) {
                                    $file = $get('file_bukti_lunas');
                                    if ($file && !empty($file)) {
                                        if (is_array($file)) {
                                            $file = $file[0] ?? null;
                                        }
                                        if ($file && is_string($file)) {
                                            $url = asset('storage/' . $file);
                                            return new HtmlString("<a href=\"{$url}\" target=\"_blank\" class=\"inline-flex items-center gap-2 px-4 py-3 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 font-medium\">📥 Download Bukti Lunas</a>");
                                        }
                                    }
                                    return '❌ Tidak ada file';
                                }),
                        ])
                        ->columns(1),
                ]),

            DateTimePicker::make('tanggal_dibuat')
                ->label('Tanggal Dibuat')
                ->default(now())
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        
        return $table
            ->modifyQueryUsing(function (Builder $query) use ($user) {
                if ($user->role === 'Admin UTC') {
                    // Show all reservations for Admin UTC
                    return $query;
                }
                // For other roles, show their own reservations
                return $query;
            })
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
                        'NOT ACC' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'LUNAS' => 'success',
                        'DP' => 'warning',
                        'BARU' => 'info',
                        default => 'gray',
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
                    ]),
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->options([
                        'BARU' => 'New',
                        'DP' => 'Down Payment',
                        'LUNAS' => 'Fully Paid',
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
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => static::canEditRecord($record)),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => static::canEditRecord($record)),
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\Action::make('view_detail')
                    ->label('View Detail')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn (Reservasi $record) => route('reservasi.detail', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('accept')
                    ->label('Terima')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => 
                        Auth::user()->role === 'Admin UTC' && 
                        $record->status_reservasi === 'NOT ACC' &&
                        static::isCheckInNotPassed($record)
                    )
                    ->action(function ($record) {
                        $record->update([
                            'status_reservasi' => 'ACC',
                            'id_pic_utc' => Auth::id(),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Terima Reservasi')
                    ->modalDescription('Apakah Anda yakin ingin menerima reservasi ini?')
                    ->modalSubmitActionLabel('Ya, Terima')
                    ->successNotificationTitle('Reservasi berhasil diterima'),
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

    /**
     * Check if a reservasi record can be edited
     * Not editable if within H-3 before check in (daysUntilCheckIn <= 2)
     */
    public static function canEditRecord($record): bool
    {
        if (!$record->waktu_check_in) {
            return true; // Jika tidak ada check in, allow edit
        }

        $checkInDate = \Carbon\Carbon::parse($record->waktu_check_in)->startOfDay();
        $now = \Carbon\Carbon::now()->startOfDay();
        $daysUntilCheckIn = $now->diffInDays($checkInDate, false); // Negative jika sudah lewat

        // Jika H-3 atau kurang dari check in, tidak bisa edit
        // daysUntilCheckIn <= 2 berarti H-2 atau lebih dekat
        return $daysUntilCheckIn > 2;
    }

    /**
     * Check if check in date has not passed yet
     */
    public static function isCheckInNotPassed($record): bool
    {
        if (!$record->waktu_check_in) {
            return true; // Jika tidak ada check in, allow
        }

        $checkInDate = \Carbon\Carbon::parse($record->waktu_check_in)->startOfDay();
        $now = \Carbon\Carbon::now()->startOfDay();

        // Return true jika check in belum lewat (check in date >= today)
        return $checkInDate >= $now;
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