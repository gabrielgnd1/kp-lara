<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Laporan;
use App\Models\DiskusiLaporan;
use Illuminate\Support\Facades\Auth;

class LaporanDiscussion extends Component
{
    public $laporan;
    public $diskusiList = [];
    public $newDiskusi = '';
    public $showModal = false;

    public function mount($laporanId)
    {
        $this->laporan = Laporan::findOrFail($laporanId);
        $this->loadDiskusi();
    }

    public function loadDiskusi()
    {
        if ($this->laporan) {
            $this->diskusiList = $this->laporan->diskusiLaporan()
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray();
        }
    }

    public function addDiskusi()
    {
        if (empty(trim($this->newDiskusi))) {
            $this->dispatch('show-message', message: 'Diskusi tidak boleh kosong', type: 'error');
            return;
        }

        try {
            DiskusiLaporan::create([
                'user_id' => Auth::id(),
                'laporan_id' => $this->laporan->id,
                'diskusi' => $this->newDiskusi
            ]);

            $this->newDiskusi = '';
            $this->loadDiskusi();
            $this->dispatch('diskusi-added');
        } catch (\Exception $e) {
            $this->dispatch('show-message', message: 'Gagal menambahkan diskusi: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.laporan-discussion');
    }
}
