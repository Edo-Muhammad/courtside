@extends('layouts.app')

@section('title', $lapangan->nama . ' - Courtside')

@section('content')
<a href="{{ route('user.lapangan.index') }}" class="btn btn-sm btn-outline-secondary mb-3">&larr; Kembali</a>

<div class="card shadow-sm mb-4">
    <div class="row g-0">
        @if ($lapangan->foto)
        <div class="col-md-4">
            <img src="{{ asset('storage/' . $lapangan->foto) }}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $lapangan->nama }}">
        </div>
        @endif
        <div class="{{ $lapangan->foto ? 'col-md-8' : 'col-12' }}">
            <div class="card-body">
                <h4 style="color: var(--navy);">{{ $lapangan->nama }}</h4>
                <p class="mb-1"><span class="badge bg-secondary">{{ $lapangan->jenis }}</span></p>
                @if ($rataRating)
                <p class="mb-1 text-warning">
                    {{ str_repeat('★', round($rataRating)) }}{{ str_repeat('☆', 5 - round($rataRating)) }}
                    <span class="text-muted small">({{ number_format($rataRating, 1) }} dari {{ $ulasan->total() }} ulasan)</span>
                </p>
                @else
                <p class="mb-1 text-muted small">Belum ada ulasan</p>
                @endif
                <p class="fw-bold">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} / jam</p>
                <p class="text-muted">{{ $lapangan->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
            </div>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<h5 style="color: var(--navy);">Jadwal Tersedia</h5>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}</td>
                    <td>
                        @if ($item->isBookable())
                        <span class="badge bg-success">Tersedia</span>
                        @else
                        <span class="badge bg-secondary">Tidak Tersedia</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->isBookable())
                        <form action="{{ route('user.booking.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jadwal_id" value="{{ $item->id }}">
                            <button type="submit" class="btn btn-sm btn-navy">Booking</button>
                        </form>
                        @else
                        <button class="btn btn-sm btn-secondary" disabled>Booking</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">Belum ada jadwal tersedia untuk lapangan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<h5 style="color: var(--navy);" class="mt-4">Ulasan Penyewa</h5>
<div class="card shadow-sm">
    <div class="card-body">
        @forelse ($ulasan as $item)
        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
            <div class="d-flex justify-content-between">
                <strong>{{ $item->user->nama }}</strong>
                <span class="text-warning">{{ str_repeat('★', $item->rating) }}{{ str_repeat('☆', 5 - $item->rating) }}</span>
            </div>
            @if ($item->komentar)
            <p class="mb-0 text-muted">{{ $item->komentar }}</p>
            @endif
        </div>
        @empty
        <p class="text-muted mb-0">Belum ada ulasan untuk lapangan ini.</p>
        @endforelse
    </div>
</div>
<div class="mt-2">
    {{ $ulasan->links() }}
</div>
@endsection