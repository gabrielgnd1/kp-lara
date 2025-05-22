<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogLaporan extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file LogLaporan & nama table bukan loglaporans jadi hrs dideklarasi
    protected $table = 'log_laporan';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'user_id',
        'diskusi_laporan',
        'laporan_id'
    ];

    //deklarasi bahwa diskusi_laporan_user_id & diskusi_laporan_laporan_id di table ini adalah milik table DiskusiLaporan
    public function diskusiLaporan()
    {
        return $this->hasOne(DiskusiLaporan::class, 
            ['user_id', 'laporan_id'], 
            ['diskusi_laporan_user_id', 'diskusi_laporan_laporan_id']
        );
    }

    //deklarasi bahwa user_id di table ini adalah milik table DiskusiLaporan
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //deklarasi bahwa laporan_id di table ini adalah milik table Laporan
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
}
