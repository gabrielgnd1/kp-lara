<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;

class LaporanList extends Component
{
    public $laporanList = [];
    public $selectedLaporan = null;
    public $showDiscussionModal = false;

    public function mount()
    {
        $this->loadLaporan();
    }

    public function loadLaporan()
    {
        // You can customize this to get laporan based on user role or requirements
        $this->laporanList = Laporan::with('user', 'area')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function selectLaporan($laporanId)
    {
        $this->selectedLaporan = $laporanId;
        $this->showDiscussionModal = true;
        $this->dispatch('laporan-selected', laporanId: $laporanId);
    }

    public function closeModal()
    {
        $this->showDiscussionModal = false;
        $this->selectedLaporan = null;
    }

    public function render()
    {
        return view('livewire.laporan-list');
    }
}
