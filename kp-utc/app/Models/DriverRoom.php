<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverRoom extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file DriverRoom & nama table bukan driverrooms jadi hrs dideklarasi
    protected $table = 'driver_room';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama',
        'internal_weekday_menginap',
        'internal_weekend_menginap',
        'internal_weekday_tidakmenginap',
        'internal_weekend_tidakmenginap',
        'eksternal_weekday_menginap',
        'eksternal_weekend_menginap',
        'eksternal_weekday_tidakmenginap',
        'eksternal_weekend_tidakmenginap',
        'deskripsi',
        'status'
    ];

    //deklarasi bahwa field driver_room_id di tabel PemesananDriverRoom adalah milik tabel DriverRoom
     public function pemesananDriverRoom()
    {
        return $this->hasMany(PemesananDriverRoom::class, 'driver_room_id');
    }
}
