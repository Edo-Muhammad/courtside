<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangan = Lapangan::orderBy('nama')->paginate(10);

        return view('admin.lapangan.index', compact('lapangan'));
    }

    public function create()
    {
        return view('admin.lapangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'string', 'max:100'],
            'harga_per_jam' => ['required', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('lapangan', 'public');
        }

        Lapangan::create($validated);

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'string', 'max:100'],
            'harga_per_jam' => ['required', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('foto')) {
            if ($lapangan->foto) {
                Storage::disk('public')->delete($lapangan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('lapangan', 'public');
        }

        $lapangan->update($validated);

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan)
    {
        $adaRiwayatBooking = $lapangan->jadwal()->whereHas('booking')->exists();

        if ($adaRiwayatBooking) {
            return redirect()
                ->route('admin.lapangan.index')
                ->with('error', 'Lapangan ini tidak dapat dihapus karena masih memiliki riwayat booking. Hapus jadwal yang belum pernah dibooking secara satu per satu, atau biarkan data lapangan ini apa adanya untuk menjaga riwayat transaksi.');
        }

        if ($lapangan->foto) {
            Storage::disk('public')->delete($lapangan->foto);
        }

        $lapangan->delete();

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
}
