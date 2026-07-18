<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'booking_id',
        'bukti_transfer',
        'status_verifikasi',
        'tanggal_bayar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
