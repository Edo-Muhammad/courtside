@extends('layouts.app')

@section('title', 'Dashboard - Courtside')

@section('content')
<h3 style="color: var(--navy);">Selamat Datang, {{ auth()->user()->nama }}!</h3>
<p>Ini adalah halaman utama User untuk menyewa lapangan olahraga.</p>

<div class="row mt-4">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Lihat Lapangan</h5>
                <p class="card-text text-muted">Cari dan booking lapangan sesuai jadwal.</p>
                <a href="{{ route('user.lapangan.index') }}" class="btn btn-sm btn-navy">Buka</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Riwayat Booking</h5>
                <p class="card-text text-muted">Pantau status booking kamu.</p>
                <a href="{{ route('user.booking.riwayat') }}" class="btn btn-sm btn-navy">Buka</a>
            </div>
        </div>
    </div>
</div>
@endsection\