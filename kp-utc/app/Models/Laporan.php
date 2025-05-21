<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file Laporan & nama table bukan laporans jadi hrs dideklarasi
    protected $table = 'laporan';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama_laporan',
        'foto_laporan',
        'decision',
        'prioritas',
        'tanggal_lapor',
        'tanggal_selesai',
        'tanggal_deadline',
        'tipe_laporan',
        'notifikasi',
        'user_id',
        'area_idarea'
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
        return $this->belongsTo(Area::class, 'area_idarea');
    }
}
