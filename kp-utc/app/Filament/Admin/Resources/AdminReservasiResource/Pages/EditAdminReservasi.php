<?php

namespace App\Filament\Admin\Resources\AdminReservasiResource\Pages;

use App\Filament\Admin\Resources\AdminReservasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon;

class EditAdminReservasi extends EditRecord
{
    protected static string $resource = AdminReservasiResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);
        // Set the current editing reservasi ID EARLY for validation
        AdminReservasiResource::$currentEditingReservasiId = (int) $record;
        \Log::debug("EditAdminReservasi::mount() - Set currentEditingReservasiId = " . $record);
        
        // Clear static date cache so validation fetches fresh data
        AdminReservasiResource::clearBookedDateCache();

        // Check if edit is allowed (not within H-3 before check in)
        if (!$this->canEdit()) {
            \Filament\Notifications\Notification::make()
                ->title('Tidak dapat mengedit')
                ->body('Reservasi tidak dapat diedit mulai H-3 sebelum tanggal check in')
                ->danger()
                ->send();
            
            $this->redirect($this->getResource()::getUrl('index'));
        }
    }

    protected function canEdit(): bool
    {
        $checkIn = $this->record->waktu_check_in;
        if (!$checkIn) {
            return true; // Jika tidak ada check in, allow edit
        }

        $checkInDate = Carbon::parse($checkIn)->startOfDay();
        $now = Carbon::now()->startOfDay();
        $daysUntilCheckIn = $now->diffInDays($checkInDate, false); // Negative jika sudah lewat

        // Jika H-3 atau kurang dari check in, tidak bisa edit
        // daysUntilCheckIn <= 2 berarti H-2 atau lebih dekat
        return $daysUntilCheckIn > 2;
    }

    protected function getRedirectUrl(): string
    {
        // setelah Save, balik ke daftar (index)
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Reset the static property
        AdminReservasiResource::$currentEditingReservasiId = null;

        // Ambil seluruh state form (termasuk mirror arrays)
        $state = $this->form->getRawState();

        // Sync pivot dari helper yang sudah kamu punya
        AdminReservasiResource::syncPivotsFromFormState($this->record, $state);

        // --- Tambahan: update qty untuk cottage per-orang (JIKA memang mau simpan detail) ---
        if (method_exists($this->record, 'items')) {
            $state = $this->form->getRawState();
            $perPerson = ['avocado cottage', 'banana cottage', 'durian cottage', 'cassava cottage'];
            $jml = max(1, (int) ($state['jumlah_orang'] ?? 1));

            $this->record->items()
                ->whereIn('cottage_slug', $perPerson)
                ->update(['qty' => $jml]);
        }

        // --- Simpan file ke field reservasi table ---
        if (!empty($state['file_reservation_form'])) {
            $this->record->update(['file_reservation_form' => $state['file_reservation_form']]);
        }

        if (!empty($state['file_bukti_dp'])) {
            $this->record->update(['file_bukti_dp' => $state['file_bukti_dp']]);
        }

        if (!empty($state['file_bukti_lunas'])) {
            $this->record->update(['file_bukti_lunas' => $state['file_bukti_lunas']]);
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record->loadMissing(['fasilitas', 'additional', 'menuMakan']);

        // Set the reservasi_id in hidden field for validation callbacks
        $data['reservasi_id_edit'] = (int) $this->record->id;

        $data['diskon'] = $data['diskon'] ?? (int) ($this->record->diskon ?? 0);

        // ------- FASILITAS (pakai groupKey = strtolower(nama)) -------
        $data['fasilitas_selected'] = [];
        $data['fasilitas_mulai'] = [];
        $data['fasilitas_selesai'] = [];
        $data['fasilitas_jumlah_orang'] = [];
        foreach ($record->fasilitas as $f) {
            $groupKey = trim(mb_strtolower($f->nama));
            $data['fasilitas_selected'][$groupKey] = true;
            $data['fasilitas_mulai'][$groupKey] = $f->pivot->mulai ?? $data['waktu_check_in'] ?? null;
            $data['fasilitas_selesai'][$groupKey] = $f->pivot->selesai ?? $data['waktu_check_out'] ?? null;
            $data['fasilitas_jumlah_orang'][$groupKey] = (int) ($f->pivot->jumlah_orang ?? 1);
        }

        // ------- ADDITIONAL (key = id) -------
        $data['additional_selected'] = [];
        $data['additional_mulai'] = [];
        $data['additional_selesai'] = [];
        foreach ($record->additional as $a) {
            $id = (string) $a->id;
            $data['additional_selected'][$id] = true;
            $data['additional_mulai'][$id] = $a->pivot->mulai ?? $data['waktu_check_in'] ?? null;
            $data['additional_selesai'][$id] = $a->pivot->selesai ?? $data['waktu_check_out'] ?? null;
        }

        // ------- MENU MAKAN -------
        $data['menu_makan_selected'] = [];
        $data['menu_makan_jumlah'] = [];
        foreach ($record->menuMakan as $m) {
            $id = (string) $m->id;
            $data['menu_makan_selected'][$id] = true;
            $data['menu_makan_jumlah'][$id] = (int) ($m->pivot->jumlah ?? 1);
        }

        // Optional: isi default radio & hari_tipe agar section fasilitas tampil
        if ($record->fasilitas->first()) {
            $first = $record->fasilitas->first();
            $data['jenis_member'] = $data['jenis_member'] ?? $first->jenis_user;
            $data['hari_tipe'] = $data['hari_tipe'] ?? $first->day;
        }

        $data['harga_akhir'] = $data['harga_akhir'] ?? (int) ($this->record->harga_akhir ?? 0);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $state = $this->form->getState();

        $fSel = (array) ($state['fasilitas_selected'] ?? []);
        $fMul = (array) ($state['fasilitas_mulai'] ?? []);
        $fSelis = (array) ($state['fasilitas_selesai'] ?? []);

        $aSel = (array) ($state['additional_selected'] ?? []);
        $aMul = (array) ($state['additional_mulai'] ?? []);
        $aSelis = (array) ($state['additional_selesai'] ?? []);

        $mSel = (array) ($state['menu_makan_selected'] ?? []);
        $mJml = (array) ($state['menu_makan_jumlah'] ?? []);

        $disk = (int) ($data['diskon'] ?? 0);
        $jenis = (string) ($data['jenis_member'] ?? $state['jenis_member'] ?? 'Internal');
        $cin = $data['waktu_check_in'] ?? $state['waktu_check_in'] ?? null;
        $cout = $data['waktu_check_out'] ?? $state['waktu_check_out'] ?? null;

        $data['harga_akhir'] = \App\Filament\Reservasi\Resources\ReservasiResource::hitungTotalHarga(
            $fSel,
            $fMul,
            $fSelis,
            $aSel,
            $aMul,
            $aSelis,
            $mSel,
            $mJml,
            $disk,
            $jenis,
            $cin,
            $cout
        );

        // status_pembayaran sudah langsung di-bind ke radio, tidak perlu logic kompleks
        // Radio hanya allow DP & LUNAS, tidak ada BARU di form edit
        // Jadi status_pembayaran akan otomatis terupdate dari form

        // Buang mirror fields dan radio fields agar tidak disimpan ke kolom non-eksis
        unset(
            $data['fasilitas_selected'],
            $data['fasilitas_mulai'],
            $data['fasilitas_selesai'],
            $data['additional_selected'],
            $data['additional_mulai'],
            $data['additional_selesai'],
            $data['menu_makan_selected'],
            $data['menu_makan_jumlah'],
            $data['hari_tipe'],
            $data['reservasi_id_edit']
        );

        return $data;
    }
}
