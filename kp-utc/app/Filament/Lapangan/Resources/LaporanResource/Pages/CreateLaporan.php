<?php

namespace App\Filament\Lapangan\Resources\LaporanResource\Pages;

use App\Filament\Lapangan\Resources\LaporanResource;
use App\Models\Laporan;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporan extends CreateRecord
{
    protected static string $resource = LaporanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate kode_laporan
        $data['kode_laporan'] = Laporan::generateKodeLaporan($data['tanggal_lapor']);
        
        // Ensure user_id is set
        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
