<?php

namespace App\Filament\Reservasi\Resources\ReservasiResource\Pages;

use App\Filament\Reservasi\Resources\ReservasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReservasis extends ListRecords
{
    protected static string $resource = ReservasiResource::class;

    public function getTitle(): string
    {
        return 'Daftar Reservasi';
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->getResource()::getUrl() => 'Daftar Reservasi',
            '#' => 'Daftar',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Reservasi')
                ->modalHeading('Tambah Reservasi')
                ->modalWidth('7xl')
                ->mutateFormDataUsing(function (array $data): array {
                    $user = auth()->user();

                    // Try to read the role name in a robust way (adjust to your app)
                    $roleName = $user?->role->name
                        ?? $user?->role_name
                        ?? $user?->role
                        ?? '';

                    // Default base fields
                    $data['status_pembayaran'] = $data['status_pembayaran'] ?? 'BARU';
                    $data['tanggal_dibuat']    = $data['tanggal_dibuat']    ?? now();

                    // Role-based overrides
                    if ($roleName === 'Admin UTC') {
                        $data['id_pic_utc']       = $user?->id;
                        $data['id_pic_ioc']       = null;
                        $data['status_reservasi'] = 'ACC';
                    } elseif ($roleName === 'Admin IOC') {
                        $data['id_pic_ioc']       = $user?->id;
                        $data['id_pic_utc']       = null;
                        $data['status_reservasi'] = 'NOT ACC';
                    } else {
                        // Fallback (if some other role creates reservations)
                        $data['id_pic_ioc']       = $data['id_pic_ioc'] ?? null;
                        $data['id_pic_utc']       = $data['id_pic_utc'] ?? null;
                        $data['status_reservasi'] = $data['status_reservasi'] ?? 'NOT ACC';
                    }

                    return $data;
                })
                ->after(function ($record, array $data): void {
                    // Persist Fasilitas selections to pivot
                    $selectedF = array_filter($data['fasilitas_selected'] ?? []);
                    $qtyF      = $data['fasilitas_jumlah'] ?? [];
                    foreach (array_keys($selectedF) as $fid) {
                        $record->pemesananFasilitas()->create([
                            'fasilitas_id' => (int) $fid,
                            'jumlah'       => max(1, (int) ($qtyF[$fid] ?? 1)),
                        ]);
                    }

                    // Additional
                    $selectedA = array_filter($data['additional_selected'] ?? []);
                    $qtyA      = $data['additional_jumlah'] ?? [];
                    foreach (array_keys($selectedA) as $aid) {
                        $record->pemesananAdditional()->create([
                            'additional_id' => (int) $aid,
                            'jumlah'        => max(1, (int) ($qtyA[$aid] ?? 1)),
                        ]);
                    }

                    // Menu Makan
                    $selectedM = array_filter($data['menu_makan_selected'] ?? []);
                    $qtyM      = $data['menu_makan_jumlah'] ?? [];
                    foreach (array_keys($selectedM) as $mid) {
                        $record->pemesananMenuMakan()->create([
                            'menu_makan_id' => (int) $mid,
                            'jumlah'        => max(1, (int) ($qtyM[$mid] ?? 1)),
                        ]);
                    }
                }),
        ];
    }

    // Removed getTableQuery override. All reservations will be shown for all roles.
}
