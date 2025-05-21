<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenReservasi extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file DokumenReservasi & nama table bukan dokumenreservasis jadi hrs dideklarasi
    protected $table = 'dokumen_reservasi';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'tipe',
        'file',
        'tanggal_upload',
        'reservasi_id'
    ];

    //deklarasi bahwa reservasi_id di table ini adalah milik table Reservasi
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
}
