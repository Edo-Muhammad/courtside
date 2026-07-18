@extends('layouts.app')

@section('title', 'Kelola Lapangan - Courtside')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 style="color: var(--navy);" class="mb-0">Kelola Lapangan</h3>
    <a href="{{ route('admin.lapangan.create') }}" class="btn btn-navy">+ Tambah Lapangan</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Harga/Jam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lapangan as $item)
                <tr>
                    <td style="width: 90px;">
                        @if ($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" width="70" class="rounded">
                        @else
                        <span class="text-muted small">Tidak ada foto</span>
                        @endif
                    </td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>Rp {{ number_format($item->harga_per_jam, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('admin.lapangan.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.lapangan.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus lapangan ini? Semua jadwal terkait juga akan terhapus.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada data lapangan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $lapangan->links() }}
</div>
@endsection