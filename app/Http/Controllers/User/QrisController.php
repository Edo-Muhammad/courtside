<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Notifications\PaymentStatusUpdated;
use Illuminate\Http\Request;
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
                ->with('error', 'Pembayaran hanya berlaku untuk booking yang masih menunggu konfirmasi.');
        }

        if ($booking->pembayaran) {
            return redirect()
                ->route('user.booking.riwayat')
                ->with('error', 'Pembayaran untuk booking ini sudah pernah dikirim.');
        }

        return view('user.qris.create', compact('booking'));
    }

    /**
     * Setelah user klik "Saya Sudah Bayar via QRIS",
     * redirect ke halaman upload bukti pembayaran QRIS.
     */
    public function confirm(Booking $booking)
    {
        $this->authorizeOwnership($booking);

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('user.booking.riwayat')
                ->with('error', 'Booking ini sudah tidak berstatus pending.');
        }

        if ($booking->pembayaran) {
            return redirect()
                ->route('user.booking.riwayat')
                ->with('error', 'Pembayaran untuk booking ini sudah pernah dikirim.');
        }

        // Redirect ke halaman upload bukti QRIS
        return redirect()->route('user.qris.upload', $booking);
    }

    /**
     * Tampilkan halaman upload bukti pembayaran QRIS.
     */
    public function uploadForm(Booking $booking)
    {
        $this->authorizeOwnership($booking);

        if ($booking->status !== 'pending' || $booking->pembayaran) {
            return redirect()->route('user.booking.riwayat');
        }

        return view('user.qris.upload', compact('booking'));
    }

    /**
     * Simpan bukti pembayaran QRIS yang diunggah user.
     */
    public function uploadStore(Request $request, Booking $booking)
    {
        $this->authorizeOwnership($booking);

        if ($booking->status !== 'pending' || $booking->pembayaran) {
            return redirect()->route('user.booking.riwayat');
        }

        $request->validate([
            'bukti_transfer' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        DB::transaction(function () use ($booking, $path) {
            Pembayaran::create([
                'booking_id'        => $booking->id,
                'metode_pembayaran' => 'qris',
                'bukti_transfer'    => $path,
                'status_verifikasi' => 'menunggu',
                'tanggal_bayar'     => now()->toDateString(),
            ]);
        });

        $booking->refresh()->load('pembayaran');
        $booking->user->notify(new PaymentStatusUpdated($booking->pembayaran));

        return redirect()
            ->route('user.booking.riwayat')
            ->with('success', 'Bukti pembayaran QRIS berhasil diunggah! Menunggu verifikasi Admin.');
    }

    /**
     * Konfirmasi pembayaran di tempat: menunggu verifikasi Admin.
     */
    public function bayarDiTempat(Booking $booking)
    {
        $this->authorizeOwnership($booking);

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('user.booking.riwayat')
                ->with('error', 'Booking ini sudah tidak berstatus pending.');
        }

        if ($booking->pembayaran) {
            return redirect()
                ->route('user.booking.riwayat')
                ->with('error', 'Pembayaran untuk booking ini sudah pernah dikirim.');
        }

        DB::transaction(function () use ($booking) {
            Pembayaran::create([
                'booking_id'        => $booking->id,
                'metode_pembayaran' => 'di_tempat',
                'bukti_transfer'    => null,
                'status_verifikasi' => 'menunggu',
                'tanggal_bayar'     => now()->toDateString(),
            ]);
        });

        $booking->refresh()->load('pembayaran');
        $booking->user->notify(new PaymentStatusUpdated($booking->pembayaran));

        return redirect()
            ->route('user.booking.riwayat')
            ->with('success', 'Permintaan bayar di tempat berhasil dikirim! Menunggu verifikasi Admin.');
    }

    private function authorizeOwnership(Booking $booking): void
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
