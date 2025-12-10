<?php

namespace App\Filament\Admin\Resources\SuperAdminFasilitasResource\Pages;

use App\Filament\Admin\Resources\SuperAdminFasilitasResource;
use App\Models\Fasilitas;
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
        $this->editFormData = [
            'nama' => $facility->nama,
            'kapasitas' => $facility->kapasitas,
            'keterangan' => $facility->keterangan,
            'harga' => $facility->harga,
            'status' => $facility->status,
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
            // Update all variants of this facility with the same name
            Fasilitas::where('nama', $this->editingFacility->nama)
                ->update([
                    'kapasitas' => $this->editFormData['kapasitas'],
                    'keterangan' => $this->editFormData['keterangan'],
                    'harga' => $this->editFormData['harga'],
                    'status' => $this->editFormData['status'],
                ]);

            $this->closeEditModal();

            Notification::make()
                ->title('Success')
                ->body('Facility updated successfully')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to update facility: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
