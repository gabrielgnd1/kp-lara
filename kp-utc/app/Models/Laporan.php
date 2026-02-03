<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Laporan extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file Laporan & nama table bukan laporans jadi hrs dideklarasi
    protected $table = 'laporan';
    public $timestamps = false;

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama_laporan',
        'deskripsi',
        'foto_laporan',
        'decision',
        'prioritas',
        'tanggal_lapor',
        'tanggal_selesai',
        'tanggal_deadline',
        'tipe_laporan',
        'notifikasi',
        'user_id',
        'area_id'
    ];

    // Cast foto_laporan as JSON array
    protected $casts = [
        'foto_laporan' => 'array',
    ];

    //deklarasi bahwa id_role di table ini adalah milik table Role
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //deklarasi bahwa field laporan_id di tabel DiskusiLaporan adalah milik tabel Laporan
    public function diskusiLaporan()
    {
        return $this->hasMany(DiskusiLaporan::class, 'laporan_id');
    }

    //deklarasi bahwa field laporan_id di tabel LogLaporan adalah milik tabel Laporan
    public function loglaporan()
    {
        return $this->hasMany(LogLaporan::class, 'laporan_id');
    }

    //deklarasi bahwa area_idarea di table ini adalah milik table Area
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public static function generateKodeLaporan($tanggalLapor)
    {
        // Parse laporan date
        $date = Carbon::parse($tanggalLapor);
        $month = $date->format('m'); // 01-12
        $year = $date->format('y');  // 26 for 2026
        $fullYear = $date->format('Y'); // 2026
        
        // Get the latest laporan for the same year
        $latestLaporan = static::whereYear('tanggal_lapor', $fullYear)
            ->orderBy('id', 'desc')
            ->first();
        
        // Extract counter from kode_laporan if exists
        $nextNumber = 1;
        if ($latestLaporan && $latestLaporan->kode_laporan) {
            // Extract the 4-digit counter from kode (position 2-5 after 'ME')
            $currentCounter = (int) substr($latestLaporan->kode_laporan, 2, 4);
            $nextNumber = $currentCounter + 1;
        }
        
        // Ensure it's 4 digits, max 9999
        $counter = str_pad(min($nextNumber, 9999), 4, '0', STR_PAD_LEFT);
        
        return "ME{$counter}{$month}{$year}";
    }
}