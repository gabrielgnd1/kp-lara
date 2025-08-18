<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    // Table name & timestamps
    protected $table = 'reservasi';
    public $timestamps = false;

    // Only columns that actually exist in your DB
    protected $fillable = [
        'nama_pemesan',
        'no_telepon',
        'email',
        'judul_kegiatan',
        'waktu_check_in',
        'waktu_check_out',
        'jumlah_laki',        // ← matches DB
        'jumlah_perempuan',
        'informasi_tambahan',
        'status_reservasi',
        'status_pembayaran',
        'tanggal_dibuat',
        'id_pic_ioc',
        'id_pic_utc',
    ];

    protected $casts = [
        'waktu_check_in'  => 'datetime',
        'waktu_check_out' => 'datetime',
        'tanggal_dibuat'  => 'datetime',
    ];

    // Relationships (keep if you use them elsewhere)
    public function pic_ioc()
    {
        return $this->belongsTo(User::class, 'id_pic_ioc');
    }

    public function pic_utc()
    {
        return $this->belongsTo(User::class, 'id_pic_utc');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'reservasi_id');
    }

    public function dokumenReservasi()
    {
        return $this->hasMany(DokumenReservasi::class, 'reservasi_id');
    }

    public function pemesananFasilitas()
    {
        return $this->hasMany(PemesananFasilitas::class, 'reservasi_id');
    }

    public function pemesananAdditional()
    {
        return $this->hasMany(PemesananAdditional::class, 'reservasi_id');
    }

    public function pemesananMenuMakan()
    {
        return $this->hasMany(PemesananMenuMakan::class, 'reservasi_id');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(
            Fasilitas::class,
            'pemesanan_fasilitas',
            'reservasi_id',
            'fasilitas_id'
        )->withPivot('jumlah');
    }

    public function additional()
    {
        return $this->belongsToMany(Additional::class, 'pemesanan_additional', 'reservasi_id', 'additional_id');
    }

    public function menuMakan()
    {
        return $this->belongsToMany(MenuMakan::class, 'pemesanan_menu_makan', 'reservasi_id', 'menu_makan_id');
    }
}
