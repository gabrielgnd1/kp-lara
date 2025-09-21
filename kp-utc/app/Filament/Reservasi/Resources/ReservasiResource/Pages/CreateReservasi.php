<?php

namespace App\Filament\Reservasi\Resources\ReservasiResource\Pages;

use App\Filament\Reservasi\Resources\ReservasiResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateReservasi extends CreateRecord
{
    protected static string $resource = ReservasiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Hanya kolom tabel reservasi
        return [
            'nama_pemesan'       => $data['nama_pemesan'] ?? null,
            'no_telepon'         => $data['no_telepon'] ?? null,
            'email'              => $data['email'] ?? null,
            'judul_kegiatan'     => $data['judul_kegiatan'] ?? null,
            'waktu_check_in'     => $data['waktu_check_in'] ?? null,
            'waktu_check_out'    => $data['waktu_check_out'] ?? null,
            'jumlah_laki'        => $data['jumlah_laki'] ?? 0,
            'jumlah_perempuan'   => $data['jumlah_perempuan'] ?? 0,
            'informasi_tambahan' => $data['informasi_tambahan'] ?? '',
            'status_reservasi'   => $data['status_reservasi'] ?? 'NOT ACC',
            'status_pembayaran'  => $data['status_pembayaran'] ?? 'BARU',
            'tanggal_dibuat'     => $data['tanggal_dibuat'] ?? now(),
            'id_pic_ioc'         => $data['id_pic_ioc'] ?? null,
            'id_pic_utc'         => $data['id_pic_utc'] ?? null,
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

    \Filament\Notifications\Notification::make()
        ->title('Reservasi berhasil dibuat!')
        ->success()
        ->send();
}

}
