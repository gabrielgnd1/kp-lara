<?php

namespace App\Filament\Admin\Resources\SuperAdminFasilitasResource\Pages;

use App\Filament\Admin\Resources\SuperAdminFasilitasResource;
use App\Models\Fasilitas;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class ManageFasilitasCards extends Page
{
    protected static string $resource = SuperAdminFasilitasResource::class;
    protected static string $view = 'filament.admin.resources.super-admin-fasilitas-resource.pages.manage-fasilitas-cards';

    public bool $showEditModal = false;
    public ?Fasilitas $editingFacility = null;
    public array $editFormData = [];
    public array $expandedGroups = [
        'hall' => true,
        'cottage' => true,
        'vip_cottage' => true,
        'others' => true,
    ];

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('Tambah Fasilitas')
                ->url(fn (): string => static::$resource::getUrl('create'))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return 'Fasilitas';
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->getResource()::getUrl() => 'Fasilitas',
            '#' => 'Daftar',
        ];
    }

    #[Computed]
    public function facilities(): array
    {
        // Get all facilities with status "Available"
        $allFacilities = Fasilitas::where('status', 'Available')
            ->orderBy('nama')
            ->orderBy('jenis_user')
            ->orderBy('menginap')
            ->orderBy('day')
            ->get();

        // Define facility categories
        $categories = [
            'hall' => [
                'Multifunction Hall', 'Hall A/B', 'Hall A+B', 'Welirang Room',
                'Cinnamon Executive Meeting Room', 'Arjuna Room', 'Pendapa Pawitra'
            ],
            'cottage' => [
                'Albizia Cottage', 'Bamboo Cottage', 'Coffee Cottage',
                'Avocado Cottage', 'Banana Cottage', 'Cassava Cottage', 'Durian Cottage'
            ],
            'vip_cottage' => [
                'VIP Cottage - Asparagus', 'VIP Cottage - Brocolli', 'VIP Cottage - Celery',
                'VIP Cottage - Eucalyptus', 'VIP Cottage - Fennel', 'VIP Cottage - Ginger',
                'VIP Cottage - Kiwi', 'VIP Cottage - Lemon', 'VIP Cottage - Mango',
                'VIP Cottage - Papaya', 'VIP Cottage - Tomato', 'VIP Cottage - Salacca'
            ],
            'others' => [
                'Camping Ground 1', 'Camping Ground 2', 'Camping Ground 3',
                'Camping Ground 4', 'Camping Ground 5', 'Camping + Tenda', 'Driver Room'
            ]
        ];

        // Organize facilities by category, then by name
        $organized = [];
        foreach ($categories as $category => $names) {
            $organized[$category] = [];
            foreach ($names as $name) {
                $facilities = $allFacilities->where('nama', $name);
                if ($facilities->isNotEmpty()) {
                    $organized[$category][$name] = $facilities->groupBy('nama')->first();
                }
            }
            if (empty($organized[$category])) {
                unset($organized[$category]);
            }
        }

        return $organized;
    }

    public function openEditModal(Fasilitas $facility): void
    {
        $this->editingFacility = $facility;
        
        // Get all variants of this facility
        $variants = Fasilitas::where('nama', $facility->nama)->get();
        
        $this->editFormData = [
            'nama' => $facility->nama,
            'kapasitas' => $facility->kapasitas,
            'keterangan' => $facility->keterangan,
            'status' => $facility->status,
            'harga_weekday_internal' => $variants->where('day', 'Weekday')->where('jenis_user', 'Internal')->where('menginap', 'Menginap')->first()->harga ?? 0,
            'harga_weekday_eksternal' => $variants->where('day', 'Weekday')->where('jenis_user', 'Eksternal')->where('menginap', 'Menginap')->first()->harga ?? 0,
            'harga_weekend_internal' => $variants->where('day', 'Weekend')->where('jenis_user', 'Internal')->where('menginap', 'Menginap')->first()->harga ?? 0,
            'harga_weekend_eksternal' => $variants->where('day', 'Weekend')->where('jenis_user', 'Eksternal')->where('menginap', 'Menginap')->first()->harga ?? 0,
        ];
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingFacility = null;
        $this->editFormData = [];
    }

    public function saveFacility(): void
    {
        if (!$this->editingFacility) {
            return;
        }

        try {
            // Update common fields for all variants
            Fasilitas::where('nama', $this->editingFacility->nama)
                ->update([
                    'kapasitas' => $this->editFormData['kapasitas'],
                    'keterangan' => $this->editFormData['keterangan'],
                    'status' => $this->editFormData['status'],
                ]);
            
            // Update price for each specific variant
            Fasilitas::where('nama', $this->editingFacility->nama)
                ->where('day', 'Weekday')
                ->where('jenis_user', 'Internal')
                ->where('menginap', 'Menginap')
                ->update(['harga' => $this->editFormData['harga_weekday_internal']]);
            
            Fasilitas::where('nama', $this->editingFacility->nama)
                ->where('day', 'Weekday')
                ->where('jenis_user', 'Eksternal')
                ->where('menginap', 'Menginap')
                ->update(['harga' => $this->editFormData['harga_weekday_eksternal']]);
            
            Fasilitas::where('nama', $this->editingFacility->nama)
                ->where('day', 'Weekend')
                ->where('jenis_user', 'Internal')
                ->where('menginap', 'Menginap')
                ->update(['harga' => $this->editFormData['harga_weekend_internal']]);
            
            Fasilitas::where('nama', $this->editingFacility->nama)
                ->where('day', 'Weekend')
                ->where('jenis_user', 'Eksternal')
                ->where('menginap', 'Menginap')
                ->update(['harga' => $this->editFormData['harga_weekend_eksternal']]);

            $this->closeEditModal();

            Notification::make()
                ->title('Berhasil')
                ->body('Fasilitas berhasil diperbarui')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal')
                ->body('Gagal memperbarui fasilitas: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
