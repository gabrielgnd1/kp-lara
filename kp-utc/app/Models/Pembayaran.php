<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file Pembayaran & nama table bukan pembayarans jadi hrs dideklarasi
    protected $table = 'pembayaran';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'reservasi_id',
        'jenis',
        'bukti_pembayaran',
        'tanggal_pembayaran'
    ];

    //deklarasi bahwa reservasi_id di table ini adalah milik table Reservasi
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
    
}
