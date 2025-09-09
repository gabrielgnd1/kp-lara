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
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Detail Reservasi';

    public static function form(Form $form): Form
    {
        $recalc = function (Get $get, callable $set) {
            $fSel = (array) ($get('fasilitas_selected') ?? []);
            $fJml = (array) ($get('fasilitas_jumlah') ?? []);
            $aSel = (array) ($get('additional_selected') ?? []);
            $aJml = (array) ($get('additional_jumlah') ?? []);
            $mSel = (array) ($get('menu_makan_selected') ?? []);
            $mJml = (array) ($get('menu_makan_jumlah') ?? []);
            $disk = (int) ($get('diskon') ?? 0);

            $split = static::hitungHariSplit($get('waktu_check_in'), $get('waktu_check_out'));
            $jenis = $get('ubaya_member') ?? 'Internal';

            $total = static::hitungTotalHarga(
                $fSel, $fJml, $aSel, $aJml, $mSel, $mJml,
                $disk,
                (int) $split['weekday'], (int) $split['weekend'],
                $jenis
            );

            $set('harga_akhir', (int) max(0, $total));
        };

        return $form->schema([
            // ====== LETAKKAN PALING ATAS ======
            Hidden::make('form_ready')
                ->default(true)            // tidak perlu cek lagi
                ->dehydrated(false),

            Hidden::make('fasilitas_selected')->default([])->dehydrated(false),
            Hidden::make('fasilitas_jumlah')->default([])->dehydrated(false),
            Hidden::make('additional_selected')->default([])->dehydrated(false),
            Hidden::make('additional_jumlah')->default([])->dehydrated(false),
            Hidden::make('menu_makan_selected')->default([])->dehydrated(false),
            Hidden::make('menu_makan_jumlah')->default([])->dehydrated(false),
            Hidden::make('hari_tipe')
                ->dehydrated(false),

            // ===== Row: Jenis Member (1 baris penuh) =====
            Section::make('')
                ->schema([
                    Radio::make('ubaya_member')
                        ->label('Jenis Member')
                        ->options(['Internal' => 'Internal', 'Eksternal' => 'Eksternal'])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc) {
                            $set('fasilitas_selected', []);
                            $set('fasilitas_jumlah', []);
                            // kalau perlu hitung ulang:
                            if (isset($recalc)) $recalc($get, $set);
                        }),
                ])
                ->columns(1),

            // ===== Row: Nama Pemesan | No Telepon (jejer/side-by-side) =====
            Grid::make([
                'default' => 1,  // satu kolom di layar kecil
                'md'      => 2,  // dua kolom di >= md
            ])
            ->schema([
                TextInput::make('nama_pemesan')
                    ->label('Nama Pemesan')
                    ->required()
                    ->maxLength(100),

                TextInput::make('no_telepon')
                    ->label('No Telepon')
                    ->tel()           // tipe tel (opsional)
                    ->required()
                    ->maxLength(20),
            ]),

            Forms\Components\TextInput::make('email')->label('Email')->email()->required()->maxLength(100),
            Forms\Components\TextInput::make('judul_kegiatan')->label('Judul Kegiatan')->required()->maxLength(100),

            DateTimePicker::make('waktu_check_in')
                ->label('Waktu Check In')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc) {
                    $hari = static::deriveHariTipe($get('waktu_check_in'), $get('waktu_check_out'));
                    $set('hari_tipe', $hari);

                    // reset pilihan fasilitas, karena filter berubah
                    $set('fasilitas_selected', []);
                    $set('fasilitas_jumlah', []);

                    $recalc($get, $set);
                }),

            DateTimePicker::make('waktu_check_out')
                ->label('Waktu Check Out')
                ->required()
                ->rule('after:waktu_check_in')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc) {
                    $hari = static::deriveHariTipe($get('waktu_check_in'), $get('waktu_check_out'));
                    $set('hari_tipe', $hari);

                    // reset pilihan fasilitas, karena filter berubah
                    $set('fasilitas_selected', []);
                    $set('fasilitas_jumlah', []);

                    $recalc($get, $set);
                }),

            TextInput::make('jumlah_laki')->label('Jumlah Laki-laki')->required()->numeric()->minValue(0),
            TextInput::make('jumlah_perempuan')->label('Jumlah Perempuan')->required()->numeric()->minValue(0),

            Textarea::make('informasi_tambahan')->label('Informasi Tambahan')->default('')->columnSpanFull(),

            Hidden::make('status_reservasi')
                ->default(fn () => auth()->user()?->role_id === 5 ? 'ACC' : 'NOT ACC')
                ->required(),
            Hidden::make('status_pembayaran')->default('BARU')->required(),
            Hidden::make('id_pic_ioc')->default(fn () => auth()->user()?->role_id === 5 ? auth()->id() : null),
            Hidden::make('id_pic_utc')->default(fn () => auth()->user()?->role_id === 7 ? auth()->id() : null),

            // ---------- FASILITAS ----------
            Section::make('Fasilitas')
                ->description('Harga otomatis menyesuaikan Weekday/Weekend di rentang tanggal.')
                ->disabled(fn (Get $get) => $get('ubaya_member') === null)
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) use ($recalc) {
                            $jenis = $get('ubaya_member') ?? 'Internal';
                            $groups = static::fasilitasGroupedByNamaForMember($jenis);

                            // gunakan state yang ada
                            $selectedMap = (array) ($get('fasilitas_selected') ?? []);
                            $jumlahMap   = (array) ($get('fasilitas_jumlah') ?? []);

                            return collect($groups)->map(function ($g, $groupKey) use ($recalc, $selectedMap, $jumlahMap) {
                                $label = $g['nama'];
                                return Grid::make(2)->schema([
                                    Checkbox::make("fasilitas_selected.$groupKey")
                                        ->label($label)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc, $groupKey) {
                                            if ($state === true && (int) ($get("fasilitas_jumlah.$groupKey") ?? 0) < 1) {
                                                $set("fasilitas_jumlah.$groupKey", 1);
                                            }
                                            $recalc($get, $set);
                                        })
                                        ->afterStateHydrated(function (\Filament\Forms\Components\Checkbox $c) use ($groupKey, $selectedMap) {
                                            $c->state((bool) ($selectedMap[$groupKey] ?? false));
                                        }),

                                    TextInput::make("fasilitas_jumlah.$groupKey")
                                        ->label('Jumlah')
                                        ->numeric()->minValue(1)->default(1)
                                        ->required(fn ($get) => $get("fasilitas_selected.$groupKey") === true)
                                        ->visible(fn ($get) => $get("fasilitas_selected.$groupKey") === true)
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($get, $set))
                                        ->afterStateHydrated(function (\Filament\Forms\Components\TextInput $c) use ($groupKey, $jumlahMap) {
                                            $c->state((int) ($jumlahMap[$groupKey] ?? 1));
                                        }),
                                ]);
                            })->values()->all();
                        }),
                ])
                ->columns(1),

            // ---------- ADDITIONAL ----------
            Section::make('Additional')
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) use ($recalc) {
                            // state existing (agar bisa di-hydrate)
                            $selectedMap = (array) ($get('additional_selected') ?? []);
                            $jumlahMap   = (array) ($get('additional_jumlah') ?? []);

                            return \App\Models\Additional::query()
                                ->where('status', 'Available')
                                ->get()
                                ->map(function ($a) use ($recalc, $selectedMap, $jumlahMap) {
                                    $id = (string) $a->id;

                                    return Grid::make(2)->schema([
                                        Checkbox::make("additional_selected.$id")
                                            ->label($a->nama)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc, $id) {
                                                if ($state === true && (int) ($get("additional_jumlah.$id") ?? 0) < 1) {
                                                    $set("additional_jumlah.$id", 1);
                                                }
                                                $recalc($get, $set);
                                            })
                                            ->afterStateHydrated(function (\Filament\Forms\Components\Checkbox $c) use ($id, $selectedMap) {
                                                $c->state((bool) ($selectedMap[$id] ?? false));
                                            }),

                                        TextInput::make("additional_jumlah.$id")
                                            ->label('Jumlah')
                                            ->numeric()->minValue(1)->default(1)
                                            ->required(fn ($get) => $get("additional_selected.$id") === true)
                                            ->visible(fn ($get) => $get("additional_selected.$id") === true)
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($get, $set))
                                            ->afterStateHydrated(function (\Filament\Forms\Components\TextInput $c) use ($id, $jumlahMap) {
                                                $c->state((int) ($jumlahMap[$id] ?? 1));
                                            }),
                                    ]);
                                })
                                ->values()
                                ->all();
                        }),
                ])
                ->columns(1),

            // ---------- MENU MAKAN (PAKET MAKANAN) ----------
            Section::make('Menu Makan')
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) use ($recalc) {
                            // state existing (agar bisa di-hydrate)
                            $selectedMap = (array) ($get('menu_makan_selected') ?? []);
                            $jumlahMap   = (array) ($get('menu_makan_jumlah') ?? []);

                            return \App\Models\MenuMakan::query()
                                ->where('status', 'Available')
                                ->get()
                                ->map(function ($m) use ($recalc, $selectedMap, $jumlahMap) {
                                    $id = (string) $m->id;

                                    return Grid::make(2)->schema([
                                        Checkbox::make("menu_makan_selected.$id")
                                            ->label($m->nama)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc, $id) {
                                                if ($state === true && (int) ($get("menu_makan_jumlah.$id") ?? 0) < 1) {
                                                    $set("menu_makan_jumlah.$id", 1);
                                                }
                                                $recalc($get, $set);
                                            })
                                            ->afterStateHydrated(function (\Filament\Forms\Components\Checkbox $c) use ($id, $selectedMap) {
                                                $c->state((bool) ($selectedMap[$id] ?? false));
                                            }),

                                        TextInput::make("menu_makan_jumlah.$id")
                                            ->label('Jumlah')
                                            ->numeric()->minValue(1)->default(1)
                                            ->required(fn ($get) => $get("menu_makan_selected.$id") === true)
                                            ->visible(fn ($get) => $get("menu_makan_selected.$id") === true)
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($get, $set))
                                            ->afterStateHydrated(function (\Filament\Forms\Components\TextInput $c) use ($id, $jumlahMap) {
                                                $c->state((int) ($jumlahMap[$id] ?? 1));
                                            }),
                                    ]);
                                })
                                ->values()
                                ->all();
                        }),
                ])
                ->columns(1),


            TextInput::make('diskon')
                ->label('Diskon (%)')
                ->numeric()
                ->default(0)
                ->minValue(0)
                ->maxValue(100)
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, Get $get) => $recalc($get, $set)),

            TextInput::make('harga_akhir')
                ->label('Harga Akhir (Rp)')
                ->numeric()
                ->readOnly()
                ->dehydrated(true), // simpan ke DB

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
                Tables\Actions\DeleteAction::make(), 
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(), // 👈 bulk delete juga bisa
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
            'edit'  => Pages\EditReservasi::route('/{record}/edit'),
        ];
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

        // Additional (dengan jumlah)
        $aSelected = array_filter($state['additional_selected'] ?? []);
        $aJumlah   = $state['additional_jumlah'] ?? [];
        $aSync = [];
        foreach (array_keys($aSelected) as $aid) {
            $qty = max(1, (int) ($aJumlah[$aid] ?? 1));
            $aSync[(int) $aid] = ['jumlah' => $qty];
        }
        $record->additional()->sync($aSync);

        // Menu Makan (Paket Makanan) — selected + jumlah
        $mSelected = array_filter($state['menu_makan_selected'] ?? []);
        $mJumlah   = $state['menu_makan_jumlah'] ?? [];
        $mSync = [];
        foreach (array_keys($mSelected) as $mid) {
            $qty = max(1, (int) ($mJumlah[$mid] ?? 1));
            $mSync[(int) $mid] = ['jumlah' => $qty];
        }
        $record->menuMakan()->sync($mSync);
    }

    protected static function deriveHariTipe(?string $checkIn, ?string $checkOut): ?string
    {
        if (!$checkIn && !$checkOut) {
            return null;
        }

        // Jika hanya check-in tersedia, pakai hari check-in.
        if ($checkIn && !$checkOut) {
            $d = Carbon::parse($checkIn);
            return ($d->isSaturday() || $d->isSunday()) ? 'Weekend' : 'Weekday';
        }

        // Jika kedua tanggal ada, iterasikan setiap hari dari check-in s/d (check-out - 1 hari)
        try {
            $start = Carbon::parse($checkIn)->startOfDay();
            $end   = Carbon::parse($checkOut)->startOfDay(); // biasanya checkout tidak dihitung hari penuh
            if ($end->lessThanOrEqualTo($start)) {
                // fallback: kalau end <= start, pakai hari start saja
                return ($start->isSaturday() || $start->isSunday()) ? 'Weekend' : 'Weekday';
            }

            foreach (CarbonPeriod::create($start, $end->copy()->subDay()) as $day) {
                if ($day->isSaturday() || $day->isSunday()) {
                    return 'Weekend';
                }
            }
            return 'Weekday';
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected static function hitungHariSplit(?string $checkIn, ?string $checkOut): array
    {
        $weekday = 0; $weekend = 0;

        if (!$checkIn || !$checkOut) {
            return ['weekday' => 1, 'weekend' => 0]; // fallback min 1 hari
        }

        try {
            $start = Carbon::parse($checkIn)->startOfDay();
            $end   = Carbon::parse($checkOut)->startOfDay();

            if ($end->lessThanOrEqualTo($start)) {
                // fallback: 1 hari berdasarkan hari start
                if ($start->isSaturday() || $start->isSunday()) $weekend = 1; else $weekday = 1;
                return compact('weekday', 'weekend');
            }

            foreach (CarbonPeriod::create($start, $end->copy()->subDay()) as $d) {
                if ($d->isSaturday() || $d->isSunday()) $weekend++;
                else $weekday++;
            }
        } catch (\Throwable $e) {
            $weekday = 1; $weekend = 0;
        }

        // pastikan minimal 1
        if ($weekday + $weekend < 1) $weekday = 1;

        return compact('weekday', 'weekend');
    }

    /**
     * Kembalikan map fasilitas “tergabung”:
     * [
     *   <groupKey> => [
     *      'id_list' => [id_weekday, id_weekend], // id-id yang merepresentasikan fasilitas ini
     *      'nama' => 'Aula Besar',
     *      'harga_weekday' => 1000000,
     *      'harga_weekend' => 1200000,
     *   ],
     *   ...
     * ]
     *
     * GroupKey pakai nama (atau kode unik kalau ada kolom 'kode').
     */
    protected static function fasilitasGroupedByNamaForMember(string $jenisUser): array
    {
        $rows = Fasilitas::query()
            ->where('status', 'Available')
            ->where('jenis_user', $jenisUser) // Internal / Eksternal
            ->get();

        $groups = [];
        foreach ($rows as $r) {
            $key = trim(mb_strtolower($r->nama)); // ganti ke $r->kode kalau ada
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'id_list' => [],
                    'nama' => $r->nama,
                    'harga_weekday' => null,
                    'harga_weekend' => null,
                ];
            }
            $groups[$key]['id_list'][] = (int) $r->id;

            if (strcasecmp($r->day, 'Weekday') === 0) {
                $groups[$key]['harga_weekday'] = (int) $r->harga;
            } elseif (strcasecmp($r->day, 'Weekend') === 0) {
                $groups[$key]['harga_weekend'] = (int) $r->harga;
            }
        }
        return $groups;
    }

    protected static function hitungTotalHarga(
        array $fasilitasSelected,
        array $fasilitasJumlah,
        array $additionalSelected,
        array $additionalJumlah,
        array $menuSelected,
        array $menuJumlah,
        int $diskonPersen,
        int $weekdayCount,
        int $weekendCount,
        ?string $jenisUser = null // 'Internal' / 'Eksternal' untuk pricing fasilitas
    ): int {
        // ------- FASILITAS (per-hari, beda harga weekday/weekend) -------
        $totalF = 0;
        if ($jenisUser) {
            $groups = static::fasilitasGroupedByNamaForMember($jenisUser);
            foreach ($fasilitasSelected as $key => $on) {
                if (!$on) continue;
                $qty = max(1, (int) ($fasilitasJumlah[$key] ?? 1));
                $g   = $groups[$key] ?? null;
                if (!$g) continue;

                $hWd = (int) ($g['harga_weekday'] ?? 0);
                $hWe = (int) ($g['harga_weekend'] ?? 0);

                $totalF += $qty * ($weekdayCount * $hWd + $weekendCount * $hWe);
            }
        }

        // ------- ADDITIONAL (anggap per-hari; kalau tidak, ganti $hariTotal=1) -------
        $hariTotal = max(1, $weekdayCount + $weekendCount);
        $aIds = array_map('intval', array_keys(array_filter($additionalSelected)));
        $totalA = 0;
        if ($aIds) {
            $items = Additional::whereIn('id', $aIds)->get()->keyBy('id');
            foreach ($aIds as $id) {
                if (!isset($items[$id])) continue;
                $qty = max(1, (int) ($additionalJumlah[$id] ?? 1));
                $totalA += (int) $items[$id]->harga * $qty * $hariTotal; // ← kalau bukan per-hari: hilangkan * $hariTotal
            }
        }

        // ------- MENU MAKAN (anggap per-hari; kalau tidak, ganti jadi sekali) -------
        $mIds = array_map('intval', array_keys(array_filter($menuSelected)));
        $totalM = 0;
        if ($mIds) {
            $items = MenuMakan::whereIn('id', $mIds)->get()->keyBy('id');
            foreach ($mIds as $id) {
                if (!isset($items[$id])) continue;
                $qty = max(1, (int) ($menuJumlah[$id] ?? 1));
                $totalM += (int) $items[$id]->harga * $qty * $hariTotal; // ← kalau bukan per-hari: hilangkan * $hariTotal
            }
        }

        $subtotal = $totalF + $totalA + $totalM;

        $diskon = max(0, min(100, (int) $diskonPersen));
        $subtotal -= (int) round($subtotal * ($diskon / 100));

        return max(0, (int) $subtotal);
    }
}
