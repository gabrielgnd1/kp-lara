<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Reservasi extends Model
{
    // Table name & timestamps
    protected $table = 'reservasi';
    public $timestamps = false;

    // Only columns that actually exist in your DB
    protected $fillable = [
        'nama_pemesan','no_telepon','email','judul_kegiatan',
        'waktu_check_in','waktu_check_out',
        'jumlah_laki','jumlah_perempuan','informasi_tambahan',
        'diskon', 'harga_akhir',
        'status_pembayaran','tanggal_dibuat',
        // intentionally exclude: status_reservasi, id_pic_ioc, id_pic_utc
    ];

     protected $guarded = ['status_reservasi','id_pic_ioc','id_pic_utc'];

    protected $casts = [
        'waktu_check_in'  => 'datetime',
        'waktu_check_out' => 'datetime',
        'tanggal_dibuat'  => 'datetime',
        'jumlah_laki' => 'integer',
        'jumlah_perempuan' => 'integer',
        'diskon'         => 'decimal:2',
        'harga_akhir'    => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $u = Auth::user();

            // safe defaults
            $model->status_pembayaran   = $model->status_pembayaran   ?? 'BARU';
            $model->jumlah_laki         = $model->jumlah_laki         ?? 0;
            $model->jumlah_perempuan    = $model->jumlah_perempuan    ?? 0;
            $model->informasi_tambahan  = $model->informasi_tambahan  ?? '';

            // role rules
            if ($u && ((int) $u->id_role === 2 || optional($u->role)->nama === 'Admin UTC')) {
                $model->status_reservasi = 'ACC';
                $model->id_pic_utc       = $u->id;
                $model->id_pic_ioc       = $model->id_pic_ioc ?? 0;  // use 0 unless you altered DB to allow NULL
            } elseif ($u && ((int) $u->id_role === 4 || optional($u->role)->nama === 'Admin IOC')) {
                $model->status_reservasi = 'NOT ACC';
                $model->id_pic_ioc       = $u->id;
                $model->id_pic_utc       = $model->id_pic_utc ?? 0;
            } else {
                $model->status_reservasi = 'NOT ACC';
                $model->id_pic_ioc       = $model->id_pic_ioc ?? 0;
                $model->id_pic_utc       = $model->id_pic_utc ?? 0;
            }

            // explicit timestamp (Jakarta)
            $model->tanggal_dibuat = $model->tanggal_dibuat ?? Carbon::now('Asia/Jakarta');
        });

        static::deleting(function (Reservasi $reservasi) {
            // Kalau soft delete & ingin pivot langsung hilang, biarkan di 'deleting'.
            // Jika ingin pivot baru hilang saat forceDelete, pindah ke static::forceDeleted(...)
            $reservasi->fasilitas()->detach();
            $reservasi->additional()->detach();
            $reservasi->menuMakan()->detach();
        });
    }

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
    return $this->belongsToMany(MenuMakan::class, 'pemesanan_menu_makan', 'reservasi_id', 'menu_makan_id')
        ->withPivot('jumlah');
}
}
