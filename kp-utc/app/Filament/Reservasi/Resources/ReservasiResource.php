<?php

namespace App\Filament\Reservasi\Resources;

use App\Filament\Reservasi\Resources\ReservasiResource\Pages;
use App\Models\Reservasi;
use App\Models\Fasilitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Group;
use Filament\Forms\Get;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class ReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Detail Reservasi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Radio::make('ubaya_member')
                ->label('INTERNAL / EKSTERNAL')
                ->options(['Ya' => 'Ya', 'Tidak' => 'Tidak'])
                ->required()
                ->reactive(),

            Radio::make('hari_tipe')
                ->label('Jenis Hari')
                ->options(['Weekday' => 'Weekday', 'Weekend' => 'Weekend'])
                ->required()
                ->reactive(),

            TextInput::make('nama_pemesan')->label('Nama Pemesan')->required()->maxLength(100),
            TextInput::make('no_telepon')->label('No Telepon')->required()->maxLength(20),
            TextInput::make('email')->label('Email')->email()->required()->maxLength(100),
            TextInput::make('judul_kegiatan')->label('Judul Kegiatan')->required()->maxLength(100),
            DateTimePicker::make('waktu_check_in')->label('Waktu Check In')->required(),
            DateTimePicker::make('waktu_check_out')->label('Waktu Check Out')->required(),
            TextInput::make('jumlah_laki_laki')->label('Jumlah Laki-laki')->required()->numeric(),
            TextInput::make('jumlah_perempuan')->label('Jumlah Perempuan')->required()->numeric(),
            Textarea::make('informasi_tambahan')->label('Informasi Tambahan')->default('')->columnSpanFull(),

            Hidden::make('status_reservasi')
                ->default(fn () => auth()->user()?->role_id === 5 ? 'ACC' : 'NOT ACC')
                ->required(),

            Hidden::make('id_pic_ioc')
                ->default(fn () => auth()->user()?->role_id === 5 ? auth()->id() : null)
                ->required(),

            Hidden::make('id_pic_utc')
                ->default(fn () => auth()->user()?->role_id === 7 ? auth()->id() : null)
                ->required(),

            Fieldset::make('Fasilitas yang Dipesan')
                ->disabled(fn (Get $get) =>
                    $get('ubaya_member') === null || $get('hari_tipe') === null
                )
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) {
                            return Fasilitas::query()
                                ->where('status', 'Available')
                                ->when($get('ubaya_member'), fn ($query, $val) =>
                                    $query->where('jenis_user', $val === 'Ya' ? 'Internal' : 'Eksternal'))
                                ->when($get('hari_tipe'), fn ($query, $val) =>
                                    $query->where('day', $val))
                                ->get()
                                ->unique('nama')
                                ->map(function ($fasilitas) {
                                    return Grid::make(2)->schema([
                                        Checkbox::make("fasilitas_selected.{$fasilitas->id}")
                                            ->label($fasilitas->nama)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                                $stateAll = $get('__all') ?? [];
                                                $diskon = $stateAll['diskon_persen'] ?? 0;
                                                $set('estimasi_harga', ReservasiResource::hitungTotalHarga($stateAll, $diskon));
                                            }),

                                        TextInput::make("fasilitas_jumlah.{$fasilitas->id}")
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->default(1)
                                            ->required(fn ($get) =>
                                                $get("fasilitas_selected.{$fasilitas->id}") === true)
                                            ->visible(fn ($get) =>
                                                $get("fasilitas_selected.{$fasilitas->id}") === true)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                                $stateAll = $get('__all') ?? [];
                                                $diskon = $stateAll['diskon_persen'] ?? 0;
                                                $set('estimasi_harga', ReservasiResource::hitungTotalHarga($stateAll, $diskon));
                                            }),
                                    ]);
                                })->toArray();
                        }),
                ])
                ->columns(1),

            TextInput::make('diskon_persen')
                ->label('Diskon (%)')
                ->numeric()
                ->default(0)
                ->minValue(0)
                ->maxValue(100)
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, Get $get) {
                    $stateAll = $get('__all') ?? [];
                    $set('estimasi_harga', ReservasiResource::hitungTotalHarga($stateAll, $state));
                }),

            TextInput::make('estimasi_harga')
    ->label('Estimasi Harga Akhir (Rp)')
    ->disabled()
    ->dehydrated(false)
    ->reactive()
    ->formatStateUsing(fn ($state) => number_format((int) $state, 0, ',', '.'))
    ->afterStateUpdated(function ($state, callable $set, Get $get) {
        $stateAll = $get('__all') ?? [];
        $diskon = $stateAll['diskon_persen'] ?? 0;
        $set('estimasi_harga', ReservasiResource::hitungTotalHarga($stateAll, $diskon));
    }),

            DateTimePicker::make('tanggal_dibuat')->label('Tanggal Dibuat')->default(now())->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pemesan')->searchable(),
                Tables\Columns\TextColumn::make('no_telepon')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('judul_kegiatan')->searchable(),
                Tables\Columns\TextColumn::make('waktu_check_in')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('waktu_check_out')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('jumlah_laki')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('jumlah_perempuan')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('tanggal_dibuat')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getSlug(): string
    {
        return 'detailreservasi';
    }
    


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservasis::route('/'),
            'create' => Pages\CreateReservasi::route('/create'),
            'edit' => Pages\EditReservasi::route('/{record}/edit'),
        ];
    }

   public function create(): void
{
    // Ambil data dari form
    $state = $this->form->getState();

    // Debug log untuk memeriksa data yang diterima
    \Log::debug('Form state:', $state);

    // Mulai transaksi untuk menyimpan data
    DB::beginTransaction();

    try {
        // 1. Simpan data di tabel 'reservasi'
        $reservasi = Reservasi::create([
            'nama_pemesan' => $state['nama_pemesan'],
            'no_telepon' => $state['no_telepon'],
            'email' => $state['email'],
            'judul_kegiatan' => $state['judul_kegiatan'],
            'waktu_check_in' => $state['waktu_check_in'],
            'waktu_check_out' => $state['waktu_check_out'],
            'jumlah_laki_laki' => $state['jumlah_laki_laki'],
            'jumlah_perempuan' => $state['jumlah_perempuan'],
            'informasi_tambahan' => $state['informasi_tambahan'],
            'status_reservasi' => $state['status_reservasi'],
            'id_pic_ioc' => $state['id_pic_ioc'],
            'id_pic_utc' => $state['id_pic_utc'],
            'diskon_persen' => $state['diskon_persen'],
            'estimasi_harga' => $state['estimasi_harga'],
            'tanggal_dibuat' => $state['tanggal_dibuat'],
        ]);

        // 2. Simpan data fasilitas yang dipesan ke tabel 'pemesanan_fasilitas'
        $fasilitasSelected = $state['fasilitas_selected'] ?? [];
        $fasilitasJumlah = $state['fasilitas_jumlah'] ?? [];
        
        foreach ($fasilitasSelected as $fasilitasId => $isSelected) {
            if ($isSelected) {
                PemesananFasilitas::create([
                    'reservasi_id' => $reservasi->id,
                    'fasilitas_id' => $fasilitasId,
                    'jumlah' => $fasilitasJumlah[$fasilitasId] ?? 1,  // Default 1 jika tidak ada jumlah yang dipilih
                ]);
            }
        }

        // 3. Simpan data pembayaran ke tabel 'pembayaran'
        Pembayaran::create([
            'reservasi_id' => $reservasi->id,
            'jenis' => 'Tunai',  // Bisa disesuaikan dengan jenis pembayaran yang dipilih
            'bukti_pembayaran' => 'path_to_bukti_pembayaran',  // Simpan path bukti pembayaran jika ada
            'tanggal_pembayaran' => now(),  // Sesuaikan dengan waktu pembayaran
        ]);

        // Commit transaksi jika semua data berhasil disimpan
        DB::commit();

        // Kirim notifikasi sukses
        Notification::make()
            ->title('Reservasi berhasil dibuat!')
            ->success()
            ->send();

        // Redirect ke halaman daftar reservasi setelah berhasil disimpan
        $this->redirect(ReservasiResource::getUrl('index'));
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollBack();
        
        // Kirim notifikasi error
        Notification::make()
            ->title('Terjadi kesalahan saat menyimpan data!')
            ->danger()
            ->send();
    }
}


    protected static function hitungTotalHarga(array $state, $diskonPersen = 0): int
    {
        $selectedIds = collect($state)
            ->filter(fn ($val, $key) => str_starts_with($key, 'fasilitas_selected.') && $val === true)
            ->map(fn ($val, $key) => (int) str_replace('fasilitas_selected.', '', $key))
            ->toArray();

        $fasilitasDipilih = Fasilitas::whereIn('id', $selectedIds)->get();

        $total = 0;
        foreach ($fasilitasDipilih as $fasilitas) {
            $jumlah = $state["fasilitas_jumlah.{$fasilitas->id}"] ?? 0;
            $total += $fasilitas->harga * (int) $jumlah;
        }

        $diskon = (int) $diskonPersen;
        $totalDiskon = $total * ($diskon / 100);
        return (int) round($total - $totalDiskon);
    }
}
