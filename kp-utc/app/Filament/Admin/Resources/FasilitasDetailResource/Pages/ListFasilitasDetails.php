<?php

namespace App\Filament\Admin\Resources\FasilitasDetailResource\Pages;

use App\Filament\Admin\Resources\FasilitasDetailResource;
use Filament\Resources\Pages\Page;
use App\Models\Fasilitas;
use App\Models\Reservasi;
use App\Models\PemesananFasilitas;
use Carbon\Carbon;
use Livewire\Attributes\Computed;

class ListFasilitasDetails extends Page
{
    protected static string $resource = FasilitasDetailResource::class;
    protected static string $view = 'filament.admin.resources.fasilitas-detail-resource.pages.list-fasilitas-details';

    public ?string $tanggal_mulai = null;
    public ?string $tanggal_selesai = null;
    public bool $hasSearched = false;

    public function getTitle(): string
    {
        return 'Detail Fasilitas';
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->getResource()::getUrl() => 'Detail Fasilitas',
            '#' => 'Daftar',
        ];
    }

    public function search(): void
    {
        $this->validate([
            'tanggal_mulai' => 'required|date_format:Y-m-d\TH:i',
            'tanggal_selesai' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Validate that end date is after start date
        $mulai = Carbon::createFromFormat('Y-m-d\TH:i', $this->tanggal_mulai);
        $selesai = Carbon::createFromFormat('Y-m-d\TH:i', $this->tanggal_selesai);

        if ($selesai->lte($mulai)) {
            $this->addError('tanggal_selesai', 'Tanggal selesai harus lebih besar dari tanggal mulai');
            return;
        }

        $this->hasSearched = true;
    }

    #[Computed]
    public function availableFacilities(): array
    {
        if (!$this->hasSearched || !$this->tanggal_mulai || !$this->tanggal_selesai) {
            return [];
        }

        $mulai = Carbon::createFromFormat('Y-m-d\TH:i', $this->tanggal_mulai);
        $selesai = Carbon::createFromFormat('Y-m-d\TH:i', $this->tanggal_selesai);

        // Get all facilities with status Available
        $allFacilities = Fasilitas::where('status', 'Available')->get();

        $availableFacilities = [];

        foreach ($allFacilities as $fasilitas) {
            // Check if this facility is booked during the requested period
            $isBooked = PemesananFasilitas::whereHas('reservasi', function ($query) use ($mulai, $selesai) {
                // Check if there's any overlap between the requested period and reservasi period
                $query->where(function ($q) use ($mulai, $selesai) {
                    $q->where('waktu_check_in', '<', $selesai)
                      ->where('waktu_check_out', '>', $mulai);
                });
            })
            ->where('fasilitas_id', $fasilitas->id)
            ->exists();

            // If not booked during this period, add to available list
            if (!$isBooked) {
                $availableFacilities[] = $fasilitas;
            }
        }

        // Group by category (same as ManageFasilitasCards logic)
        $categories = [
            'hall' => [],
            'cottage' => [],
            'vip_cottage' => [],
            'others' => [],
        ];

        foreach ($availableFacilities as $fasilitas) {
            $nama_lower = strtolower($fasilitas->nama);

            if (str_contains($nama_lower, 'hall')) {
                $categories['hall'][] = $fasilitas;
            } elseif (str_contains($nama_lower, 'vip cottage') || str_contains($nama_lower, 'vip')) {
                $categories['vip_cottage'][] = $fasilitas;
            } elseif (str_contains($nama_lower, 'cottage')) {
                $categories['cottage'][] = $fasilitas;
            } else {
                $categories['others'][] = $fasilitas;
            }
        }

        return $categories;
    }
}
