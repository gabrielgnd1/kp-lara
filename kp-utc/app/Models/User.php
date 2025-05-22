<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file User & nama table bukan users jadi hrs dideklarasi
    protected $table = 'user';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_role',
        'status'
    ];

    //hidden ini artinya data yang ada disini gaakan direturn waktu dipanggil
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        //bikin password ke hash otomatis pake bcrypt
        return [
            'password' => 'hashed',
        ];
    }

    //deklarasi bahwa id_role di table ini adalah milik table Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    //deklarasi bahwa field id_pic_ioc di tabel Reservasi adalah milik tabel User
    public function reservasiPicIoc()
    {
        return $this->hasMany(Reservasi::class, 'id_pic_ioc');
    }

    //deklarasi bahwa field id_pic_utc di tabel Reservasi adalah milik tabel User
    public function reservasiPicUtc()
    {
        return $this->hasMany(Reservasi::class, 'id_pic_utc');
    }

    //deklarasi bahwa field user_id di tabel Laporan adalah milik tabel User
    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'user_id');
    }

    //deklarasi bahwa field user_id di tabel DiskusiLaporan adalah milik tabel User
    public function diskusiLaporan()
    {
        return $this->hasMany(DiskusiLaporan::class, 'user_id');
    }

    //deklarasi bahwa field user_id di tabel LogLaporan adalah milik tabel User
    public function logLaporan()
    {
        return $this->hasMany(LogLaporan::class, 'user_id');
    }
}
