<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;

class ReservasiViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show($id)
    {
        $reservasi = Reservasi::with([
            'fasilitas',
            'additional',
            'menuMakan',
            'pic_utc'
        ])->findOrFail($id);
        
        $data = $reservasi->toArray();
        
        // Add admin user name if available
        if ($reservasi->pic_utc) {
            $data['pic_utc_name'] = $reservasi->pic_utc->name ?? '';
        }
        
        // Add currently logged-in user name
        if (auth()->check()) {
            $data['current_user_name'] = auth()->user()->name ?? '';
        }
        
        // Transform facilities array to include fasilitas details with weekday/weekend split
        if (isset($data['fasilitas'])) {
            // Get facility groups by jenis_user for harga lookup
            $jenis = $reservasi->jenis_member ?? 'Internal';
            $groups = $this->fasilitasGroupedByNamaForMember($jenis);
            
            $data['pemesanan_fasilitas'] = array_map(function($fasilitas) use ($groups) {
                $namaKey = strtolower(trim($fasilitas['nama'] ?? ''));
                $group = $groups[$namaKey] ?? null;
                
                return [
                    'nama_fasilitas' => $fasilitas['nama'] ?? '',
                    'mulai' => $fasilitas['pivot']['mulai'] ?? null,
                    'selesai' => $fasilitas['pivot']['selesai'] ?? null,
                    'jumlah_orang' => $fasilitas['pivot']['jumlah_orang'] ?? null,
                    'harga' => $fasilitas['harga'] ?? 0,
                    'harga_weekday' => $group['harga_weekday'] ?? 0,
                    'harga_weekend' => $group['harga_weekend'] ?? 0,
                ];
            }, $data['fasilitas']);
            unset($data['fasilitas']);
        }
        
        // Transform additional array
        if (isset($data['additional'])) {
            $data['pemesanan_additional'] = array_map(function($additional) {
                return [
                    'nama' => $additional['nama'] ?? '',
                    'mulai' => $additional['pivot']['mulai'] ?? null,
                    'selesai' => $additional['pivot']['selesai'] ?? null,
                    'harga' => $additional['pivot']['harga'] ?? 0,
                ];
            }, $data['additional']);
            unset($data['additional']);
        }
        
        // Transform menu array
        if (isset($data['menu_makan'])) {
            $data['pemesanan_menu_makan'] = array_map(function($menu) {
                return [
                    'nama' => $menu['nama'] ?? '',
                    'jumlah' => $menu['pivot']['jumlah'] ?? 1,
                    'harga' => $menu['harga'] ?? 0,
                ];
            }, $data['menu_makan']);
            unset($data['menu_makan']);
        }
        
        return response()
            ->view('reservasi.pdf', ['reservasi' => $data])
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }
    
    protected function fasilitasGroupedByNamaForMember(string $jenisUser): array
    {
        $rows = \App\Models\Fasilitas::query()
            ->where('status', 'Available')
            ->where('jenis_user', $jenisUser)
            ->get();

        $groups = [];

        foreach ($rows as $r) {
            $key = trim(mb_strtolower($r->nama));

            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'nama' => $r->nama,
                    'id_list' => [],
                    'id_weekday' => null,
                    'id_weekend' => null,
                    'id_canonical' => null,
                    'harga_weekday' => null,
                    'harga_weekend' => null,
                ];
            }

            $groups[$key]['id_list'][] = (int) $r->id;

            $day = strtolower((string) $r->day);
            if ($day === 'weekday') {
                $groups[$key]['id_weekday'] = (int) $r->id;
                $groups[$key]['harga_weekday'] = (int) $r->harga;
            } elseif ($day === 'weekend') {
                $groups[$key]['id_weekend'] = (int) $r->id;
                $groups[$key]['harga_weekend'] = (int) $r->harga;
            }

            if ($groups[$key]['id_canonical'] === null) {
                $groups[$key]['id_canonical'] = $groups[$key]['id_weekday']
                    ?? $groups[$key]['id_weekend']
                    ?? (int) $r->id;
            }
        }

        foreach ($groups as &$g) {
            $g['id_list'] = array_values(array_unique($g['id_list']));
        }

        return $groups;
    }
}