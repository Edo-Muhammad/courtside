<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    /**
     * Simpan ulasan/rating untuk sebuah booking milik sendiri.
     */
    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'approved') {
            return back()->with('error', 'Ulasan hanya bisa diberikan untuk booking yang sudah disetujui.');
        }

        if ($booking->ulasan) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk booking ini.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'komentar' => ['nullable', 'string', 'max:1000'],
        ]);

        Ulasan::create([
            'lapangan_id' => $booking->jadwal->lapangan_id,
            'user_id' => Auth::id(),
            'booking_id' => $booking->id,
            'rating' => $validated['rating'],
            'komentar' => $validated['komentar'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan kamu berhasil disimpan.');
    }
}
