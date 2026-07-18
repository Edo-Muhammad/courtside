<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Notifications\BookingStatusUpdated;
use App\Notifications\PaymentStatusUpdated;

class PembayaranController extends Controller
{
    /**
     * Tampilkan semua pembayaran yang perlu diverifikasi Admin.
     */
    public function index()
    {
        $pembayaran = Pembayaran::with(['booking.user', 'booking.jadwal.lapangan'])
            ->orderByRaw("CASE status_verifikasi WHEN 'menunggu' THEN 1 WHEN 'terverifikasi' THEN 2 ELSE 3 END")
            ->latest()
            ->paginate(10);

        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    /**
     * Verifikasi pembayaran sebagai sah, lalu approve booking terkait.
     */
    public function verify(Pembayaran $pembayaran)
    {
        $pembayaran->update(['status_verifikasi' => 'terverifikasi']);

        // Otomatis approve booking ketika pembayaran diverifikasi
        $booking = $pembayaran->booking;
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'approved']);
            $booking->user->notify(new BookingStatusUpdated($booking));
        }

        $pembayaran->booking->user->notify(new PaymentStatusUpdated($pembayaran));

        return back()->with('success', 'Pembayaran berhasil diverifikasi dan booking telah dikonfirmasi.');
    }

    /**
     * Tolak pembayaran (misalnya bukti transfer tidak valid).
     */
    public function reject(Pembayaran $pembayaran)
    {
        $pembayaran->update(['status_verifikasi' => 'ditolak']);
        $pembayaran->booking->user->notify(new PaymentStatusUpdated($pembayaran));

        return back()->with('success', 'Pembayaran ditolak.');
    }
}
