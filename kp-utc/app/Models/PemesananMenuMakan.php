<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananMenuMakan extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file PemesananMenuMakan & nama table bukan pemesananmenumakans jadi hrs dideklarasi
    protected $table = 'pemesanan_menu_makan';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'reservasi_id',
        'menu_makan_id',
        'jumlah',
        'tanggal_waktu'
    ];

    //deklarasi bahwa menu_makan_id di table ini adalah milik table MenuMakan
    public function menuMakan()
    {
        return $this->belongsTo(MenuMakan::class, 'menu_makan_id');
    }

    //deklarasi bahwa reservasi_id di table ini adalah milik table Reservasi
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
}
