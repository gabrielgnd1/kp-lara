<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampingGround extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file CampingGround & nama table bukan campinggrounds jadi hrs dideklarasi
    protected $table = 'camping_ground';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama',
        'kapasitas',
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

    //deklarasi bahwa field camping_ground_id di tabel PemesananCampingGround adalah milik tabel CampingGround
    public function reservasi()
    {
        return $this->hasMany(PemesananCampingGround::class, 'camping_ground_id');
    }
}
