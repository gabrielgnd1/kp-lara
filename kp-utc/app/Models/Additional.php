<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Additional extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file Additional & nama table bukan additionals jadi hrs dideklarasi
    protected $table = 'additional';

    public $timestamps = false;

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama',
        'harga',
        'deskripsi',
        'status'
    ];

    //deklarasi bahwa field additional_id di tabel PemesananAdditional adalah milik tabel Additional
    public function pemesananAdditional()
    {
        return $this->hasMany(PemesananAdditional::class, 'additional_id');
    }
}
