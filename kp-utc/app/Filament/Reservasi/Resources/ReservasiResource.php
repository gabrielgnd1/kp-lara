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
        // Helper: hitung & set estimasi harga dari state saat ini
        $recalc = function (callable $set, Get $get) {
            $diskon = (int) ($get('diskon_persen') ?? 0);
            $set('estimasi_harga', self::hitungTotalHargaFromGet($get, $diskon));
        };

        return $form->schema([
            Radio::make('ubaya_member')
                ->label('INTERNAL / EKSTERNAL')
                ->options(['Ya' => 'Ya', 'Tidak' => 'Tidak'])
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),

            Radio::make('hari_tipe')
                ->label('Jenis Hari')
                ->options(['Weekday' => 'Weekday', 'Weekend' => 'Weekend'])
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),

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
                ->disabled(fn (Get $get) => $get('ubaya_member') === null || $get('hari_tipe') === null)
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) use ($recalc) {
                            return Fasilitas::query()
                                ->where('status', 'Available')
                                ->when($get('ubaya_member'), fn ($query, $val) =>
                                    $query->where('jenis_user', $val === 'Ya' ? 'Internal' : 'Eksternal'))
                                ->when($get('hari_tipe'), fn ($query, $val) =>
                                    $query->where('day', $val))
                                ->get()
                                ->unique('nama')
                                ->map(function ($fasilitas) use ($recalc) {
                                    return Grid::make(2)->schema([
                                        Checkbox::make("fasilitas_selected.{$fasilitas->id}")
                                            ->label($fasilitas->nama)
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),

                                        TextInput::make("fasilitas_jumlah.{$fasilitas->id}")
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->default(1)
                                            ->required(fn ($get) => $get("fasilitas_selected.{$fasilitas->id}") === true)
                                            ->visible(fn ($get) => $get("fasilitas_selected.{$fasilitas->id}") === true)
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),
                                    ]);
                                })->toArray();
                        }),
                ])
                ->columns(1),

            Fieldset::make('Additional')
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) use ($recalc) {
                            return \App\Models\Additional::query()
                                ->where('status', 'Available')
                                ->get()
                                ->map(function ($additional) use ($recalc) {
                                    return Grid::make(2)->schema([
                                        Checkbox::make("additional_selected.{$additional->id}")
                                            ->label($additional->nama)
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),

                                        TextInput::make("additional_jumlah.{$additional->id}")
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->default(1)
                                            ->required(fn ($get) => $get("additional_selected.{$additional->id}") === true)
                                            ->visible(fn ($get) => $get("additional_selected.{$additional->id}") === true)
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),
                                    ]);
                                })->toArray();
                        }),
                ])
                ->columns(1),

            Fieldset::make('Menu Makan')
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) use ($recalc) {
                            return \App\Models\MenuMakan::query()
                                ->where('status', 'Available')
                                ->get()
                                ->map(function ($menuMakan) use ($recalc) {
                                    return Grid::make(2)->schema([
                                        Checkbox::make("menu_makan_selected.{$menuMakan->id}")
                                            ->label($menuMakan->nama)
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),

                                        TextInput::make("menu_makan_jumlah.{$menuMakan->id}")
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->default(1)
                                            ->required(fn ($get) => $get("menu_makan_selected.{$menuMakan->id}") === true)
                                            ->visible(fn ($get) => $get("menu_makan_selected.{$menuMakan->id}") === true)
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),
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
                ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),

            TextInput::make('estimasi_harga')
                ->label('Estimasi Harga Akhir (Rp)')
                ->default(0)
                ->disabled()
                ->dehydrated(false)
                ->reactive()
                ->afterStateHydrated(fn (callable $set, Get $get) => $recalc($set, $get))
                ->formatStateUsing(fn ($state) => number_format((int) $state, 0, ',', '.')),

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
        $state = $this->form->getState();

        DB::beginTransaction();

        try {
            $reservasi = Reservasi::create([
                'nama_pemesan'      => $state['nama_pemesan'],
                'no_telepon'        => $state['no_telepon'],
                'email'             => $state['email'],
                'judul_kegiatan'    => $state['judul_kegiatan'],
                'waktu_check_in'    => $state['waktu_check_in'],
                'waktu_check_out'   => $state['waktu_check_out'],
                'jumlah_laki_laki'  => $state['jumlah_laki_laki'],
                'jumlah_perempuan'  => $state['jumlah_perempuan'],
                'informasi_tambahan'=> $state['informasi_tambahan'],
                'status_reservasi'  => $state['status_reservasi'],
                'id_pic_ioc'        => $state['id_pic_ioc'],
                'id_pic_utc'        => $state['id_pic_utc'],
                'diskon_persen'     => $state['diskon_persen'] ?? 0,
                'estimasi_harga'    => $state['estimasi_harga'] ?? 0,
                'tanggal_dibuat'    => $state['tanggal_dibuat'],
            ]);

            DB::commit();

            Notification::make()
                ->title('Reservasi berhasil dibuat!')
                ->success()
                ->send();

            $this->redirect(ReservasiResource::getUrl('index'));
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Terjadi kesalahan saat menyimpan data!')
                ->danger()
                ->send();

            throw $e;
        }
    }

    /**
     * Versi FIX: hitung total langsung dari $get (tanpa __all).
     */
    protected static function hitungTotalHargaFromGet(Get $get, int $diskonPersen = 0): int
    {
        // --- Fasilitas
        $fasilitasSelected = array_filter($get('fasilitas_selected') ?? []); // [id => true/false]
        $fasilitasIds = array_map('intval', array_keys($fasilitasSelected));
        $fasilitasJumlah = $get('fasilitas_jumlah') ?? [];                   // [id => jumlah]

        $totalFasilitas = 0;
        if (!empty($fasilitasIds)) {
            $items = Fasilitas::whereIn('id', $fasilitasIds)->get()->keyBy('id');
            foreach ($fasilitasIds as $id) {
                if (!isset($items[$id])) continue;
                $jumlah = (int) ($fasilitasJumlah[$id] ?? 0);
                $totalFasilitas += (int) $items[$id]->harga * $jumlah;
            }
        }

        // --- Additional
        $additionalSelected = array_filter($get('additional_selected') ?? []);
        $additionalIds = array_map('intval', array_keys($additionalSelected));
        $additionalJumlah = $get('additional_jumlah') ?? [];

        $totalAdditional = 0;
        if (!empty($additionalIds)) {
            $items = \App\Models\Additional::whereIn('id', $additionalIds)->get()->keyBy('id');
            foreach ($additionalIds as $id) {
                if (!isset($items[$id])) continue;
                $jumlah = (int) ($additionalJumlah[$id] ?? 0);
                $totalAdditional += (int) $items[$id]->harga * $jumlah;
            }
        }

        // --- Menu Makan
        $menuSelected = array_filter($get('menu_makan_selected') ?? []);
        $menuIds = array_map('intval', array_keys($menuSelected));
        $menuJumlah = $get('menu_makan_jumlah') ?? [];

        $totalMenu = 0;
        if (!empty($menuIds)) {
            $items = \App\Models\MenuMakan::whereIn('id', $menuIds)->get()->keyBy('id');
            foreach ($menuIds as $id) {
                if (!isset($items[$id])) continue;
                $jumlah = (int) ($menuJumlah[$id] ?? 0);
                $totalMenu += (int) $items[$id]->harga * $jumlah;
            }
        }

        $total = $totalFasilitas + $totalAdditional + $totalMenu;

        $diskon = max(0, min(100, (int) $diskonPersen));
        $total -= (int) round($total * ($diskon / 100));

        return max(0, (int) $total);
    }
}
