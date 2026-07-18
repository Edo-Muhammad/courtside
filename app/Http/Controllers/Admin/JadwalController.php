<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = Jadwal::with('lapangan')
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->paginate(10);

        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $lapanganList = Lapangan::orderBy('nama')->get();

        return view('admin.jadwal.create', compact('lapanganList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lapangan_id' => ['required', 'exists:lapangan,id'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
            'status_tersedia' => ['nullable', 'boolean'],
        ]);

        $validated['status_tersedia'] = $request->boolean('status_tersedia', true);

        Jadwal::create($validated);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $lapanganList = Lapangan::orderBy('nama')->get();
        $terkunci = $jadwal->booking && in_array($jadwal->booking->status, ['pending', 'approved']);

        return view('admin.jadwal.edit', compact('jadwal', 'lapanganList', 'terkunci'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $adaBookingAktif = $jadwal->booking && in_array($jadwal->booking->status, ['pending', 'approved']);

        if ($adaBookingAktif) {
            return redirect()
                ->route('admin.jadwal.index')
                ->with('error', 'Jadwal ini tidak dapat diubah karena sudah memiliki booking aktif (pending/approved). Tolak atau batalkan booking tersebut terlebih dahulu jika ingin mengubah tanggal/jam slot ini.');
        }

        $validated = $request->validate([
            'lapangan_id' => ['required', 'exists:lapangan,id'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
            'status_tersedia' => ['nullable', 'boolean'],
        ]);

        $validated['status_tersedia'] = $request->boolean('status_tersedia', true);

        $jadwal->update($validated);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        if ($jadwal->booking) {
            return redirect()
                ->route('admin.jadwal.index')
                ->with('error', 'Jadwal ini tidak dapat dihapus karena sudah memiliki riwayat booking. Nonaktifkan slot ini saja lewat tombol Edit jika tidak ingin dipakai lagi.');
        }

        $jadwal->delete();

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
