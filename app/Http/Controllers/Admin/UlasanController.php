<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;

class UlasanController extends Controller
{
    public function index()
    {
        $ulasan = Ulasan::with(['user', 'lapangan', 'booking'])
            ->latest()
            ->paginate(10);

        return view('admin.ulasan.index', compact('ulasan'));
    }

    public function destroy(Ulasan $ulasan)
    {
        $ulasan->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
