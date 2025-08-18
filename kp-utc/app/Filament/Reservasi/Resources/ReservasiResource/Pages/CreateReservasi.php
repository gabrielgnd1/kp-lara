<?php

namespace App\Filament\Reservasi\Resources\ReservasiResource\Pages;

use App\Filament\Reservasi\Resources\ReservasiResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class CreateReservasi extends CreateRecord
{
    protected static string $resource = ReservasiResource::class;

    /**
     * Keep only DB columns and map any different field names if needed.
     * Your table has:
     * id, nama_pemesan, no_telepon, email, judul_kegiatan,
     * waktu_check_in, waktu_check_out, jumlah_laki, jumlah_perempuan,
     * informasi_tambahan, status_reservasi, status_pembayaran,
     * tanggal_dibuat, id_pic_ioc, id_pic_utc
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return [
            'nama_pemesan'       => $data['nama_pemesan'] ?? null,
            'no_telepon'         => $data['no_telepon'] ?? null,
            'email'              => $data['email'] ?? null,
            'judul_kegiatan'     => $data['judul_kegiatan'] ?? null,
            'waktu_check_in'     => $data['waktu_check_in'] ?? null,
            'waktu_check_out'    => $data['waktu_check_out'] ?? null,
            'jumlah_laki'        => $data['jumlah_laki'] ?? 0,           // field name now matches Resource
            'jumlah_perempuan'   => $data['jumlah_perempuan'] ?? 0,
            'informasi_tambahan' => $data['informasi_tambahan'] ?? '',
            'status_reservasi'   => $data['status_reservasi'] ?? 'NOT ACC',
            'status_pembayaran'  => $data['status_pembayaran'] ?? 'Belum Bayar',
            'tanggal_dibuat'     => $data['tanggal_dibuat'] ?? now(),
            'id_pic_ioc'         => $data['id_pic_ioc'] ?? null,
            'id_pic_utc'         => $data['id_pic_utc'] ?? null,
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $modelClass = static::getModel();   // App\Models\Reservasi
            return $modelClass::create($data);  // ← INSERT into DB
        });
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Reservasi berhasil dibuat!')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
