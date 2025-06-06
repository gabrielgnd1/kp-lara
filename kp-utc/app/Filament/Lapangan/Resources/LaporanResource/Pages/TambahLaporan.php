<?php

namespace App\Filament\Lapangan\Resources\LaporanResource\Pages;

use App\Filament\Lapangan\Resources\LaporanResource;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Forms\Components\{
    TextInput,
    FileUpload,
    Select,
    DatePicker,
    Hidden,
    Card
};
use App\Models\Laporan;

class TambahLaporan extends Page
{
    use InteractsWithForms;

    protected static string $resource = LaporanResource::class;

    protected static string $view = 'filament.lapangan.resources.laporan-resource.pages.tambah-laporan';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'tanggal_lapor' => now()->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonth()->format('Y-m-d'),
            'user_id' => auth()->id(),
            'decision' => 'Belum Diproses',
            'notifikasi' => 'Belum Dibaca',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Card::make([
                TextInput::make('nama_laporan')->required()->maxLength(100),
                FileUpload::make('foto_laporan')->image()->required(),
                Select::make('prioritas')->required()->options([
                    'Rendah' => 'Rendah',
                    'Sedang' => 'Sedang',
                    'Tinggi' => 'Tinggi',
                ]),
                DatePicker::make('tanggal_lapor')->required(),
                DatePicker::make('tanggal_deadline'),
                Select::make('tipe_laporan')->required()->options([
                    'Kebersihan' => 'Kebersihan',
                    'Kerusakan' => 'Kerusakan',
                    'Perbaikan' => 'Perbaikan',
                    'Lainnya' => 'Lainnya',
                ]),
                Select::make('area_id')->relationship('area', 'nama_area')->required(),
                Hidden::make('user_id')->default(fn () => auth()->id()),
                Hidden::make('decision')->default('Belum Diproses'),
                Hidden::make('tanggal_selesai')->default(fn () => now()->addMonth()->toDateString()),
                Hidden::make('notifikasi')->default('Belum Dibaca'),
            ])
        ])->statePath('data');
    }

    public function create(): void
    {
        Laporan::create($this->form->getState());

        Notification::make()
            ->title('Laporan berhasil ditambahkan.')
            ->success()
            ->send();

        $this->redirect(LaporanResource::getUrl('index'));
    }

    public static function getSlug(): string
    {
        return 'tambah';
    }
    public static function getNavigationLabel(): string
    {
        return 'Tambah Laporan';
    }
    public static function getNavigationGroup(): ?string
    {
        return 'Laporan';
    }
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-plus';
    }

   
}
