<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser as FilamentUserContract;
use Filament\Panel;

class User extends Authenticatable implements FilamentUserContract
{
    use HasFactory, Notifiable;

    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file User & nama table bukan users jadi hrs dideklarasi
    protected $table = 'user';

    //karena di table user tidak ada created_at & updated_at
    public $timestamps = false;

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'username',
        'name',
        'email',    
        'password',             
        'id_role',
        'status'
    ];

    // Map is_active to status for Filament
    public function getIsActiveAttribute()
    {
        return $this->status === 'Available';
    }

    public function setIsActiveAttribute($value)
    {
        $this->attributes['status'] = $value ? 'Available' : 'Not Available';
    }

    //hidden ini artinya data yang ada disini gaakan direturn waktu dipanggil
    protected $hidden = [
        'password',
        //  'remember_token',
    ];

    protected function casts(): array
    {
        //bikin password ke hash otomatis pake bcrypt
        return [
            'password' => 'hashed',
        ];
    }

      public function canAccessPanel(Panel $panel): bool
        {
            if ($panel->getId() === 'admin' && $this->id_role === 1 && $this->status === 'Available') {
                return true;
            }

            if ($panel->getId() === 'lapangan' && $this->id_role === 3 && $this->status === 'Available') {
                return true;
            }

            if ($panel->getId() === 'reservasi' && $this->id_role === 2 && $this->status === 'Available') {
                return true;
            }
            // Tambahkan kondisi untuk panel lain sesuai kebutuhan  

            return false;
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
