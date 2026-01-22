<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdminReservasiResource\Pages;
use App\Models\Reservasi;
use App\Models\Fasilitas;
use App\Models\Additional;
use App\Models\MenuMakan;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AdminReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Manajemen Reservasi';
    protected static ?string $navigationLabel = 'Kelola Reservasi';
    protected static array $bookedDateCache = [];
    public static ?int $currentEditingReservasiId = null;

    public static function getModelLabel(): string
    {
        return 'Reservasi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Reservasi';
    }

    // Mutate data before create: set id_pic_utc, id_pic_ioc, status_reservasi based on user role
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        if ($user->id_role == 2) {
            $data['id_pic_utc'] = $user->id;
            $data['id_pic_ioc'] = null;
            $data['status_reservasi'] = 'ACC';
        } elseif ($user->id_role == 4) {
            $data['id_pic_ioc'] = $user->id;
            $data['id_pic_utc'] = null;
            $data['status_reservasi'] = 'NOT ACC';
        }
        return $data;
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
        return mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    }

    public static function form(Form $form): Form
    {
        $recalc = function (Get $get, callable $set) {
            $selectedKeys = array_keys(array_filter((array) $get('fasilitas_selected')));
            $perPerson = ['avocado cottage', 'banana cottage', 'cassava cottage', 'durian cottage'];

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
            Hidden::make('reservasi_id_edit')
                ->default(null)
                ->dehydrated(false),
                
            Hidden::make('form_ready')
                ->default(true)
                ->dehydrated(false),

            Hidden::make('fasilitas_selected')->default([])->dehydrated(false),
            Hidden::make('fasilitas_mulai')->default([])->dehydrated(false),
            Hidden::make('fasilitas_selesai')->default([])->dehydrated(false),
            Hidden::make('fasilitas_jumlah_orang')->default([])->dehydrated(false),
            Hidden::make('additional_selected')->default([])->dehydrated(false),
            Hidden::make('additional_mulai')->default([])->dehydrated(false),
            Hidden::make('additional_selesai')->default([])->dehydrated(false),
            Hidden::make('menu_makan_selected')->default([])->dehydrated(false),
            Hidden::make('menu_makan_jumlah')->default([])->dehydrated(false),
            Hidden::make('hari_tipe')->dehydrated(false),

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

            TextInput::make('kode_reservasi')
                ->label('Kode Reservasi')
                ->disabled()
                ->dehydrated(false)
                ->visible(fn ($record) => $record !== null),

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
                ->helperText(fn($state) => $state
                    ? 'Hari: ' . Carbon::parse($state)->locale('id')->isoFormat('dddd')
                    : null)
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
                ->helperText(fn($state) => $state
                    ? 'Hari: ' . Carbon::parse($state)->locale('id')->isoFormat('dddd')
                    : null)
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

            Section::make('Fasilitas')
                ->description('Harga otomatis menyesuaikan Weekday/Weekend di rentang tanggal.')
                ->schema([
                    Group::make()
                        ->schema(function (\Filament\Forms\Get $get) use ($recalc) {
                            $jenis = $get('jenis_member') ?? 'Internal';
                            $groups = static::fasilitasGroupedByNamaForMember($jenis);
                            $selected = (array) ($get('fasilitas_selected') ?? []);

                            $HALL = [
                                'Multifunction Hall' => 'multifunction hall',
                                'Hall A/B' => 'hall a/b',
                                'Hall A+B' => 'hall a+b',
                                'Welirang Room' => 'welirang room',
                                'Cinnamon Executive Meeting Room' => 'cinnamon executive meeting room',
                                'Arjuna Room' => 'arjuna room',
                                'Pendapa Pawitra' => 'pendapa pawitra',
                            ];
                            $COTTAGE = [
                                'Albizia Cottage' => 'albizia cottage',
                                'Bamboo Cottage' => 'bamboo cottage',
                                'Coffee Cottage' => 'coffee cottage',
                                'Avocado Cottage' => 'avocado cottage',
                                'Banana Cottage' => 'banana cottage',
                                'Cassava Cottage' => 'cassava cottage',
                                'Durian Cottage' => 'durian cottage',
                            ];
                            $VIP = [
                                'VIP Cottage - Asparagus' => 'vip cottage - asparagus',
                                'VIP Cottage - Brocolli' => 'vip cottage - brocolli',
                                'VIP Cottage - Celery' => 'vip cottage - celery',
                                'VIP Cottage - Eucalyptus' => 'vip cottage - eucalyptus',
                                'VIP Cottage - Fennel' => 'vip cottage - fennel',
                                'VIP Cottage - Ginger' => 'vip cottage - ginger',
                                'VIP Cottage - Kiwi' => 'vip cottage - kiwi',
                                'VIP Cottage - Lemon' => 'vip cottage - lemon',
                                'VIP Cottage - Mango' => 'vip cottage - mango',
                                'VIP Cottage - Papaya' => 'vip cottage - papaya',
                                'VIP Cottage - Tomato' => 'vip cottage - tomato',
                                'VIP Cottage - Salacca' => 'vip cottage - salacca',
                            ];

                            $OTHERS = [
                                'Camping Ground 1' => 'camping ground 1',
                                'Camping Ground 2' => 'camping ground 2',
                                'Camping Ground 3' => 'camping ground 3',
                                'Camping Ground 4' => 'camping ground 4',
                                'Camping Ground 5' => 'camping ground 5',
                                'Camping + Tenda' => 'camping + tenda',
                                'Driver Room' => 'driver room',
                            ];

                            $buildBox = function (string $title, array $items) use ($groups, $selected, $recalc) {
                                $rows = [];
                                foreach ($items as $label => $key) {
                                    if (!isset($groups[$key])) {
                                        continue;
                                    }
                                    $rows[] = static::facilityRow($key, $label, $selected, $recalc);
                                }
                                if (empty($rows))
                                    return null;

                                return \Filament\Forms\Components\Section::make($title)
                                    ->schema($rows)
                                    ->columns(1)
                                    ->collapsible();
                            };

                            $boxes = array_filter([
                                $buildBox('HALL', $HALL),
                                $buildBox('COTTAGE', $COTTAGE),
                                $buildBox('VIP COTTAGE', $VIP),
                                $buildBox('OTHERS', $OTHERS),
                            ]);

                            $selectedKeys = array_keys(array_filter((array) $get('fasilitas_selected')));
                            $perPerson = ['avocado cottage', 'banana cottage', 'cassava cottage', 'durian cottage'];
                            $showJumlah = count(array_intersect($selectedKeys, $perPerson)) > 0;


                            // kembalikan box + field jumlah_orang di bawahnya
                            return array_merge(array_values($boxes));
                        })
                        ->live(),
                ])
                ->columns(1),

            // ...jumlah_orang textbox removed as requested...


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

            // SECTION 1: Dokumen Pembayaran (Upload & Pilih tipe pembayaran)
            Section::make('💳 Dokumen Pembayaran')
                ->description('Upload dokumen dan pilih tipe pembayaran')
                ->visible(fn($record) => $record !== null) // Hanya tampil saat edit, tidak saat create
                ->schema([
                    // Status Pembayaran Display
                    Placeholder::make('status_pembayaran_display')
                        ->label('Status Pembayaran Saat Ini')
                        ->content(function($record) {
                            if (!$record || !$record->status_pembayaran) {
                                return new HtmlString('<span class="text-red-600 font-semibold">❌ Belum Bayar</span>');
                            } elseif ($record->status_pembayaran === 'DP') {
                                return new HtmlString('<span class="text-blue-600 font-semibold">💳 DP 30%</span>');
                            } elseif ($record->status_pembayaran === 'LUNAS') {
                                return new HtmlString('<span class="text-green-600 font-semibold">✅ LUNAS</span>');
                            }
                            return 'Belum Bayar';
                        })
                        ->visible(fn($record) => $record !== null),

                    // Radio Tipe Pembayaran - hanya tampil jika belum final (NULL atau DP)
                    Radio::make('status_pembayaran')
                        ->label('Pilih Tipe Pembayaran')
                        ->options(function($record) {
                            // Jika sudah LUNAS, jangan tampilkan pilihan
                            if ($record && $record->status_pembayaran === 'LUNAS') {
                                return [];
                            }
                            // Jika sudah DP, hanya tampilkan opsi LUNAS
                            if ($record && $record->status_pembayaran === 'DP') {
                                return [
                                    'LUNAS' => 'LUNAS (Bayar Sisa Sekarang)',
                                ];
                            }
                            // Jika NULL/Belum bayar, tampilkan keduanya
                            return [
                                'DP' => 'DP 30% + Upgrade Lunas Nanti',
                                'LUNAS' => 'LUNAS (Bayar Penuh Sekarang)',
                            ];
                        })
                        ->visible(function($record) {
                            // Hanya tampil jika status bukan LUNAS
                            return !($record && $record->status_pembayaran === 'LUNAS');
                        })
                        ->dehydrated(true)
                        ->required(fn(Get $get, $record) => !$get('status_pembayaran') || ($record && $record->status_pembayaran === 'DP'))
                        ->reactive()
                        ->live(),

                    // Upload Reservation Form - hanya tampil jika status NULL dan sudah pilih DP
                    FileUpload::make('file_reservation_form')
                        ->label('Upload Reservation Form (Wajib)')
                        ->disk('public')
                        ->directory('reservasi/forms')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->maxSize(5120)
                        ->preserveFilenames()
                        ->dehydrated(true)
                        ->nullable()
                        ->required(fn(Get $get, $record) => 
                            $record && !$record->status_pembayaran && $get('status_pembayaran') === 'DP'
                        )
                        ->visible(function($record, Get $get) {
                            // Hanya tampil jika status NULL (belum bayar) dan pilih DP
                            if ($record && !$record->status_pembayaran && $get('status_pembayaran') === 'DP') {
                                return true;
                            }
                            return false;
                        }),

                    // Upload Bukti DP - hanya tampil jika status NULL dan pilih DP
                    FileUpload::make('file_bukti_dp')
                        ->label('Upload Bukti Pembayaran DP (Wajib)')
                        ->disk('public')
                        ->directory('reservasi/bukti-pembayaran')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->maxSize(5120)
                        ->preserveFilenames()
                        ->dehydrated(true)
                        ->nullable()
                        ->required(fn(Get $get, $record) => 
                            $record && !$record->status_pembayaran && $get('status_pembayaran') === 'DP'
                        )
                        ->visible(function($record, Get $get) {
                            // Hanya tampil jika status NULL dan pilih DP
                            if ($record && !$record->status_pembayaran && $get('status_pembayaran') === 'DP') {
                                return true;
                            }
                            return false;
                        }),

                    // Upload Bukti Lunas - tampil jika (status NULL dan pilih LUNAS) atau (status DP dan pilih LUNAS)
                    FileUpload::make('file_bukti_lunas')
                        ->label('Upload Bukti Pembayaran Lunas (Wajib)')
                        ->disk('public')
                        ->directory('reservasi/bukti-pembayaran')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->maxSize(5120)
                        ->preserveFilenames()
                        ->dehydrated(true)
                        ->nullable()
                        ->required(fn(Get $get) => $get('status_pembayaran') === 'LUNAS')
                        ->visible(function($record, Get $get) {
                            // Tampil jika status NULL atau DP, dan pilih LUNAS
                            if ($get('status_pembayaran') === 'LUNAS') {
                                if ($record && !$record->status_pembayaran) {
                                    return true; // Status NULL, pilih LUNAS
                                }
                                if ($record && $record->status_pembayaran === 'DP') {
                                    return true; // Status DP, pilih LUNAS
                                }
                            }
                            return false;
                        }),
                ]),

            // SECTION: Ringkasan Dokumen (View-only, display data dari database)
            Section::make('📂 Ringkasan Dokumen')
                ->description('Semua file dokumen yang telah diupload')
                ->visible(fn($record) => $record !== null) // Hanya tampil saat edit, tidak saat create
                ->schema([
                    Group::make()
                        ->schema([
                            Placeholder::make('doc_form')
                                ->label('📋 Reservation Form')
                                ->content(function($record) {
                                    if (!$record || !$record->file_reservation_form) {
                                        return '❌ Tidak ada file';
                                    }
                                    $url = asset('storage/' . $record->file_reservation_form);
                                    return new HtmlString("<a href=\"{$url}\" target=\"_blank\" class=\"inline-flex items-center gap-2 px-4 py-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 font-medium\">📥 Download Reservation Form</a>");
                                }),

                            Placeholder::make('doc_dp')
                                ->label('💳 Bukti Pembayaran DP')
                                ->content(function($record) {
                                    if (!$record || !$record->file_bukti_dp) {
                                        return '❌ Tidak ada file';
                                    }
                                    $url = asset('storage/' . $record->file_bukti_dp);
                                    return new HtmlString("<a href=\"{$url}\" target=\"_blank\" class=\"inline-flex items-center gap-2 px-4 py-3 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 font-medium\">📥 Download Bukti DP</a>");
                                }),

                            Placeholder::make('doc_lunas')
                                ->label('✅ Bukti Pembayaran Lunas')
                                ->content(function($record) {
                                    if (!$record || !$record->file_bukti_lunas) {
                                        return '❌ Tidak ada file';
                                    }
                                    $url = asset('storage/' . $record->file_bukti_lunas);
                                    return new HtmlString("<a href=\"{$url}\" target=\"_blank\" class=\"inline-flex items-center gap-2 px-4 py-3 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 font-medium\">📥 Download Bukti Lunas</a>");
                                }),
                        ])
                        ->columns(1),
                ]),

            DateTimePicker::make('tanggal_dibuat')->label('Tanggal Dibuat')->default(now())->required()->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        // Show all reservations for all users (no filter by status_reservasi)
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pemesan')
                    ->label('Nama Pemesan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('No Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul_kegiatan')
                    ->label('Judul Kegiatan')
                    ->searchable(),
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
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => static::canEditRecord($record)),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => static::canEditRecord($record)),
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\Action::make('accept')
                    ->label('Terima')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => 
                        Auth::user()?->id_role == 2 && 
                        $record->status_reservasi === 'NOT ACC' &&
                        static::isCheckInNotPassed($record)
                    )
                    ->action(function ($record) {
                        $record->id_pic_utc = Auth::id();
                        $record->status_reservasi = 'ACC';
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Terima Reservasi')
                    ->modalDescription('Apakah Anda yakin ingin menerima reservasi ini?')
                    ->modalSubmitActionLabel('Ya, Terima')
                    ->successNotificationTitle('Reservasi berhasil diterima'),
                Tables\Actions\Action::make('view_detail')
                    ->label('View Detail')
                    ->url(fn (Reservasi $record): string => route('reservasi.detail', ['id' => $record->id]))
                    ->openUrlInNewTab()
                    ->visible(fn (Reservasi $record): bool => $record->exists),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdminReservasis::route('/'),
            'create' => Pages\CreateAdminReservasi::route('/create'),
            'view' => Pages\ViewAdminReservasi::route('/{record}'),
            'edit' => Pages\EditAdminReservasi::route('/{record}/edit'),
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

    public static function syncPivotsFromFormState(Reservasi $record, array $state): void
    {
        $fSelected = array_filter($state['fasilitas_selected'] ?? []);
        $fMulai = $state['fasilitas_mulai'] ?? [];
        $fSelesai = $state['fasilitas_selesai'] ?? [];
        $fJumlahOrang = $state['fasilitas_jumlah_orang'] ?? [];
        $fSync = [];

        $jenis = $state['jenis_member'] ?? 'Internal';
        $groups = static::fasilitasGroupedByNamaForMember($jenis);

        foreach (array_keys($fSelected) as $groupKey) {
            if (!isset($groups[$groupKey]))
                continue;

            $fid = $groups[$groupKey]['id_canonical'] ?? null;

            if (!$fid || (int) $fid <= 0) {
                continue;
            }

            $fSync[(int) $fid] = [
                'mulai' => $fMulai[$groupKey] ?? null,
                'selesai' => $fSelesai[$groupKey] ?? null,
                'jumlah_orang' => max(1, (int) ($fJumlahOrang[$groupKey] ?? 1)),
            ];
        }

        $record->fasilitas()->sync($fSync);

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
        ?string $checkOut = null,
        ?array $fJumlahOrang = null
    ): int {
        $subtotal = 0;
        $perPersonKeys = ['avocado cottage', 'banana cottage', 'cassava cottage', 'durian cottage'];

        if ($jenisUser) {
            $groups = static::fasilitasGroupedByNamaForMember($jenisUser);
            foreach ($fSelected as $key => $on) {
                if (!$on)
                    continue;
                $g = $groups[$key] ?? null;
                if (!$g)
                    continue;

                $split = static::hitungSplitPerRange($fMulai[$key] ?? null, $fSelesai[$key] ?? null);
                $base = ($split['weekday'] * (int) ($g['harga_weekday'] ?? 0))
                    + ($split['weekend'] * (int) ($g['harga_weekend'] ?? 0));

                $jumlahOrang = in_array($key, $perPersonKeys, true) 
                    ? max(1, (int)($fJumlahOrang[$key] ?? 1))
                    : 1;

                $subtotal += $jumlahOrang * $base;
            }
        }

        foreach ($aSelected as $id => $on) {
            if (!$on)
                continue;
            $split = static::hitungSplitPerRange($aMulai[$id] ?? null, $aSelesai[$id] ?? null);
            $hari = max(0, $split['weekday'] + $split['weekend']);
            if ($item = Additional::find((int) $id)) {
                $subtotal += (int) $item->harga * max(1, $hari);
            }
        }

        $hariReservasi = static::hitungSplitPerRange($checkIn, $checkOut);
        $hariTotal = max(1, $hariReservasi['weekday'] + $hariReservasi['weekend']);
        foreach (array_keys(array_filter($menuSelected)) as $mid) {
            if ($menu = MenuMakan::find((int) $mid)) {
                $qty = max(1, (int) ($menuJumlah[$mid] ?? 1));
                $subtotal += (int) $menu->harga * $qty * $hariTotal;
            }
        }

        $diskon = max(0, min(100, (int) $diskonPersen));
        $subtotal -= (int) round($subtotal * ($diskon / 100));

        return max(0, (int) $subtotal);
    }

    protected static function bookedDatesForFacility(int $facilityId, ?int $excludeReservasiId = null): array
    {
        // If no explicit exclude ID, try to get from current editing context
        if ($excludeReservasiId === null) {
            $excludeReservasiId = static::$currentEditingReservasiId;
        }

        $cacheKey = $facilityId . '|' . ($excludeReservasiId ?: 0);
        if (isset(static::$bookedDateCache[$cacheKey]))
            return static::$bookedDateCache[$cacheKey];

        $rows = DB::table('pemesanan_fasilitas')
            ->select('mulai', 'selesai', 'reservasi_id')
            ->where('fasilitas_id', $facilityId)
            ->when($excludeReservasiId, fn($q) => $q->where('reservasi_id', '!=', $excludeReservasiId))
            ->get();

        \Log::debug("bookedDatesForFacility: facId=$facilityId, exclude=$excludeReservasiId, found=" . count($rows) . " rows");

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

    protected static function disabledDatesForGroup(string $groupKey, \Filament\Forms\Get $get): array
    {
        $ids = static::facilityIdsByGroupKey($groupKey);
        if (empty($ids))
            return [];

        // Try to get reservasiId from multiple sources (in priority order):
        // 1. Static property set in mount() - this is set by EditReservasi::mount()
        // 2. Route parameter - fallback for form schema evaluation
        // 3. Hidden field 'reservasi_id_edit' if it exists in the form
        $reservasiId = static::$currentEditingReservasiId;
        
        if (!$reservasiId) {
            // Try to get from hidden field that might be set in form
            try {
                $hiddenId = $get('reservasi_id_edit');
                if ($hiddenId) {
                    $reservasiId = (int) $hiddenId;
                }
            } catch (\Exception $e) {
                // Silently fail, try next source
            }
        }
        
        if (!$reservasiId) {
            // Try to get from route parameter
            try {
                $recordId = request()->route('record');
                if ($recordId) {
                    $reservasiId = (int) $recordId;
                }
            } catch (\Exception $e) {
                \Log::debug("Could not get record from route: " . $e->getMessage());
            }
        }
        
        // DEBUG - show what we found
        \Log::debug("disabledDatesForGroup($groupKey): staticProp=" . static::$currentEditingReservasiId . ", routeRecord=$reservasiId, facilityIds=" . json_encode($ids));

        $all = [];
        foreach ($ids as $fid) {
            // Pass $reservasiId to exclude current reservation from conflict check
            $dates = static::bookedDatesForFacility($fid, $reservasiId);
            \Log::debug("  Facility $fid: bookedDatesForFacility with reservasiId=$reservasiId returned " . count($dates) . " blocked dates");
            $all = array_merge($all, $dates);
        }
        return array_values(array_unique($all));
    }

    // Helper method to get the current editing reservasi ID
    protected static function getCurrentEditingReservasiId(): ?int
    {
        return static::$currentEditingReservasiId;
    }

    // Helper method to clear booked date cache
    public static function clearBookedDateCache(): void
    {
        static::$bookedDateCache = [];
    }

    protected static function facilityIdsByGroupKey(string $groupKey): array
    {
        return \App\Models\Fasilitas::query()
            ->where('status', 'Available')
            ->whereRaw('LOWER(nama) = ?', [$groupKey])
            ->pluck('id')
            ->map(fn($v) => (int) $v)
            ->all();
    }

    protected static function facilityRow(string $groupKey, string $label, array $selectedMap, callable $recalc)
    {
        $perPersonCottages = ['avocado cottage', 'banana cottage', 'durian cottage', 'cassava cottage'];
        $isPerPerson = in_array($groupKey, $perPersonCottages);
        
        $schema = [
            \Filament\Forms\Components\Checkbox::make("fasilitas_selected.$groupKey")
                ->label($label)
                ->reactive()
                ->live()
                ->afterStateHydrated(function (\Filament\Forms\Components\Checkbox $c) use ($groupKey, $selectedMap) {
                    $c->state((bool) ($selectedMap[$groupKey] ?? false));
                })
                ->afterStateUpdated(function ($state, \Filament\Forms\Set $set, \Filament\Forms\Get $get) use ($recalc, $groupKey) {
                    if ($state === true) {
                        $set("fasilitas_mulai.$groupKey", $get('waktu_check_in'));
                        $set("fasilitas_selesai.$groupKey", $get('waktu_check_out'));
                    }
                    $recalc($get, $set);
                }),

            \Filament\Forms\Components\DateTimePicker::make("fasilitas_mulai.$groupKey")
                ->label('Mulai')
                ->native(false)
                ->minDate(fn(\Filament\Forms\Get $get) => $get('waktu_check_in'))
                ->maxDate(fn(\Filament\Forms\Get $get) => $get('waktu_check_out'))
                ->disabledDates(fn(\Filament\Forms\Get $get) => static::disabledDatesForGroup($groupKey, $get))
                ->required(fn(\Filament\Forms\Get $get) => $get("fasilitas_selected.$groupKey") === true)
                ->visible(fn(\Filament\Forms\Get $get) => $get("fasilitas_selected.$groupKey") === true)
                ->reactive()
                ->rule(function (\Filament\Forms\Get $get) use ($groupKey) {
                    return function (string $attribute, $value, $fail) use ($get, $groupKey) {
                        if (!$value)
                            return;
                        $start = \Carbon\Carbon::parse($value)->startOfDay();
                        $endRaw = $get("fasilitas_selesai.$groupKey");
                        $end = $endRaw ? \Carbon\Carbon::parse($endRaw)->startOfDay() : $start->copy()->addDay();
                        $blocked = static::disabledDatesForGroup($groupKey, $get);
                        $set = array_flip($blocked);
                        foreach (\Carbon\CarbonPeriod::create($start, $end->copy()->subDay()) as $d) {
                            if (isset($set[$d->format('Y-m-d')])) {
                                $fail('Rentang tanggal fasilitas bentrok.');
                                break;
                            }
                        }
                        $checkIn = $get('waktu_check_in');
                        if ($checkIn && $value < $checkIn)
                            $fail('Tanggal mulai < check-in.');
                    };
                })
                ->afterStateUpdated(fn($state, \Filament\Forms\Set $set, \Filament\Forms\Get $get) => $recalc($get, $set)),

            \Filament\Forms\Components\DateTimePicker::make("fasilitas_selesai.$groupKey")
                ->label('Selesai')
                ->native(false)
                ->minDate(fn(\Filament\Forms\Get $get) => $get('waktu_check_in'))
                ->maxDate(fn(\Filament\Forms\Get $get) => $get('waktu_check_out'))
                ->disabledDates(fn(\Filament\Forms\Get $get) => static::disabledDatesForGroup($groupKey, $get))
                ->required(fn(\Filament\Forms\Get $get) => $get("fasilitas_selected.$groupKey") === true)
                ->visible(fn(\Filament\Forms\Get $get) => $get("fasilitas_selected.$groupKey") === true)
                ->reactive()
                ->rule(function (\Filament\Forms\Get $get) use ($groupKey) {
                    return function (string $attribute, $value, $fail) use ($get, $groupKey) {
                        if (!$value)
                            return;
                        $end = \Carbon\Carbon::parse($value)->startOfDay();
                        $startRaw = $get("fasilitas_mulai.$groupKey");
                        if (!$startRaw)
                            return;
                        $start = \Carbon\Carbon::parse($startRaw)->startOfDay();
                        if ($end->lessThanOrEqualTo($start))
                            $fail('Selesai harus > Mulai.');
                        $blocked = static::disabledDatesForGroup($groupKey, $get);
                        $set = array_flip($blocked);
                        foreach (\Carbon\CarbonPeriod::create($start, $end->copy()->subDay()) as $d) {
                            if (isset($set[$d->format('Y-m-d')])) {
                                $fail('Rentang tanggal fasilitas bentrok.');
                                break;
                            }
                        }
                        $checkOut = $get('waktu_check_out');
                        if ($checkOut && $value > $checkOut)
                            $fail('Tanggal selesai > check-out.');
                    };
                })
                ->afterStateUpdated(fn($state, \Filament\Forms\Set $set, \Filament\Forms\Get $get) => $recalc($get, $set)),
        ];

        if ($isPerPerson) {
            $schema[] = \Filament\Forms\Components\TextInput::make("fasilitas_jumlah_orang.$groupKey")
                ->label('Jumlah Orang')
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required(fn(\Filament\Forms\Get $get) => $get("fasilitas_selected.$groupKey") === true)
                ->visible(fn(\Filament\Forms\Get $get) => $get("fasilitas_selected.$groupKey") === true)
                ->reactive()
                ->afterStateUpdated(fn($state, \Filament\Forms\Set $set, \Filament\Forms\Get $get) => $recalc($get, $set))
                ->afterStateHydrated(function (\Filament\Forms\Components\TextInput $component, \Filament\Forms\Get $get) use ($groupKey) {
                    $jumlahOrang = $get("fasilitas_jumlah_orang.$groupKey") ?? 1;
                    $component->state((int) $jumlahOrang);
                });
        }
        
        return \Filament\Forms\Components\Grid::make($isPerPerson ? 4 : 3)->schema($schema);
    }

}
