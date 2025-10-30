<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laporan;
use App\Models\User;
use Carbon\Carbon;

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user or create one
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'username' => 'testuser',
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'status' => 'Available',
                'id_role' => 2,
            ]);
        }

        // Create sample laporan records
        $laporanData = [
            [
                'nama_laporan' => 'Server Down di Area Utama',
                'tipe_laporan' => 'Technical',
                'prioritas' => 'Tinggi',
                'decision' => 'Selesai',
                'tanggal_lapor' => Carbon::now()->subDays(5),
                'tanggal_selesai' => Carbon::now()->subDay(),
                'tanggal_deadline' => Carbon::now()->addDays(2),
                'user_id' => $user->id,
                'area_id' => 1,
                'notifikasi' => 1,
            ],
            [
                'nama_laporan' => 'Perbaikan Sistem Database',
                'tipe_laporan' => 'Maintenance',
                'prioritas' => 'Sedang',
                'decision' => 'Diproses',
                'tanggal_lapor' => Carbon::now()->subDays(3),
                'tanggal_selesai' => Carbon::now()->addDays(2),
                'tanggal_deadline' => Carbon::now()->addDays(5),
                'user_id' => $user->id,
                'area_id' => 1,
                'notifikasi' => 1,
            ],
            [
                'nama_laporan' => 'Update Security Patches',
                'tipe_laporan' => 'Security',
                'prioritas' => 'Tinggi',
                'decision' => 'Selesai',
                'tanggal_lapor' => Carbon::now()->subDays(2),
                'tanggal_selesai' => Carbon::now(),
                'tanggal_deadline' => Carbon::now()->addDay(),
                'user_id' => $user->id,
                'area_id' => 1,
                'notifikasi' => 1,
            ],
            [
                'nama_laporan' => 'Instalasi Perangkat Baru',
                'tipe_laporan' => 'Hardware',
                'prioritas' => 'Rendah',
                'decision' => 'Belum Diproses',
                'tanggal_lapor' => Carbon::now(),
                'tanggal_selesai' => Carbon::now()->addDays(10),
                'tanggal_deadline' => Carbon::now()->addDays(10),
                'user_id' => $user->id,
                'area_id' => 1,
                'notifikasi' => 1,
            ],
        ];

        foreach ($laporanData as $data) {
            Laporan::create($data);
        }

        $this->command->info('Laporan seeder completed successfully!');
    }
}
