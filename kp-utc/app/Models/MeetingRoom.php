<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    //laravel itu otomatis ngira kalau nama table itu bentuk jamak dari nama file modelnya
    //karena nama file MeetingRoom & nama table bukan meetingrooms jadi hrs dideklarasi
    protected $table = 'meeting_room';

    //ini isi smua atribut selain primary key
    protected $fillable = [
        'nama',
        'kapasitas',
        'internal_weekday_menginap',
        'internal_weekend_menginap',
        'internal_weekday_tidakmenginap',
        'internal_weekend_tidakmenginap',
        'eksternal_weekday_menginap',
        'eksternal_weekend_menginap',
        'eksternal_weekday_tidakmenginap',
        'eksternal_weekend_tidakmenginap',
        'deskripsi',
        'status'
    ];

    //deklarasi bahwa field meeting_room_id di tabel PemesananMeetingRoom adalah milik tabel MeetingRoom
    public function pemesananMeetingRoom()
    {
        return $this->hasMany(PemesananMeetingRoom::class, 'meeting_room_id');
    }
}
