<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananCottage extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file PemesananCottage & nama table bukan pemesanancottages jadi hrs dideklarasi
    protected $table = 'pemesanan_cottage';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'reservasi_id',
        'cottage_id',
        'jumlah'
    ];

    //deklarasi bahwa fasilitas_id di table ini adalah milik table Fasilitas
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'fasilitas_id');
    }

    //deklarasi bahwa reservasi_id di table ini adalah milik table Reservasi
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
}
