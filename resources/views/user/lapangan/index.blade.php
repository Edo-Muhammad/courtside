@extends('layouts.app')

@section('title', 'Sewa Lapangan - Courtside')

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Pilih Lapangan</h3>

<form action="{{ route('user.lapangan.index') }}" method="GET" class="row g-2 mb-4">
    <div class="col-md-6">
        <input type="text" name="cari" class="form-control" placeholder="Cari nama lapangan..." value="{{ request('cari') }}">
    </div>
    <div class="col-md-4">
        <select name="jenis" class="form-select">
            <option value="">-- Semua Jenis --</option>
            @foreach ($daftarJenis as $jenis)
            <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-navy w-100">Cari</button>
    </div>
</form>

<div class="row">
    @forelse ($lapangan as $item)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            @if ($item->foto)
            <img src="{{ asset('storage/' . $item->foto) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $item->nama }}">
            @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                <span class="text-muted">Tidak ada foto</span>
            </div>
            @endif
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $item->nama }}</h5>
                <p class="card-text text-muted mb-1">{{ $item->jenis }}</p>
                @if ($item->ulasan_avg_rating)
                <p class="mb-1 text-warning">
                    {{ str_repeat('★', round($item->ulasan_avg_rating)) }}{{ str_repeat('☆', 5 - round($item->ulasan_avg_rating)) }}
                    <span class="text-muted small">({{ number_format($item->ulasan_avg_rating, 1) }})</span>
                </p>
                @else
                <p class="mb-1 text-muted small">Belum ada ulasan</p>
                @endif
                <p class="card-text fw-bold" style="color: var(--navy);">Rp {{ number_format($item->harga_per_jam, 0, ',', '.') }} / jam</p>
                <a href="{{ route('user.lapangan.show', $item) }}" class="btn btn-navy mt-auto">Lihat Jadwal</a>
            </div>
        </div>
    </div>
    @empty
    <p class="text-muted">Tidak ada lapangan yang cocok dengan pencarian kamu.</p>
    @endforelse
</div>

<div class="mt-3">
    {{ $lapangan->links() }}
</div>
@endsection