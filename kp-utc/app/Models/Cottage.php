<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cottage extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file Cottage & nama table bukan cottages jadi hrs dideklarasi
    protected $table = 'cottage';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama',
        'tipe',
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

    //deklarasi bahwa field cottage_id di tabel PemesananCottage adalah milik tabel Cottage
     public function cottages()
    {
        return $this->hasMany(PemesananCottage::class, 'cottage_id');
    }
}
