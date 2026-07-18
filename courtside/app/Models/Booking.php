<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'booking_id');
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'booking_id');
    }
}
