<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    /**
     * Tampilkan daftar lapangan, dengan dukungan filter jenis dan pencarian nama.
     */
    public function index(Request $request)
    {
        $query = Lapangan::query();

        if ($request->filled('cari')) {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $lapangan = $query->withAvg('ulasan', 'rating')
            ->orderBy('nama')
            ->paginate(9)
            ->withQueryString();

        $daftarJenis = Lapangan::select('jenis')->distinct()->orderBy('jenis')->pluck('jenis');

        return view('user.lapangan.index', compact('lapangan', 'daftarJenis'));
    }

    /**
     * Tampilkan detail lapangan beserta jadwal/slot yang tersedia.
     */
    public function show(Lapangan $lapangan)
    {
        $jadwal = $lapangan->jadwal()
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        $ulasan = $lapangan->ulasan()->with('user')->latest()->paginate(5);
        $rataRating = $lapangan->ulasan()->avg('rating');

        return view('user.lapangan.show', compact('lapangan', 'jadwal', 'ulasan', 'rataRating'));
    }
}
