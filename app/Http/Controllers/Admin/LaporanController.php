<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Tampilkan form filter periode laporan.
     */
    public function index(Request $request)
    {
        $dariTanggal = $request->input('dari', now()->startOfMonth()->toDateString());
        $sampaiTanggal = $request->input('sampai', now()->toDateString());

        $booking = $this->queryLaporan($dariTanggal, $sampaiTanggal)->paginate(15);

        return view('admin.laporan.index', compact('booking', 'dariTanggal', 'sampaiTanggal'));
    }

    /**
     * Unduh laporan booking dalam format CSV (bisa dibuka di Excel).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $dariTanggal = $request->input('dari', now()->startOfMonth()->toDateString());
        $sampaiTanggal = $request->input('sampai', now()->toDateString());

        $bookings = $this->queryLaporan($dariTanggal, $sampaiTanggal)->get();

        $filename = "laporan-booking-{$dariTanggal}-sd-{$sampaiTanggal}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bookings) {
            $handle = fopen('php://output', 'w');

            // Tambahkan BOM agar karakter dibaca benar oleh Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Penyewa', 'Lapangan', 'Tanggal', 'Jam', 'Status', 'Total Harga', 'Status Pembayaran']);

            foreach ($bookings as $b) {
                fputcsv($handle, [
                    $b->user->nama,
                    $b->jadwal->lapangan->nama,
                    \Carbon\Carbon::parse($b->jadwal->tanggal)->format('d-m-Y'),
                    substr($b->jadwal->jam_mulai, 0, 5) . ' - ' . substr($b->jadwal->jam_selesai, 0, 5),
                    ucfirst($b->status),
                    $b->jadwal->totalHarga(),
                    $b->pembayaran ? ucfirst($b->pembayaran->status_verifikasi) : '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Tampilkan halaman versi cetak (untuk di-print / Save as PDF lewat browser).
     */
    public function cetak(Request $request)
    {
        $dariTanggal = $request->input('dari', now()->startOfMonth()->toDateString());
        $sampaiTanggal = $request->input('sampai', now()->toDateString());

        $bookings = $this->queryLaporan($dariTanggal, $sampaiTanggal)->get();

        $totalPendapatan = $bookings->where('status', 'approved')
            ->sum(fn($b) => $b->jadwal->totalHarga());

        return view('admin.laporan.cetak', compact('bookings', 'dariTanggal', 'sampaiTanggal', 'totalPendapatan'));
    }

    /**
     * Query dasar laporan booking dalam rentang tanggal jadwal tertentu.
     */
    private function queryLaporan(string $dari, string $sampai)
    {
        return Booking::with(['user', 'jadwal.lapangan', 'pembayaran'])
            ->whereHas('jadwal', function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal', [$dari, $sampai]);
            })
            ->latest();
    }
}
