@extends('layouts.app')

@section('title', 'Kelola Ulasan - Courtside')

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Kelola Ulasan</h3>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Lapangan</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ulasan as $u)
                <tr>
                    <td>{{ $u->user->nama }}</td>
                    <td>{{ $u->lapangan->nama }}</td>
                    <td>
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $u->rating)
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                        <span class="text-muted small">({{ $u->rating }}/5)</span>
                    </td>
                    <td>{{ $u->komentar ?? '-' }}</td>
                    <td>{{ $u->created_at->format('d-m-Y') }}</td>
                    <td>
                        <form action="{{ route('admin.ulasan.destroy', $u) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus ulasan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada ulasan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $ulasan->links() }}
</div>
@endsection
