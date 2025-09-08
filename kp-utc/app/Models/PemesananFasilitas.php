<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananFasilitas extends Model
{
    protected $table = 'pemesanan_fasilitas';
    public $timestamps = false;

    protected $fillable = [
        'reservasi_id',
        'fasilitas_id', // <-- must be fasilitas_id, not cottage_id
        'jumlah',
    ];

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'fasilitas_id');
    }

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
}
    