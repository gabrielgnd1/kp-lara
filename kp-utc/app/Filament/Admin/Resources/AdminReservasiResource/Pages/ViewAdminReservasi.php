<?php

namespace App\Filament\Admin\Resources\AdminReservasiResource\Pages;

use App\Filament\Admin\Resources\AdminReservasiResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Group;
use Illuminate\Support\HtmlString;

class ViewAdminReservasi extends ViewRecord
{
    protected static string $resource = AdminReservasiResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('')
                ->schema([
                    Radio::make('jenis_member')
                        ->label('Jenis Member')
                        ->options(['Internal' => 'Internal', 'Eksternal' => 'Eksternal'])
                        ->disabled(),
                ])
                ->columns(1),

            Grid::make([
                'default' => 1,
                'md' => 2,
            ])->schema([
                TextInput::make('kode_reservasi')
                    ->label('Kode Reservasi')
                    ->disabled(),
                TextInput::make('nama_pemesan')
                    ->label('Nama Pemesan')
                    ->disabled(),
                TextInput::make('no_telepon')
                    ->label('No Telepon')
                    ->disabled(),
            ]),

            TextInput::make('email')
                ->label('Email')
                ->disabled(),

            TextInput::make('judul_kegiatan')
                ->label('Judul Kegiatan')
                ->disabled(),

            DateTimePicker::make('waktu_check_in')
                ->label('Waktu Check In')
                ->disabled(),

            DateTimePicker::make('waktu_check_out')
                ->label('Waktu Check Out')
                ->disabled(),

            TextInput::make('jumlah_laki')
                ->label('Jumlah Laki-laki')
                ->disabled(),

            TextInput::make('jumlah_perempuan')
                ->label('Jumlah Perempuan')
                ->disabled(),

            Textarea::make('informasi_tambahan')
                ->label('Informasi Tambahan')
                ->disabled()
                ->columnSpanFull(),

            Section::make('Status')
                ->schema([
                    TextInput::make('status_reservasi')
                        ->label('Status Reservasi')
                        ->disabled(),
                    TextInput::make('status_pembayaran')
                        ->label('Status Pembayaran')
                        ->disabled(),
                ])
                ->columns(2),

            TextInput::make('diskon')
                ->label('Diskon (%)')
                ->disabled(),

            TextInput::make('harga_akhir')
                ->label('Harga Akhir (Rp)')
                ->disabled()
                ->prefix('Rp'),

            Section::make('Fasilitas yang Dipesan')
                ->description('Daftar fasilitas yang telah dipesan')
                ->schema([
                    \Filament\Forms\Components\View::make('forms.components.booked-facilities'),
                ]),

            Section::make('Additional yang Dipesan')
                ->description('Daftar additional yang telah dipesan')
                ->schema([
                    \Filament\Forms\Components\View::make('forms.components.booked-additional'),
                ]),

            Section::make('Menu Makan yang Dipesan')
                ->description('Daftar menu makan yang telah dipesan')
                ->schema([
                    \Filament\Forms\Components\View::make('forms.components.booked-menu-makan'),
                ]),

            Section::make('📂 Ringkasan Dokumen')
                ->description('Semua file dokumen yang telah diupload')
                ->visible(fn(\Filament\Forms\Get $get) => !empty($get('file_reservation_form')) || !empty($get('file_bukti_dp')) || !empty($get('file_bukti_lunas')))
                ->schema([
                    Group::make()
                        ->schema([
                            Placeholder::make('doc_form')
                                ->label('📋 Reservation Form')
                                ->dehydrated(false)
                                ->content(function(\Filament\Forms\Get $get) {
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
                                ->content(function(\Filament\Forms\Get $get) {
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
                                ->content(function(\Filament\Forms\Get $get) {
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
                ->disabled(),
        ]);
    }
}
