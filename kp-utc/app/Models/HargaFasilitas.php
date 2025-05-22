<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaFasilitas extends Model
{
    //deklarasi bahwa field harga_fasilitas_id di tabel Fasilitas adalah milik tabel HargaFasilitas
    public function fasilitas()
    {
        return $this->hasMany(Fasilitas::class, 'harga_fasilitas_id');
    }
}
