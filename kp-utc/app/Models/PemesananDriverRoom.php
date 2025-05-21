<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananDriverRoom extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file PemesananDriverRoom & nama table bukan pemesanandriverrooms jadi hrs dideklarasi
    protected $table = 'pemesanan_driver_room';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'reservasi_id',
        'driver_room_id',
        'jumlah'
    ];

    //deklarasi bahwa driver_room_id di table ini adalah milik table DriverRoom
    public function driverRoom()
    {
        return $this->belongsTo(DriverRoom::class, 'driver_room_id');
    }

    //deklarasi bahwa reservasi_id di table ini adalah milik table Reservasi
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
}
