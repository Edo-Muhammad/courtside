@extends('layouts.app')

@section('title', 'Dashboard Admin - Courtside')

@section('content')
<h3 style="color: var(--navy);">Dashboard Admin</h3>
<p>Selamat datang, {{ auth()->user()->nama }}. Berikut ringkasan aktivitas Courtside.</p>

<div class="row mt-3">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total Booking</h6>
                <h3 style="color: var(--navy);">{{ $totalBooking }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted mb-1">Menunggu Konfirmasi</h6>
                <h3 class="text-warning">{{ $totalPending }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted mb-1">Booking Disetujui</h6>
                <h3 class="text-success">{{ $totalApproved }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted mb-1">Estimasi Pendapatan</h6>
                <h5 style="color: var(--navy);">Rp {{ number_format($estimasiPendapatan, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Lapangan Terpopuler</h5>
                @if ($lapanganTerpopuler->isEmpty())
                <p class="text-muted mb-0">Belum ada data booking.</p>
                @else
                <ol class="mb-0 ps-3">
                    @foreach ($lapanganTerpopuler as $l)
                    <li>{{ $l->nama }} <span class="text-muted">({{ $l->total_booking }} booking)</span></li>
                    @endforeach
                </ol>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Kelola Lapangan</h5>
                <p class="card-text text-muted">Tambah, ubah, dan hapus data lapangan.</p>
                <a href="{{ route('admin.lapangan.index') }}" class="btn btn-sm btn-navy">Buka</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Kelola Jadwal</h5>
                <p class="card-text text-muted">Atur slot waktu tiap lapangan.</p>
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-navy">Buka</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Konfirmasi Booking</h5>
                <p class="card-text text-muted">Setujui atau tolak booking yang masuk.</p>
                <a href="{{ route('admin.booking.index') }}" class="btn btn-sm btn-navy">Buka</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Verifikasi Pembayaran</h5>
                <p class="card-text text-muted">Cek dan verifikasi bukti transfer user.</p>
                <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-sm btn-navy">Buka</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Kelola Ulasan</h5>
                <p class="card-text text-muted">Lihat dan hapus ulasan dari penyewa.</p>
                <a href="{{ route('admin.ulasan.index') }}" class="btn btn-sm btn-navy">Buka</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Laporan Booking</h5>
                <p class="card-text text-muted">Export laporan ke Excel/PDF per periode.</p>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-navy">Buka</a>
            </div>
        </div>
    </div>
</div>
@endsection