<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\BookingStatusUpdated;

class BookingController extends Controller
{
    /**
     * Tampilkan semua booking, prioritas status pending di atas.
     */
    public function index()
    {
        $booking = Booking::with(['user', 'jadwal.lapangan'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'approved' THEN 2 WHEN 'rejected' THEN 3 ELSE 4 END")
            ->latest()
            ->paginate(10);

        return view('admin.booking.index', compact('booking'));
    }

    /**
     * Setujui booking yang masih pending.
     */
    public function approve(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking berstatus pending yang dapat disetujui.');
        }

        $booking->update(['status' => 'approved']);
        $booking->user->notify(new BookingStatusUpdated($booking));

        return back()->with('success', 'Booking berhasil disetujui.');
    }

    /**
     * Tolak booking yang masih pending.
     */
    public function reject(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking berstatus pending yang dapat ditolak.');
        }

        $booking->update(['status' => 'rejected']);
        $booking->user->notify(new BookingStatusUpdated($booking));

        return back()->with('success', 'Booking berhasil ditolak.');
    }
}
