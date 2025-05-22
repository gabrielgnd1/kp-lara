<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaFasilitas extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file HargaFasilitas & nama table bukan hargafasilitass jadi hrs dideklarasi
    protected $table = 'harga_fasilitas';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'jenis_user',
        'day',
        'harga'
    ];

    
    //deklarasi bahwa field harga_fasilitas_id di tabel Fasilitas adalah milik tabel HargaFasilitas
    public function fasilitas()
    {
        return $this->hasMany(Fasilitas::class, 'harga_fasilitas_id');
    }
}
