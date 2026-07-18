@extends('layouts.app')

@section('title', 'Kelola Jadwal - Courtside')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 style="color: var(--navy);" class="mb-0">Kelola Jadwal</h3>
    <a href="{{ route('admin.jadwal.create') }}" class="btn btn-navy">+ Tambah Jadwal</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Lapangan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal as $item)
                <tr>
                    <td>{{ $item->lapangan->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}</td>
                    <td>
                        @if (!$item->status_tersedia)
                        <span class="badge bg-secondary">Nonaktif</span>
                        @elseif ($item->booking && in_array($item->booking->status, ['pending', 'approved']))
                        <span class="badge bg-warning text-dark">Sudah Dibooking</span>
                        @else
                        <span class="badge bg-success">Tersedia</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.jadwal.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.jadwal.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada jadwal.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $jadwal->links() }}
</div>
@endsection