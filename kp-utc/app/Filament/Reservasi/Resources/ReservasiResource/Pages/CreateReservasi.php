<?php

namespace App\Filament\Reservasi\Resources\ReservasiResource\Pages;

use App\Filament\Reservasi\Resources\ReservasiResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateReservasi extends CreateRecord
{
    protected static string $resource = ReservasiResource::class;

    public function getTitle(): string
    {
        return 'Tambah Reservasi';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // status_pembayaran sudah langsung dari form, tidak perlu transform
        // Radio hanya allow DP & LUNAS, jadi sudah pasti valid
        
        // Hanya kolom tabel reservasi
        return [
            'nama_pemesan' => $data['nama_pemesan'] ?? null,
            'no_telepon' => $data['no_telepon'] ?? null,
            'email' => $data['email'] ?? null,
            'judul_kegiatan' => $data['judul_kegiatan'] ?? null,
            'waktu_check_in' => $data['waktu_check_in'] ?? null,
            'waktu_check_out' => $data['waktu_check_out'] ?? null,
            'jumlah_laki' => $data['jumlah_laki'] ?? 0,
            'jumlah_perempuan' => $data['jumlah_perempuan'] ?? 0,
            'informasi_tambahan' => $data['informasi_tambahan'] ?? '',
            'status_reservasi' => $data['status_reservasi'] ?? 'NOT ACC',
            'status_pembayaran' => $data['status_pembayaran'] ?? 'DP',
            'tanggal_dibuat' => $data['tanggal_dibuat'] ?? now(),
            'id_pic_ioc' => $data['id_pic_ioc'] ?? null,
            'id_pic_utc' => $data['id_pic_utc'] ?? null,
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $modelClass = static::getModel();   // App\Models\Reservasi
            return $modelClass::create($data);
        });
    }

    protected function afterCreate(): void
    {
        // Ambil SEMUA state (hidden mirror juga ikut)
        $state = $this->form->getRawState();

        // Bersihkan baris hantu yang mungkin sudah keburu dibuat
        \DB::table('pemesanan_fasilitas')
            ->where('reservasi_id', $this->record->getKey())
            ->where(function ($q) {
                $q->whereNull('fasilitas_id')->orWhere('fasilitas_id', 0);
            })
            ->delete();

        // Pastikan relasi benar-benar bersih di level Eloquent
        $this->record->fasilitas()->detach();

        // Tulis ulang pivot dari mirror state (punya guard id > 0)
        \App\Filament\Reservasi\Resources\ReservasiResource::syncPivotsFromFormState(
            $this->record,
            $state
        );

        // Safety net terakhir: kalau MASIH ada 0, hapus lagi
        \DB::table('pemesanan_fasilitas')
            ->where('reservasi_id', $this->record->getKey())
            ->where(function ($q) {
                $q->whereNull('fasilitas_id')->orWhere('fasilitas_id', 0);
            })
            ->delete();

        // --- Tambahan: tulis items qty per-orang (JIKA memang mau simpan detail) ---
        if (method_exists($this->record, 'items')) {
            $state = $this->form->getRawState();

            $perPerson = ['avocado cottage', 'banana cottage', 'durian cottage', 'cassava cottage'];
            $jml = max(1, (int) ($state['jumlah_orang'] ?? 1));

            $selectedKeys = array_keys(array_filter($state['fasilitas_selected'] ?? []));
            foreach ($selectedKeys as $slug) {
                $qty = in_array($slug, $perPerson, true) ? $jml : 1;

                $this->record->items()->create([
                    'cottage_slug' => $slug,
                    'qty' => $qty,
                    // Sesuaikan cara ambil harga_satuan. Kalau belum ada helper, boleh kosongkan dulu atau hitung manual.
                    // 'harga_satuan'  => static::hargaCottage($slug, $state['hari_tipe']),
                ]);
            }
        }

        // --- Simpan file ke field reservasi table ---
        $updates = [];
        if (!empty($state['file_reservation_form'])) {
            $updates['file_reservation_form'] = $state['file_reservation_form'];
        }
        if (!empty($state['file_bukti_dp'])) {
            $updates['file_bukti_dp'] = $state['file_bukti_dp'];
        }
        if (!empty($state['file_bukti_lunas'])) {
            $updates['file_bukti_lunas'] = $state['file_bukti_lunas'];
        }
        if (!empty($updates)) {
            $this->record->update($updates);
        }

        \Filament\Notifications\Notification::make()
            ->title('Reservasi berhasil dibuat!')
            ->success()
            ->send();
    }

}
