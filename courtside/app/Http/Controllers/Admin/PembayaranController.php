<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
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
     * Verifikasi pembayaran sebagai sah.
     */
    public function verify(Pembayaran $pembayaran)
    {
        $pembayaran->update(['status_verifikasi' => 'terverifikasi']);
        $pembayaran->booking->user->notify(new PaymentStatusUpdated($pembayaran));

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
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
