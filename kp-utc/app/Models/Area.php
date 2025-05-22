<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file Area & nama table bukan areas jadi hrs dideklarasi
    protected $table = 'area';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama_area'
    ];

    //deklarasi bahwa field area_id di tabel Laporan adalah milik tabel Area
     public function laporan()
    {
        return $this->hasMany(Laporan::class, 'area_id');
    }
}
