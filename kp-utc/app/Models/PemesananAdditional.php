<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananAdditional extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file PemesananAdditional & nama table bukan pemesananadditionals jadi hrs dideklarasi
    protected $table = 'pemesanan_additional';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'reservasi_id',
        'additional_id',
        'jumlah'
    ];

    //deklarasi bahwa additional_id di table ini adalah milik table Additional
    public function additional()
    {
        return $this->belongsTo(Additional::class, 'additional_id');
    }

    //deklarasi bahwa reservasi_id di table ini adalah milik table Reservasi
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
}
