<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $table = 'lapangan';

    protected $fillable = [
        'nama',
        'jenis',
        'harga_per_jam',
        'foto',
        'deskripsi',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'lapangan_id');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'lapangan_id');
    }
}
