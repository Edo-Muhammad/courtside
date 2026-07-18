<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'booking_id',
        'metode_pembayaran',
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

    /**
     * Label metode pembayaran yang ramah pengguna.
     */
    public function getMetodeLabelAttribute(): string
    {
        return match($this->metode_pembayaran) {
            'qris'      => 'QRIS',
            'di_tempat' => 'Bayar di Tempat',
            'transfer'  => 'Transfer Bank',
            default     => ucfirst($this->metode_pembayaran ?? '-'),
        };
    }
}
