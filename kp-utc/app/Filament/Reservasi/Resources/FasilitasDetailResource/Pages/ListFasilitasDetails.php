<?php

namespace App\Filament\Reservasi\Resources\FasilitasDetailResource\Pages;

use App\Filament\Reservasi\Resources\FasilitasDetailResource;
use Filament\Resources\Pages\Page;
use App\Models\Fasilitas;
use App\Models\PemesananFasilitas;
use Carbon\Carbon;
use Livewire\Attributes\Computed;

class ListFasilitasDetails extends Page
{
    protected static string $resource = FasilitasDetailResource::class;
    protected static string $view = 'filament.reservasi.resources.fasilitas-detail-resource.pages.list-fasilitas-details';

    public ?string $tanggal_mulai = null;
    public ?string $tanggal_selesai = null;
    public bool $hasSearched = false;
    public array $expandedGroups = ['hall' => true, 'cottage' => true, 'vip_cottage' => true, 'others' => true];

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

        $mulai = Carbon::createFromFormat('Y-m-d\TH:i', $this->tanggal_mulai);
        $selesai = Carbon::createFromFormat('Y-m-d\TH:i', $this->tanggal_selesai);

        if ($selesai->lte($mulai)) {
            $this->addError('tanggal_selesai', 'Tanggal selesai harus lebih besar dari tanggal mulai');
            return;
        }

        $this->hasSearched = true;
    }

    #[Computed]
    public function facilities(): array
    {
        if (!$this->hasSearched || !$this->tanggal_mulai || !$this->tanggal_selesai) {
            return [];
        }

        try {
            $mulai = Carbon::createFromFormat('Y-m-d\TH:i', $this->tanggal_mulai);
            $selesai = Carbon::createFromFormat('Y-m-d\TH:i', $this->tanggal_selesai);
        } catch (\Exception $e) {
            // Fallback: try parsing as ISO format
            $mulai = Carbon::parse($this->tanggal_mulai);
            $selesai = Carbon::parse($this->tanggal_selesai);
        }

        \Log::info('Facility search:', [
            'tanggal_mulai_raw' => $this->tanggal_mulai,
            'tanggal_selesai_raw' => $this->tanggal_selesai,
            'mulai_parsed' => $mulai->toString(),
            'selesai_parsed' => $selesai->toString(),
        ]);

        $allFacilities = Fasilitas::orderBy('nama')->orderBy('day')->orderBy('jenis_user')->get();

        // Get unique facilities by name
        $uniqueFacilities = $allFacilities->unique('nama')->values();

        $categorized = [
            'hall' => [],
            'cottage' => [],
            'vip_cottage' => [],
            'others' => [],
        ];

        foreach ($uniqueFacilities as $fasilitas) {
            // Get ALL variants for this facility (Weekday/Weekend, Eksternal/Internal)
            $variants = $allFacilities->where('nama', $fasilitas->nama);
            
            // Check availability for EACH VARIANT separately
            foreach ($variants as $variant) {
                $isBooked = PemesananFasilitas::whereHas('reservasi', function ($query) use ($mulai, $selesai) {
                    $query->where('waktu_check_in', '<', $selesai)
                          ->where('waktu_check_out', '>', $mulai);
                })->where('fasilitas_id', $variant->id)
                ->exists();

                $isAvailable = ($variant->status === 'Available' && !$isBooked);
                $variant->setAttribute('is_available_for_period', $isAvailable);
            }
            
            \Log::info("Facility {$fasilitas->nama} - Variants count: " . $variants->count());

            $nama_lower = strtolower($fasilitas->nama);

            if (str_contains($nama_lower, 'hall') || 
                str_contains($nama_lower, 'welirang') || 
                str_contains($nama_lower, 'cinnamon') || 
                str_contains($nama_lower, 'arjuna') || 
                str_contains($nama_lower, 'pendapa')) {
                $key = $fasilitas->nama;
                if (!isset($categorized['hall'][$key])) {
                    $categorized['hall'][$key] = [];
                }
                $categorized['hall'][$key] = $variants->values();
            } elseif (str_contains($nama_lower, 'vip cottage') || str_contains($nama_lower, 'vip')) {
                $key = $fasilitas->nama;
                if (!isset($categorized['vip_cottage'][$key])) {
                    $categorized['vip_cottage'][$key] = [];
                }
                $categorized['vip_cottage'][$key] = $variants->values();
            } elseif (str_contains($nama_lower, 'cottage')) {
                $key = $fasilitas->nama;
                if (!isset($categorized['cottage'][$key])) {
                    $categorized['cottage'][$key] = [];
                }
                $categorized['cottage'][$key] = $variants->values();
            } else {
                $key = $fasilitas->nama;
                if (!isset($categorized['others'][$key])) {
                    $categorized['others'][$key] = [];
                }
                $categorized['others'][$key] = $variants->values();
            }
        }

        return $categorized;
    }
}
