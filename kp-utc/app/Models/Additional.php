<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Additional extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file Additional & nama table bukan additionals jadi hrs dideklarasi
    protected $table = 'additional';

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

    //deklarasi bahwa field additional_id di tabel PemesananAdditional adalah milik tabel Additional
    public function pemesananAdditional()
    {
        return $this->hasMany(PemesananAdditional::class, 'additional_id');
    }
}
