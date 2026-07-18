<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalBooking = Booking::count();
        $totalPending = Booking::where('status', 'pending')->count();
        $totalApproved = Booking::where('status', 'approved')->count();

        // Estimasi pendapatan: total dari booking yang sudah approved,
        // dihitung berdasarkan durasi slot x harga per jam (bukan flat per jam)
        $estimasiPendapatan = Booking::where('status', 'approved')
            ->with('jadwal.lapangan')
            ->get()
            ->sum(fn($b) => $b->jadwal->totalHarga());

        // Lapangan terpopuler berdasarkan jumlah booking (semua status)
        $lapanganTerpopuler = Lapangan::select('lapangan.nama', DB::raw('COUNT(booking.id) as total_booking'))
            ->join('jadwal', 'jadwal.lapangan_id', '=', 'lapangan.id')
            ->join('booking', 'booking.jadwal_id', '=', 'jadwal.id')
            ->groupBy('lapangan.id', 'lapangan.nama')
            ->orderByDesc('total_booking')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBooking',
            'totalPending',
            'totalApproved',
            'estimasiPendapatan',
            'lapanganTerpopuler'
        ));
    }

    public function user()
    {
        return view('user.dashboard');
    }
}
