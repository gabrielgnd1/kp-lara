<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuMakan extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file MenuMakan & nama table bukan menumakans jadi hrs dideklarasi
    protected $table = 'menu_makan';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama',
        'harga',
        'deskripsi',
        'status'
    ];

    //deklarasi bahwa field menu_makan_id di tabel PemesananMenuMakan adalah milik tabel MenuMakan
    public function pemesananMenuMakan()
    {
        return $this->hasMany(PemesananMenuMakan::class, 'menu_makan_id');
    }
}
