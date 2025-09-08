<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananMenuMakan extends Model
{
    protected $table = 'pemesanan_menu_makan';
    public $timestamps = false;

    protected $fillable = [
        'reservasi_id',
        'menu_makan_id',
        'jumlah',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }

    public function menuMakan()
    {
        return $this->belongsTo(MenuMakan::class, 'menu_makan_id');
    }
}
