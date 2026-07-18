<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Notifications\BookingStatusUpdated;
use App\Notifications\PaymentStatusUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QrisController extends Controller
{
    /**
     * Tampilkan halaman simulasi QRIS untuk booking yang masih pending.
     */
    public function create(Booking $booking)
    {
        $this->authorizeOwnership($booking);

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('user.booking.riwayat')
                ->with('error', 'Pembayaran QRIS hanya berlaku untuk booking yang masih menunggu konfirmasi.');
        }

        return view('user.qris.create', compact('booking'));
    }

    /**
     * Simulasikan konfirmasi pembayaran QRIS: begitu "dibayar",
     * booking otomatis disetujui dan pembayaran otomatis terverifikasi
     * tanpa perlu tinjauan manual Admin.
     */
    public function confirm(Booking $booking)
    {
        $this->authorizeOwnership($booking);

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('user.booking.riwayat')
                ->with('error', 'Booking ini sudah tidak berstatus pending.');
        }

        DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'approved']);

            Pembayaran::create([
                'booking_id' => $booking->id,
                'bukti_transfer' => null,
                'status_verifikasi' => 'terverifikasi',
                'tanggal_bayar' => now()->toDateString(),
            ]);
        });

        $booking->refresh();
        $booking->load('pembayaran.booking.jadwal.lapangan');
        $booking->user->notify(new BookingStatusUpdated($booking));
        $booking->user->notify(new PaymentStatusUpdated($booking->pembayaran));

        return redirect()
            ->route('user.booking.riwayat')
            ->with('success', 'Pembayaran QRIS berhasil! Booking kamu otomatis dikonfirmasi.');
    }

    private function authorizeOwnership(Booking $booking): void
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
