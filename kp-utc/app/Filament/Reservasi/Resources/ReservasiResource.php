<?php

namespace App\Filament\Reservasi\Resources;

use App\Filament\Reservasi\Resources\ReservasiResource\Pages;
use App\Models\Reservasi;
use App\Models\Fasilitas;
use App\Models\Additional;
use App\Models\MenuMakan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;

class ReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Detail Reservasi';

    public static function form(Form $form): Form
    {
        // Recalc estimasi
        $recalc = function (callable $set, Get $get) {
            $diskon = (int) ($get('diskon_persen') ?? 0);
            $set('estimasi_harga', self::hitungTotalHargaFromGet($get, $diskon));
        };

        return $form->schema([
            Radio::make('ubaya_member')
                ->label('Jenis Member')
                ->options(['Internal' => 'Internal', 'Eksternal' => 'Eksternal'])
                ->required()
                ->reactive()
                ->live()
                ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc) {
                    $set('fasilitas_selected', []);
                    $set('fasilitas_jumlah', []);
                    $recalc($set, $get);
                }),

            Radio::make('hari_tipe')
                ->label('Jenis Hari')
                ->options(['Weekday' => 'Weekday', 'Weekend' => 'Weekend'])
                ->required()
                ->reactive()
                ->live()
                ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc) {
                    $set('fasilitas_selected', []);
                    $set('fasilitas_jumlah', []);
                    $recalc($set, $get);
                }),

            Forms\Components\TextInput::make('nama_pemesan')->label('Nama Pemesan')->required()->maxLength(100),
            Forms\Components\TextInput::make('no_telepon')->label('No Telepon')->required()->maxLength(20),
            Forms\Components\TextInput::make('email')->label('Email')->email()->required()->maxLength(100),
            Forms\Components\TextInput::make('judul_kegiatan')->label('Judul Kegiatan')->required()->maxLength(100),

            DateTimePicker::make('waktu_check_in')->label('Waktu Check In')->required(),
            DateTimePicker::make('waktu_check_out')->label('Waktu Check Out')->required()->rule('after:waktu_check_in'),

            TextInput::make('jumlah_laki')->label('Jumlah Laki-laki')->required()->numeric()->minValue(0),
            TextInput::make('jumlah_perempuan')->label('Jumlah Perempuan')->required()->numeric()->minValue(0),

            Textarea::make('informasi_tambahan')->label('Informasi Tambahan')->default('')->columnSpanFull(),

            Hidden::make('status_reservasi')
                ->default(fn () => auth()->user()?->role_id === 5 ? 'ACC' : 'NOT ACC')
                ->required(),
            Hidden::make('status_pembayaran')->default('BARU')->required(),
            Hidden::make('id_pic_ioc')->default(fn () => auth()->user()?->role_id === 5 ? auth()->id() : null),
            Hidden::make('id_pic_utc')->default(fn () => auth()->user()?->role_id === 7 ? auth()->id() : null),

            // MIRROR ARRAYS (so they're present in form state on create)
            Hidden::make('fasilitas_selected')->default([])->dehydrated(true),
            Hidden::make('fasilitas_jumlah')->default([])->dehydrated(true),
            Hidden::make('additional_selected')->default([])->dehydrated(true),
            Hidden::make('additional_jumlah')->default([])->dehydrated(true),
            Hidden::make('menu_makan_selected')->default([])->dehydrated(true),
            Hidden::make('menu_makan_jumlah')->default([])->dehydrated(true),

            // -------- FASILITAS --------
            Section::make('Fasilitas yang Dipesan')
                ->description('Pilih Jenis Member & Jenis Hari untuk menampilkan fasilitas.')
                ->disabled(fn (Get $get) => $get('ubaya_member') === null || $get('hari_tipe') === null)
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) use ($recalc) {
                            $selectedMap = (array) ($get('fasilitas_selected') ?? []);
                            $jumlahMap   = (array) ($get('fasilitas_jumlah') ?? []);

                            return Fasilitas::query()
                                ->where('status', 'Available')
                                ->when($get('ubaya_member'), fn ($q, $v) => $q->where('jenis_user', $v === 'Internal' ? 'Internal' : 'Eksternal'))
                                ->when($get('hari_tipe'), fn ($q, $v) => $q->where('day', $v))
                                ->get()
                                ->map(function ($f) use ($recalc, $selectedMap, $jumlahMap) {
                                    $id = (string) $f->id;

                                    return Grid::make(2)->schema([
                                        Checkbox::make("fasilitas_selected.$id")
                                            ->label($f->nama)
                                            ->reactive()
                                            ->live()
                                            ->afterStateHydrated(function (\Filament\Forms\Components\Checkbox $c) use ($id, $selectedMap) {
                                                $c->state((bool) ($selectedMap[$id] ?? false));
                                            })
                                            ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc, $id) {
                                                if ($state === true && (int) ($get("fasilitas_jumlah.$id") ?? 0) < 1) {
                                                    $set("fasilitas_jumlah.$id", 1);
                                                }
                                                $recalc($set, $get);
                                            }),

                                        TextInput::make("fasilitas_jumlah.$id")
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->minValue(1)
                                            ->default(1)
                                            ->required(fn ($get) => $get("fasilitas_selected.$id") === true)
                                            ->visible(fn ($get) => $get("fasilitas_selected.$id") === true)
                                            ->reactive()
                                            ->live()
                                            ->afterStateHydrated(function (\Filament\Forms\Components\TextInput $c) use ($id, $jumlahMap) {
                                                $c->state((int) ($jumlahMap[$id] ?? 1));
                                            })
                                            ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),
                                    ]);
                                })->toArray();
                        }),
                ])
                ->columns(1),

            // -------- ADDITIONAL --------
            Section::make('Additional')
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) use ($recalc) {
                            $selectedMap = (array) ($get('additional_selected') ?? []);
                            $jumlahMap   = (array) ($get('additional_jumlah') ?? []);

                            return Additional::query()
                                ->where('status', 'Available')
                                ->get()
                                ->map(function ($a) use ($recalc, $selectedMap, $jumlahMap) {
                                    $id = (string) $a->id;

                                    return Grid::make(2)->schema([
                                        Checkbox::make("additional_selected.$id")
                                            ->label($a->nama)
                                            ->reactive()
                                            ->live()
                                            ->afterStateHydrated(function (\Filament\Forms\Components\Checkbox $c) use ($id, $selectedMap) {
                                                $c->state((bool) ($selectedMap[$id] ?? false));
                                            })
                                            ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc, $id) {
                                                if ($state === true && (int) ($get("additional_jumlah.$id") ?? 0) < 1) {
                                                    $set("additional_jumlah.$id", 1);
                                                }
                                                $recalc($set, $get);
                                            }),

                                        TextInput::make("additional_jumlah.$id")
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->minValue(1)
                                            ->default(1)
                                            ->required(fn ($get) => $get("additional_selected.$id") === true)
                                            ->visible(fn ($get) => $get("additional_selected.$id") === true)
                                            ->reactive()
                                            ->live()
                                            ->afterStateHydrated(function (\Filament\Forms\Components\TextInput $c) use ($id, $jumlahMap) {
                                                $c->state((int) ($jumlahMap[$id] ?? 1));
                                            })
                                            ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($set, $get)),
                                    ]);
                                })->toArray();
                        }),
                ])
                ->columns(1),

            // -------- MENU MAKAN --------
            Section::make('Menu Makan')
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) use ($recalc) {
                            $selectedMap = (array) ($get('menu_makan_selected') ?? []);
                            $jumlahMap   = (array) ($get('menu_makan_jumlah') ?? []);

                            return MenuMakan::query()
                                ->where('status', 'Available')
                                ->get()
                                ->map(function ($m) use ($recalc, $selectedMap, $jumlahMap) {
                                    $id = (string) $m->id;

                                    return Grid::make(2)->schema([
                                        Checkbox::make("menu_makan_selected.$id")
                                            ->label($m->nama)
                                            ->reactive()
                                            ->live()
                                            ->afterStateHydrated(function (\Filament\Forms\Components\Checkbox $c) use ($id, $selectedMap) {
                                                $c->state((bool) ($selectedMap[$id] ?? false));
                                            })
                                            ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc, $id) {
                                                if ($state === true && (int) ($get("menu_makan_jumlah.$id") ?? 0) < 1) {
                                                    $set("menu_makan_jumlah.$id", 1);
                                                }
                                                $recalc($set, $get);
                                            }),

                                        TextInput::make("menu_makan_jumlah.$id")
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->minValue(1)
                                            ->default(1)
                                            ->required(fn ($get) => $get("menu_makan_selected.$id") === true)
                                            ->visible(fn ($get) => $get("menu_makan_selected.$id") === true)
                                            ->reactive()
                                            ->live()
                                            ->afterStateHydrated(function (\Filament\Forms\Components\TextInput $c) use ($id, $jumlahMap) {
                                                $c->state((int) ($jumlahMap[$id] ?? 1));
                                            })
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
                Tables\Columns\TextColumn::make('status_reservasi')->badge(),
                Tables\Columns\TextColumn::make('status_pembayaran')->badge(),
                Tables\Columns\TextColumn::make('tanggal_dibuat')->dateTime()->sortable(),
            ])
            ->paginated()
            ->striped(false)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
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
            'edit'  => Pages\EditReservasi::route('/{record}/edit'),
        ];
    }

    // ---------- helpers ----------

    protected static function hitungTotalHargaFromGet(Get $get, int $diskonPersen = 0): int
    {
        $fSelected = array_filter($get('fasilitas_selected') ?? []);
        $fIds     = array_map('intval', array_keys($fSelected));
        $fJumlah  = $get('fasilitas_jumlah') ?? [];

        $totalF = 0;
        if (!empty($fIds)) {
            $items = Fasilitas::whereIn('id', $fIds)->get()->keyBy('id');
            foreach ($fIds as $id) {
                if (!isset($items[$id])) continue;
                $qty = max(1, (int) ($fJumlah[$id] ?? 1));
                $totalF += (int) $items[$id]->harga * $qty;
            }
        }

        $aSelected = array_filter($get('additional_selected') ?? []);
        $aIds     = array_map('intval', array_keys($aSelected));
        $aJumlah  = $get('additional_jumlah') ?? [];
        $totalA = 0;
        if (!empty($aIds)) {
            $items = Additional::whereIn('id', $aIds)->get()->keyBy('id');
            foreach ($aIds as $id) {
                if (!isset($items[$id])) continue;
                $qty = max(1, (int) ($aJumlah[$id] ?? 1));
                $totalA += (int) $items[$id]->harga * $qty;
            }
        }

        $mSelected = array_filter($get('menu_makan_selected') ?? []);
        $mIds     = array_map('intval', array_keys($mSelected));
        $mJumlah  = $get('menu_makan_jumlah') ?? [];
        $totalM = 0;
        if (!empty($mIds)) {
            $items = MenuMakan::whereIn('id', $mIds)->get()->keyBy('id');
            foreach ($mIds as $id) {
                if (!isset($items[$id])) continue;
                $qty = max(1, (int) ($mJumlah[$id] ?? 1));
                $totalM += (int) $items[$id]->harga * $qty;
            }
        }

        $total = $totalF + $totalA + $totalM;
        $diskon = max(0, min(100, (int) $diskonPersen));
        $total -= (int) round($total * ($diskon / 100));

        return max(0, (int) $total);
    }

    public static function syncPivotsFromFormState(\App\Models\Reservasi $record, array $state): void
    {
        // Fasilitas
        $fSelected = array_filter($state['fasilitas_selected'] ?? []);
        $fJumlah   = $state['fasilitas_jumlah'] ?? [];
        $fSync = [];
        foreach (array_keys($fSelected) as $fid) {
            $qty = max(1, (int) ($fJumlah[$fid] ?? 1));
            $fSync[(int) $fid] = ['jumlah' => $qty];
        }
        $record->fasilitas()->sync($fSync);

        // Additional (if you store jumlah too; if not, convert to simple array)
        $aSelected = array_filter($state['additional_selected'] ?? []);
        $aJumlah   = $state['additional_jumlah'] ?? [];
        $aSync = [];
        foreach (array_keys($aSelected) as $aid) {
            $qty = max(1, (int) ($aJumlah[$aid] ?? 1));
            $aSync[(int) $aid] = ['jumlah' => $qty];
        }
        $record->additional()->sync($aSync);

        // Menu Makan
        $mSelected = array_filter($state['menu_makan_selected'] ?? []);
        $mJumlah   = $state['menu_makan_jumlah'] ?? [];
        $mSync = [];
        foreach (array_keys($mSelected) as $mid) {
            $qty = max(1, (int) ($mJumlah[$mid] ?? 1));
            $mSync[(int) $mid] = ['jumlah' => $qty];
        }
        $record->menuMakan()->sync($mSync);
    }
}
