<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiskusiLaporan extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file DiskusiLaporan & nama table bukan diskusilaporans jadi hrs dideklarasi
    protected $table = 'diskusi_laporan';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'user_id',
        'laporan_id',
        'diskusi'
    ];

    //deklarasi bahwa user_id di table ini adalah milik table User
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //deklarasi bahwa id_role di table ini adalah milik table Role
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    //deklarasi bahwa field diskusi_laporan_id di tabel LogLaporan adalah milik tabel DiskusiLaporan
    public function logLaporan()
    {
        return $this->hasMany(LogLaporan::class, 'diskusi_laporan_id');
    }
}
