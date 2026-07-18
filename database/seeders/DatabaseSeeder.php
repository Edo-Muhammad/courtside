<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Akun Admin & User Demo ----------
        User::firstOrCreate(
            ['email' => 'admin@courtside.com'],
            [
                'nama' => 'Admin Courtside',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@courtside.com'],
            [
                'nama' => 'Budi Santoso',
                'password' => Hash::make('user1234'),
                'role' => 'user',
            ]
        );

        // ---------- Data Lapangan Demo ----------
        $lapanganList = [
            [
                'nama' => 'Lapangan Futsal Arena A',
                'jenis' => 'Futsal',
                'harga_per_jam' => 120000,
                'deskripsi' => 'Lapangan futsal indoor dengan rumput sintetis kualitas standar kompetisi.',
            ],
            [
                'nama' => 'GOR Badminton Ridhoindo',
                'jenis' => 'Badminton',
                'harga_per_jam' => 25000,
                'deskripsi' => 'Lapangan badminton indoor dengan pencahayaan LED, cocok untuk latihan maupun pertandingan.',
            ],
            [
                'nama' => 'Lapangan Futsal Arena B',
                'jenis' => 'Futsal',
                'harga_per_jam' => 100000,
                'deskripsi' => 'Lapangan futsal outdoor beratap, dekat dengan area parkir.',
            ],
        ];

        foreach ($lapanganList as $data) {
            $lapangan = Lapangan::firstOrCreate(['nama' => $data['nama']], $data);

            // Buat beberapa slot jadwal untuk 3 hari ke depan jika belum ada
            if ($lapangan->jadwal()->count() === 0) {
                for ($i = 0; $i < 3; $i++) {
                    $tanggal = now()->addDays($i)->toDateString();

                    foreach ([['08:00', '09:00'], ['09:00', '10:00'], ['19:00', '20:00']] as [$mulai, $selesai]) {
                        Jadwal::create([
                            'lapangan_id' => $lapangan->id,
                            'tanggal' => $tanggal,
                            'jam_mulai' => $mulai,
                            'jam_selesai' => $selesai,
                            'status_tersedia' => true,
                        ]);
                    }
                }
            }
        }
    }
}
