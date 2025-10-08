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
use Filament\Forms\Set;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Detail Reservasi';
    protected static array $bookedDateCache = [];

    public static function getModelLabel(): string
    {
        return 'Reservasi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Reservasi';
    }

    protected static function configurePdfOptions()
    {
        return [
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'isPhpEnabled' => true,
            'chroot' => public_path(),
            'encoding' => 'UTF-8',
            'enable_html5_parser' => true,
            'enable_remote' => true,
            'font_cache' => storage_path('fonts'),
            'enable_php' => true,
            'enable_javascript' => true,
            'default_paper_size' => 'a4',
            'default_font_size' => 12,
            'dpi' => 96,
            'enable_font_subsetting' => true
        ];
    }

    protected static function sanitizeData($data)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = static::sanitizeData($v);
            }
            return $data;
        }

        if (is_string($data)) {
            if (!mb_check_encoding($data, 'UTF-8')) {
                $enc = mb_detect_encoding($data, ['UTF-8', 'Windows-1252', 'ISO-8859-1'], true) ?: 'Windows-1252';
                $data = mb_convert_encoding($data, 'UTF-8', $enc);
            }
            // buang byte terlarang untuk JSON/HTML
            $data = @iconv('UTF-8', 'UTF-8//IGNORE', $data);
            $data = preg_replace('/[^\x09\x0A\x0D\x20-\x7E\x{A0}-\x{10FFFF}]/u', '', $data);
            return $data;
        }
        return $data;
    }

    protected static function sanitizeHtml(string $html): string
    {
        if (!mb_check_encoding($html, 'UTF-8')) {
            $html = mb_convert_encoding($html, 'UTF-8', 'auto');
        }
        $html = @iconv('UTF-8', 'UTF-8//IGNORE', $html);
        // dompdf nyaman dengan HTML-ENTITIES juga
        return mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
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
                $get('waktu_check_out')
            );
            $set('harga_akhir', (int) max(0, $total));
        };

        return $form->schema([
            // ====== LETAKKAN PALING ATAS ======
            Hidden::make('form_ready')
                ->default(true)
                ->dehydrated(false),

            Hidden::make('fasilitas_selected')->default([])->dehydrated(false),
            Hidden::make('fasilitas_mulai')->default([])->dehydrated(false),
            Hidden::make('fasilitas_selesai')->default([])->dehydrated(false),
            Hidden::make('additional_selected')->default([])->dehydrated(false),
            Hidden::make('additional_mulai')->default([])->dehydrated(false),
            Hidden::make('additional_selesai')->default([])->dehydrated(false),
            Hidden::make('menu_makan_selected')->default([])->dehydrated(false),
            Hidden::make('menu_makan_jumlah')->default([])->dehydrated(false),
            Hidden::make('hari_tipe')->dehydrated(false),

            // ===== Row: Jenis Member =====
            Section::make('')
                ->schema([
                    Radio::make('jenis_member')
                        ->label('Jenis Member')
                        ->options(['Internal' => 'Internal', 'Eksternal' => 'Eksternal'])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc) {
                            $set('fasilitas_selected', []);
                            $recalc($get, $set);
                        })
                        ->live()
                ])
                ->columns(1),

            // ===== Row: Nama Pemesan | No Telepon =====
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

            Forms\Components\TextInput::make('email')->label('Email')->email()->required()->maxLength(100),
            Forms\Components\TextInput::make('judul_kegiatan')->label('Judul Kegiatan')->required()->maxLength(100),

            DateTimePicker::make('waktu_check_in')
                ->label('Waktu Check In')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, Get $get) use ($recalc) {
                    $hari = static::deriveHariTipe($get('waktu_check_in'), $get('waktu_check_out'));
                    $set('hari_tipe', $hari);
                    $set('fasilitas_selected', []);
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
                    $set('fasilitas_selected', []);
                    $recalc($get, $set);
                }),

            TextInput::make('jumlah_laki')->label('Jumlah Laki-laki')->required()->numeric()->minValue(0),
            TextInput::make('jumlah_perempuan')->label('Jumlah Perempuan')->required()->numeric()->minValue(0),

            Textarea::make('informasi_tambahan')->label('Informasi Tambahan')->default('')->columnSpanFull(),

            Hidden::make('status_reservasi')
                ->default(fn() => (Auth::check() && Auth::user()?->role_id === 5) ? 'ACC' : 'NOT ACC')
                ->required(),
            Hidden::make('status_pembayaran')->default('BARU')->required(),
            Hidden::make('id_pic_ioc')->default(fn() => (Auth::check() && Auth::user()?->role_id === 5) ? Auth::id() : null),
            Hidden::make('id_pic_utc')->default(fn() => (Auth::check() && Auth::user()?->role_id === 7) ? Auth::id() : null),

            // ---------- FASILITAS ----------
            Section::make('Fasilitas')
                ->description('Harga otomatis menyesuaikan Weekday/Weekend di rentang tanggal.')
                ->disabled(false)
                ->live()
                ->schema([
                    Group::make()
                        ->live()
                        ->schema(function (Get $get) use ($recalc) {
                            $jenis = $get('jenis_member') ?? 'Internal';
                            $groups = static::fasilitasGroupedByNamaForMember($jenis);

                            $selectedMap = (array) ($get('fasilitas_selected') ?? []);

                            return collect($groups)->map(function ($g, $groupKey) use ($recalc, $selectedMap) {
                                $label = $g['nama'];
                                return Grid::make(3)->schema([
                                    Checkbox::make("fasilitas_selected.$groupKey")
                                        ->label($label)
                                        ->reactive()
                                        ->afterStateHydrated(function (\Filament\Forms\Components\Checkbox $c) use ($groupKey, $selectedMap) {
                                            $c->state((bool) ($selectedMap[$groupKey] ?? false));
                                        })
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) use ($recalc, $groupKey) {
                                            if ($state === true) {
                                                $set("fasilitas_mulai.$groupKey", $get('waktu_check_in'));
                                                $set("fasilitas_selesai.$groupKey", $get('waktu_check_out'));
                                            }
                                            $recalc($get, $set);
                                        }),

                                    DateTimePicker::make("fasilitas_mulai.$groupKey")
                                        ->label('Mulai')
                                        ->native(false) // pastikan pakai Flatpickr agar disabledDates berfungsi
                                        ->minDate(fn(Get $get) => $get('waktu_check_in'))
                                        ->maxDate(fn(Get $get) => $get('waktu_check_out'))
                                        ->disabledDates(fn(Get $get) => static::disabledDatesForGroup($groupKey, $get))
                                        ->required(fn(Get $get) => $get("fasilitas_selected.$groupKey") === true)
                                        ->visible(fn(Get $get) => $get("fasilitas_selected.$groupKey") === true)
                                        ->reactive()
                                        ->rule(function (Get $get) use ($groupKey) {
                                            return function (string $attribute, $value, $fail) use ($get, $groupKey) {
                                                $blocked = \App\Filament\Reservasi\Resources\ReservasiResource::disabledDatesForGroup($groupKey, $get);
                                                if ($value) {
                                                    $d = \Carbon\Carbon::parse($value)->format('Y-m-d');
                                                    if (in_array($d, $blocked, true)) {
                                                        $fail('Tanggal tersebut sudah dibooking.');
                                                    }
                                                }
                                                $checkIn = $get('waktu_check_in');
                                                if ($checkIn && $value < $checkIn)
                                                    $fail('Tanggal mulai tidak boleh sebelum check-in.');
                                            };
                                        })
                                        ->afterStateUpdated(fn($state, \Filament\Forms\Set $set, Get $get) => $recalc($get, $set)),

                                    DateTimePicker::make("fasilitas_selesai.$groupKey")
                                        ->label('Selesai')
                                        ->native(false)
                                        ->minDate(fn(Get $get) => $get('waktu_check_in'))
                                        ->maxDate(fn(Get $get) => $get('waktu_check_out'))
                                        ->disabledDates(fn(Get $get) => static::disabledDatesForGroup($groupKey, $get))
                                        ->required(fn(Get $get) => $get("fasilitas_selected.$groupKey") === true)
                                        ->visible(fn(Get $get) => $get("fasilitas_selected.$groupKey") === true)
                                        ->reactive()
                                        ->rule(function (Get $get) use ($groupKey) {
                                            return function (string $attribute, $value, $fail) use ($get, $groupKey) {
                                                $blocked = \App\Filament\Reservasi\Resources\ReservasiResource::disabledDatesForGroup($groupKey, $get);
                                                if ($value) {
                                                    $d = \Carbon\Carbon::parse($value)->format('Y-m-d');
                                                    if (in_array($d, $blocked, true)) {
                                                        $fail('Tanggal tersebut sudah dibooking.');
                                                    }
                                                }
                                                $checkOut = $get('waktu_check_out');
                                                $mulai = $get("fasilitas_mulai.$groupKey");
                                                if ($checkOut && $value > $checkOut)
                                                    $fail('Tanggal selesai tidak boleh setelah check-out.');
                                                if ($mulai && $value <= $mulai)
                                                    $fail('Tanggal selesai harus setelah tanggal mulai.');
                                            };
                                        })
                                        ->afterStateUpdated(fn($state, \Filament\Forms\Set $set, Get $get) => $recalc($get, $set)),
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
                            $selectedMap = (array) ($get('additional_selected') ?? []);

                            return \App\Models\Additional::query()
                                ->where('status', 'Available')
                                ->get()
                                ->map(function ($a) use ($recalc, $selectedMap) {
                                    $id = (string) $a->id;

                                    return Grid::make(3)->schema([
                                        Checkbox::make("additional_selected.$id")
                                            ->label($a->nama)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) use ($recalc, $id) {
                                                if ($state === true) {
                                                    $set("additional_mulai.$id", $get('waktu_check_in'));
                                                    $set("additional_selesai.$id", $get('waktu_check_out'));
                                                }
                                                $recalc($get, $set);
                                            }),

                                        DateTimePicker::make("additional_mulai.$id")
                                            ->label('Mulai')
                                            ->minDate(fn(Get $get) => $get('waktu_check_in'))
                                            ->maxDate(fn(Get $get) => $get('waktu_check_out'))
                                            ->required(fn(Get $get) => $get("additional_selected.$id") === true)
                                            ->visible(fn(Get $get) => $get("additional_selected.$id") === true)
                                            ->reactive()
                                            ->rule(function (Get $get) {
                                                return function (string $attribute, $value, $fail) use ($get) {
                                                    $checkIn = $get('waktu_check_in');
                                                    if ($checkIn && $value < $checkIn)
                                                        $fail('Tanggal mulai tidak boleh sebelum check-in.');
                                                };
                                            })
                                            ->afterStateUpdated(fn($state, Set $set, Get $get) => $recalc($get, $set)),

                                        DateTimePicker::make("additional_selesai.$id")
                                            ->label('Selesai')
                                            ->minDate(fn(Get $get) => $get('waktu_check_in'))
                                            ->maxDate(fn(Get $get) => $get('waktu_check_out'))
                                            ->required(fn(Get $get) => $get("additional_selected.$id") === true)
                                            ->visible(fn(Get $get) => $get("additional_selected.$id") === true)
                                            ->reactive()
                                            ->rule(function (Get $get) use ($id) {
                                                return function (string $attribute, $value, $fail) use ($get, $id) {
                                                    $checkOut = $get('waktu_check_out');
                                                    $mulai = $get("additional_mulai.$id");
                                                    if ($checkOut && $value > $checkOut)
                                                        $fail('Tanggal selesai tidak boleh setelah check-out.');
                                                    if ($mulai && $value <= $mulai)
                                                        $fail('Tanggal selesai harus setelah tanggal mulai.');
                                                };
                                            })
                                            ->afterStateUpdated(fn($state, Set $set, Get $get) => $recalc($get, $set)),
                                    ]);

                                })
                                ->values()
                                ->all();
                        }),
                ])
                ->columns(1),

            // ---------- MENU MAKAN ----------
            Section::make('Menu Makan')
                ->schema([
                    Group::make()
                        ->schema(function (Get $get) use ($recalc) {
                            $selectedMap = (array) ($get('menu_makan_selected') ?? []);
                            $jumlahMap = (array) ($get('menu_makan_jumlah') ?? []);

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
                                            ->required(fn($get) => $get("menu_makan_selected.$id") === true)
                                            ->visible(fn($get) => $get("menu_makan_selected.$id") === true)
                                            ->reactive()
                                            ->afterStateUpdated(fn($state, callable $set, Get $get) => $recalc($get, $set))
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
                ->afterStateUpdated(fn($state, callable $set, Get $get) => $recalc($get, $set)),

            TextInput::make('harga_akhir')
                ->label('Harga Akhir (Rp)')
                ->numeric()
                ->readOnly()
                ->dehydrated(true),

            DateTimePicker::make('tanggal_dibuat')->label('Tanggal Dibuat')->default(now())->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pemesan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu_check_in')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_check_out')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_laki')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_perempuan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_reservasi')
                    ->badge(),
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->badge(),
                Tables\Columns\TextColumn::make('tanggal_dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('print_pdf')
                    ->label('Print to PDF')
                    ->action(function (Reservasi $record) {
                        $data = static::sanitizeData($record->fresh()->toArray());

                        // Render Blade → paksa UTF-8 → loadHTML
                        $html = view('reservasi.pdf', ['reservasi' => $data])->render();
                        $html = static::sanitizeHtml($html);

                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
                            ->setPaper('a4')
                            ->setOptions(static::configurePdfOptions());

                        return response($pdf->output(), 200, [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'attachment; filename=reservasi-' . $record->id . '.pdf',
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('export')
                    ->label('Export to PDF')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->default(now()->subMonth()),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->default(now()),
                    ])
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data) {
                        $startDate = $data['start_date'] ?? null;
                        $endDate = $data['end_date'] ?? null;

                        if ($startDate && $endDate) {
                            $records = $records->filter(function ($record) use ($startDate, $endDate) {
                                $checkIn = \Carbon\Carbon::parse($record->waktu_check_in)->startOfDay();
                                return $checkIn->between($startDate, $endDate);
                            });
                        }

                        if ($records->isEmpty()) {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title('No Records Found')
                                ->body('No reservations found in the selected date range.')
                                ->send();
                            return;
                        }

                        $recordsArray = $records->sortBy('waktu_check_in')->map(function ($record) {
                            return static::sanitizeData($record->toArray());
                        })->toArray();

                        $viewData = [
                            'reservasi_list' => $recordsArray,
                            'date_range' => [
                                'start' => $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : 'All',
                                'end' => $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : 'All'
                            ]
                        ];

                        $pdf = PDF::loadView('reservasi.pdf', $viewData)
                            ->setPaper('a4')
                            ->setOptions(static::configurePdfOptions());

                        return response($pdf->output())
                            ->header('Content-Type', 'application/pdf')
                            ->header('Content-Disposition', 'attachment; filename="reservasi-list-' . now()->format('Y-m-d') . '.pdf"');
                    })
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
            // Tambahkan ini kalau kamu pakai halaman create:
            // 'create' => Pages\CreateReservasi::route('/create'),
            'edit' => Pages\EditReservasi::route('/{record}/edit'),
        ];
    }

    public static function syncPivotsFromFormState(Reservasi $record, array $state): void
    {
        // -------- FASILITAS --------
        $fSelected = array_filter($state['fasilitas_selected'] ?? []);
        $fMulai = $state['fasilitas_mulai'] ?? [];
        $fSelesai = $state['fasilitas_selesai'] ?? [];
        $fSync = [];

        $jenis = $state['jenis_member'] ?? 'Internal';
        $groups = static::fasilitasGroupedByNamaForMember($jenis);

        foreach (array_keys($fSelected) as $groupKey) {
            if (!isset($groups[$groupKey]))
                continue;

            $fid = $groups[$groupKey]['id_canonical'] ?? null;

            // FIX: jangan kirim id kosong/null/0
            if (!$fid || (int) $fid <= 0) {
                continue;
            }

            $fSync[(int) $fid] = [
                'mulai' => $fMulai[$groupKey] ?? null,
                'selesai' => $fSelesai[$groupKey] ?? null,
            ];
        }

        $record->fasilitas()->sync($fSync);

        // -------- ADDITIONAL --------
        $aSelected = array_filter($state['additional_selected'] ?? []);
        $aMulai = $state['additional_mulai'] ?? [];
        $aSelesai = $state['additional_selesai'] ?? [];
        $aSync = [];
        foreach (array_keys($aSelected) as $id) {
            $aid = (int) $id;
            if ($aid > 0) {
                $aSync[$aid] = [
                    'mulai' => $aMulai[$id] ?? null,
                    'selesai' => $aSelesai[$id] ?? null,
                ];
            }
        }

        $record->additional()->sync($aSync);

        // -------- MENU MAKAN --------
        $mSelected = array_filter($state['menu_makan_selected'] ?? []);
        $mJumlah = $state['menu_makan_jumlah'] ?? [];
        $mSync = [];
        foreach (array_keys($mSelected) as $mid) {
            $mid = (int) $mid;
            if ($mid > 0) {
                $qty = max(1, (int) ($mJumlah[$mid] ?? 1));
                $mSync[$mid] = ['jumlah' => $qty];
            }
        }

        $record->menuMakan()->sync($mSync);
    }

    // ===== Helpers =====

    protected static function deriveHariTipe(?string $checkIn, ?string $checkOut): ?string
    {
        if (!$checkIn && !$checkOut) {
            return null;
        }

        if ($checkIn && !$checkOut) {
            $d = Carbon::parse($checkIn);
            return ($d->isSaturday() || $d->isSunday()) ? 'Weekend' : 'Weekday';
        }

        try {
            $start = Carbon::parse($checkIn)->startOfDay();
            $end = Carbon::parse($checkOut)->startOfDay();

            if ($end->lessThanOrEqualTo($start)) {
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

    protected static function hitungSplitPerRange(?string $mulai, ?string $selesai): array
    {
        $wd = 0;
        $we = 0;
        if (!$mulai || !$selesai)
            return ['weekday' => 0, 'weekend' => 0];
        $start = Carbon::parse($mulai)->startOfDay();
        $end = Carbon::parse($selesai)->startOfDay();
        if ($end->lessThanOrEqualTo($start)) {
            ($start->isWeekend()) ? $we++ : $wd++;
            return ['weekday' => $wd, 'weekend' => $we];
        }
        foreach (CarbonPeriod::create($start, $end->copy()->subDay()) as $d) {
            $d->isWeekend() ? $we++ : $wd++;
        }
        return ['weekday' => $wd, 'weekend' => $we];
    }

    protected static function hitungHariSplit(?string $checkIn, ?string $checkOut): array
    {
        $weekday = 0;
        $weekend = 0;

        if (!$checkIn || !$checkOut) {
            return ['weekday' => 1, 'weekend' => 0];
        }

        try {
            $start = Carbon::parse($checkIn)->startOfDay();
            $end = Carbon::parse($checkOut)->startOfDay();

            if ($end->lessThanOrEqualTo($start)) {
                if ($start->isSaturday() || $start->isSunday())
                    $weekend = 1;
                else
                    $weekday = 1;
                return compact('weekday', 'weekend');
            }

            foreach (CarbonPeriod::create($start, $end->copy()->subDay()) as $d) {
                if ($d->isSaturday() || $d->isSunday())
                    $weekend++;
                else
                    $weekday++;
            }
        } catch (\Throwable $e) {
            $weekday = 1;
            $weekend = 0;
        }

        if ($weekday + $weekend < 1)
            $weekday = 1;

        return compact('weekday', 'weekend');
    }

    protected static function fasilitasGroupedByNamaForMember(string $jenisUser): array
    {
        $rows = Fasilitas::query()
            ->where('status', 'Available')
            ->where('jenis_user', $jenisUser)
            ->get();

        $groups = [];

        foreach ($rows as $r) {
            $key = trim(mb_strtolower($r->nama));

            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'nama' => $r->nama,
                    'id_list' => [],
                    'id_weekday' => null,
                    'id_weekend' => null,
                    'id_canonical' => null,
                    'harga_weekday' => null,
                    'harga_weekend' => null,
                ];
            }

            $groups[$key]['id_list'][] = (int) $r->id;

            $day = strtolower((string) $r->day);
            if ($day === 'weekday') {
                $groups[$key]['id_weekday'] = (int) $r->id;
                $groups[$key]['harga_weekday'] = (int) $r->harga;
            } elseif ($day === 'weekend') {
                $groups[$key]['id_weekend'] = (int) $r->id;
                $groups[$key]['harga_weekend'] = (int) $r->harga;
            }

            if ($groups[$key]['id_canonical'] === null) {
                $groups[$key]['id_canonical'] = $groups[$key]['id_weekday']
                    ?? $groups[$key]['id_weekend']
                    ?? (int) $r->id;
            }
        }

        foreach ($groups as &$g) {
            $g['id_list'] = array_values(array_unique($g['id_list']));
        }

        return $groups;
    }

    // === DIBUAT PUBLIC supaya bisa dipanggil dari Pages ===
    public static function hitungTotalHarga(
        array $fSelected,
        array $fMulai,
        array $fSelesai,
        array $aSelected,
        array $aMulai,
        array $aSelesai,
        array $menuSelected,
        array $menuJumlah,
        int $diskonPersen,
        ?string $jenisUser = null,
        ?string $checkIn = null,
        ?string $checkOut = null
    ): int {
        $subtotal = 0;

        // FASILITAS
        if ($jenisUser) {
            $groups = static::fasilitasGroupedByNamaForMember($jenisUser);
            foreach ($fSelected as $key => $on) {
                if (!$on)
                    continue;
                $g = $groups[$key] ?? null;
                if (!$g)
                    continue;

                $split = static::hitungSplitPerRange($fMulai[$key] ?? null, $fSelesai[$key] ?? null);
                $subtotal += ($split['weekday'] * (int) ($g['harga_weekday'] ?? 0))
                    + ($split['weekend'] * (int) ($g['harga_weekend'] ?? 0));
            }
        }

        // ADDITIONAL (per-hari; kalau “sekali pakai” ganti $hari -> min(1, $hari))
        foreach ($aSelected as $id => $on) {
            if (!$on)
                continue;
            $split = static::hitungSplitPerRange($aMulai[$id] ?? null, $aSelesai[$id] ?? null);
            $hari = max(0, $split['weekday'] + $split['weekend']);
            if ($item = Additional::find((int) $id)) {
                $subtotal += (int) $item->harga * max(1, $hari);
            }
        }

        // MENU MAKAN (default pakai global check-in/out)
        $hariReservasi = static::hitungSplitPerRange($checkIn, $checkOut);
        $hariTotal = max(1, $hariReservasi['weekday'] + $hariReservasi['weekend']);
        foreach (array_keys(array_filter($menuSelected)) as $mid) {
            if ($menu = MenuMakan::find((int) $mid)) {
                $qty = max(1, (int) ($menuJumlah[$mid] ?? 1));
                $subtotal += (int) $menu->harga * $qty * $hariTotal;
            }
        }

        // Diskon
        $diskon = max(0, min(100, (int) $diskonPersen));
        $subtotal -= (int) round($subtotal * ($diskon / 100));

        return max(0, (int) $subtotal);
    }

    // Ambil tanggal booked untuk 1 fasilitas-id
    protected static function bookedDatesForFacility(int $facilityId, ?int $excludeReservasiId = null): array
    {
        $cacheKey = $facilityId . '|' . ($excludeReservasiId ?: 0);
        if (isset(static::$bookedDateCache[$cacheKey]))
            return static::$bookedDateCache[$cacheKey];

        $rows = \DB::table('pemesanan_fasilitas')
            ->select('mulai', 'selesai', 'reservasi_id')
            ->where('fasilitas_id', $facilityId)
            ->when($excludeReservasiId, fn($q) => $q->where('reservasi_id', '!=', $excludeReservasiId))
            ->get();

        $dates = [];
        foreach ($rows as $r) {
            if (!$r->mulai || !$r->selesai)
                continue;
            $start = \Carbon\Carbon::parse($r->mulai)->startOfDay();
            $end = \Carbon\Carbon::parse($r->selesai)->startOfDay();

            if ($end->lessThanOrEqualTo($start)) {
                $dates[] = $start->format('Y-m-d');
                continue;
            }
            foreach (\Carbon\CarbonPeriod::create($start, $end->copy()->subDay()) as $d) {
                $dates[] = $d->format('Y-m-d');
            }
        }
        return static::$bookedDateCache[$cacheKey] = array_values(array_unique($dates));
    }

    // Disabled dates untuk 1 group fasilitas (gabungkan semua id_list)
    protected static function disabledDatesForGroup(string $groupKey, \Filament\Forms\Get $get): array
    {
        $jenis = $get('jenis_member') ?? 'Internal';
        $groups = static::fasilitasGroupedByNamaForMember($jenis);
        $g = $groups[$groupKey] ?? null;
        if (!$g)
            return [];

        // Pakai semua varian ID yang mungkin dipakai saat simpan (weekday/weekend/canonical)
        $ids = array_unique(array_filter(array_map('intval', array_merge(
            $g['id_list'] ?? [],
            [$g['id_weekday'] ?? 0, $g['id_weekend'] ?? 0, $g['id_canonical'] ?? 0]
        ))));

        $currentId = (int) (request()->route('record') ?? 0) ?: null;

        $all = [];
        foreach ($ids as $fid) {
            $all = array_merge($all, static::bookedDatesForFacility($fid, $currentId));
        }
        return array_values(array_unique($all));
    }
}
