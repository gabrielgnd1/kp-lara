<?php

namespace App\Filament\Lapangan\Resources\LaporanResource\Pages;

use App\Filament\Lapangan\Resources\LaporanResource;
use App\Models\DiskusiLaporan;
use App\Models\Laporan;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class DiscussionLaporan extends Page
{
    protected static string $resource = LaporanResource::class;
    protected static string $view = 'filament.lapangan.resources.laporan-resource.pages.discussion-laporan';

    public ?Laporan $record = null;
    public $discussions = [];
    public $newDiskusi = '';

    public function mount(): void
    {
        $recordId = request()->route('record');
        $this->record = Laporan::findOrFail($recordId);
        $this->loadDiscussions();
    }

    public function loadDiscussions()
    {
        if ($this->record) {
            $this->discussions = $this->record->diskusiLaporan()
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray();
        }
    }

    public function addDiskusi()
    {
        if (empty(trim($this->newDiskusi))) {
            session()->flash('error', 'Diskusi tidak boleh kosong!');
            return;
        }

        try {
            DiskusiLaporan::create([
                'user_id' => Auth::id(),
                'laporan_id' => $this->record->id,
                'diskusi' => $this->newDiskusi
            ]);

            $this->newDiskusi = '';
            $this->loadDiscussions();
            session()->flash('success', 'Diskusi berhasil ditambahkan!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menambahkan diskusi: ' . $e->getMessage());
        }
    }

    public function getTitle(): string
    {
        return 'Diskusi - ' . ($this->record?->nama_laporan ?? 'Laporan');
    }
}
