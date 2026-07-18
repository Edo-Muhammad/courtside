<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Notifications\PaymentStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Tampilkan form upload bukti transfer untuk booking yang sudah disetujui.
     */
    public function create(Booking $booking)
    {
        $this->authorizeOwnership($booking);

        if ($booking->status !== 'approved') {
            return redirect()
                ->route('user.booking.riwayat')
                ->with('error', 'Pembayaran hanya bisa diunggah untuk booking yang sudah disetujui.');
        }

        if ($booking->pembayaran) {
            return redirect()
                ->route('user.booking.riwayat')
                ->with('error', 'Bukti pembayaran untuk booking ini sudah pernah diunggah.');
        }

        return view('user.pembayaran.create', compact('booking'));
    }

    /**
     * Simpan bukti transfer yang diunggah user.
     */
    public function store(Request $request, Booking $booking)
    {
        $this->authorizeOwnership($booking);

        if ($booking->status !== 'approved' || $booking->pembayaran) {
            return redirect()->route('user.booking.riwayat');
        }

        $request->validate([
            'bukti_transfer' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $pembayaran = Pembayaran::create([
            'booking_id'        => $booking->id,
            'metode_pembayaran' => 'transfer',
            'bukti_transfer'    => $path,
            'status_verifikasi' => 'menunggu',
            'tanggal_bayar'     => now()->toDateString(),
        ]);

        $booking->user->notify(new PaymentStatusUpdated($pembayaran));

        return redirect()
            ->route('user.booking.riwayat')
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi Admin.');
    }

    private function authorizeOwnership(Booking $booking): void
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
