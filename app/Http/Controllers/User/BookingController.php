<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Riwayat booking milik user yang sedang login.
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with(['jadwal.lapangan', 'pembayaran', 'ulasan'])
            ->latest()
            ->paginate(10);

        return view('user.booking.riwayat', compact('bookings'));
    }

    /**
     * Proses pengajuan booking untuk sebuah slot jadwal.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id' => ['required', 'exists:jadwal,id'],
        ]);

        // Gunakan transaction + lockForUpdate agar tidak terjadi double booking
        // saat dua user mencoba booking slot yang sama secara bersamaan.
        $booking = DB::transaction(function () use ($validated) {
            $jadwal = Jadwal::lockForUpdate()->findOrFail($validated['jadwal_id']);

            if (!$jadwal->isBookable()) {
                throw ValidationException::withMessages([
                    'jadwal_id' => 'Maaf, slot jadwal ini sudah tidak tersedia.',
                ]);
            }

            return Booking::create([
                'user_id' => Auth::id(),
                'jadwal_id' => $jadwal->id,
                'status' => 'pending',
            ]);
        });

        return redirect()
            ->route('user.booking.riwayat')
            ->with('success', 'Booking berhasil diajukan. Menunggu konfirmasi Admin.');
    }

    /**
     * Batalkan booking milik sendiri, hanya jika masih berstatus pending.
     */
    public function cancel(Booking $booking)
    {
        // Pastikan booking ini benar-benar milik user yang sedang login.
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking berstatus pending yang dapat dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
