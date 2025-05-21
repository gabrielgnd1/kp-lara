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

    //deklarasi bahwa cottage_id di table ini adalah milik table Cottage
    public function cottage()
    {
        return $this->belongsTo(Cottage::class, 'cottage_id');
    }
}
