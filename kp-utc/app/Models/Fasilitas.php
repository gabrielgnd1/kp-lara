<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file Fasilitas & nama table bukan fasilitass jadi hrs dideklarasi
    protected $table = 'fasilitas';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama',
        'kapasitas',
        'keterangan',
        'harga_fasilitas_id',
        'status'
    ];

    //deklarasi bahwa field cottage_id di tabel PemesananFasilitas adalah milik tabel Fasilitas
     public function pemesananFasilitas()
    {
        return $this->hasMany(PemesananFasilitas::class, 'fasilitas_id');
    }

    //deklarasi bahwa field harga_fasilitas_id di tabel ini adalah milik table HargaFasilitas
    public function hargaFasilitas()
    {
        return $this->belongsTo(HargaFasilitas::class, 'harga_fasilitas_id');
    }
}
