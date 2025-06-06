<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file Reservasi & nama table bukan reservasis jadi hrs dideklarasi
    protected $table = 'reservasi';
    public $timestamps = false;

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama_pemesan',
        'no_telepon',
        'email',
        'judul_kegiatan',
        'waktu_check_in',
        'waktu_check_out',
        'jumlah_laki_laki',
        'jumlah_perempuan',
        'informasi_tambahan',
        'status_reservasi',
        'status_pembayaran',
        'tanggal_dibuat',
        'id_pic_ioc',
        'id_pic_utc',
        'alamat',
    ];

    //deklarasi bahwa field id_pic_ioc di tabel ini adalah milik table User
    public function pic_ioc()
    {
        return $this->belongsTo(User::class, 'id_pic_ioc');
    }

    //deklarasi bahwa field id_pic_utc di tabel ini adalah milik table User
    public function pic_utc()
    {
        return $this->belongsTo(User::class, 'id_pic_utc');
    }

    //deklarasi bahwa field reservasi_id di tabel Pembayaran adalah milik tabel Reservasi
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'reservasi_id');
    }

    //deklarasi bahwa field reservasi_id di tabel DokumenReservasi adalah milik tabel Reservasi
    public function dokumenReservasi()
    {
        return $this->hasMany(DokumenReservasi::class, 'reservasi_id');
    }

    //deklarasi bahwa field reservasi_id di tabel PemesananFasilitas adalah milik tabel Reservasi
    public function pemesananFasilitas()
    {
        return $this->hasMany(PemesananFasilitas::class, 'reservasi_id');
    }

    //deklarasi bahwa field reservasi_id di tabel PemesananAdditional adalah milik tabel Reservasi
    public function pemesananAdditional()
    {
        return $this->hasMany(PemesananAdditional::class, 'reservasi_id');
    }

    //deklarasi bahwa field reservasi_id di tabel PemesananMenuMakan adalah milik tabel Reservasi
    public function pemesananMenuMakan()
    {
        return $this->hasMany(PemesananMenuMakan::class, 'reservasi_id');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(
            Fasilitas::class,
            'pemesanan_fasilitas', 
            'reservasi_id',        // foreign key di tabel pivot mengarah ke model ini
            'fasilitas_id'         // foreign key ke model Fasilitas
        )->withPivot('jumlah'); // kalau mau akses jumlah juga
    }

}
