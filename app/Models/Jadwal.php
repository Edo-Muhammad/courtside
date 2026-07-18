<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    protected $fillable = [
        'lapangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status_tersedia',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'status_tersedia' => 'boolean',
        ];
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'jadwal_id');
    }

    // Slot dianggap bisa dibooking hanya jika status_tersedia true
    // DAN belum punya booking aktif (pending/approved)
    public function isBookable(): bool
    {
        if (!$this->status_tersedia) {
            return false;
        }

        return !$this->booking()
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }

    /**
     * Durasi slot dalam satuan jam (mendukung durasi non-1-jam, misal 08:00-10:00 = 2 jam).
     */
    public function durasiJam(): float
    {
        $mulai = \Carbon\Carbon::parse($this->jam_mulai);
        $selesai = \Carbon\Carbon::parse($this->jam_selesai);

        return abs($selesai->floatDiffInHours($mulai));
    }

    /**
     * Total harga slot ini = harga per jam lapangan x durasi slot.
     */
    public function totalHarga(): float
    {
        return $this->lapangan->harga_per_jam * $this->durasiJam();
    }
}
