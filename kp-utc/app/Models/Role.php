<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file Role & nama table bukan roles jadi hrs dideklarasi
    protected $table = 'role';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama'
    ];

    //deklarasi bahwa field id_role di tabel User adalah milik tabel Role
     public function user()
    {
        return $this->hasOne(User::class, 'id_role');
    }
}
