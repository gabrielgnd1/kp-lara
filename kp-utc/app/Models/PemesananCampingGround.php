<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananCampingGround extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file PemesananCampingGround & nama table bukan pemesanancampinggrounds jadi hrs dideklarasi
    protected $table = 'pemesanan_camping_ground';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'reservasi_id',
        'camping_ground_id',
        'jumlah',
    ];

    //deklarasi bahwa camping_ground_id di table ini adalah milik table CampingGround
    public function campingGround()
    {
        return $this->belongsTo(CampingGround::class, 'camping_ground_id');
    }

    //deklarasi bahwa reservasi_id di table ini adalah milik table Reservasi
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
}
