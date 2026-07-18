@extends('layouts.app')

@section('title', 'Bayar QRIS - Courtside')

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Pembayaran QRIS</h3>

<div class="card shadow-sm mx-auto" style="max-width: 420px;">
    <div class="card-body text-center">
        <p class="mb-1"><strong>{{ $booking->jadwal->lapangan->nama }}</strong></p>
        <p class="text-muted mb-1">{{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d-m-Y') }} &bull; {{ substr($booking->jadwal->jam_mulai, 0, 5) }} - {{ substr($booking->jadwal->jam_selesai, 0, 5) }}</p>
        <h4 style="color: var(--navy);" class="mb-3">Rp {{ number_format($booking->jadwal->lapangan->harga_per_jam, 0, ',', '.') }}</h4>

        {{-- QR dummy: pola visual acak berbasis ID booking, murni simulasi, bukan QR sungguhan --}}
        @php
        $seed = $booking->id;
        $size = 9;
        mt_srand($seed);
        @endphp
        <div class="d-inline-block p-3 bg-white border rounded mb-3">
            <svg width="200" height="200" viewBox="0 0 {{ $size }} {{ $size }}">
                <rect width="{{ $size }}" height="{{ $size }}" fill="#fff" />
                @for ($y = 0; $y < $size; $y++)
                    @for ($x=0; $x < $size; $x++)
                    @if (($x < 3 && $y < 3) || ($x>= $size - 3 && $y < 3) || ($x < 3 && $y>= $size - 3))
                        <rect x="{{ $x }}" y="{{ $y }}" width="1" height="1" fill="#000" />
                        @elseif (mt_rand(0, 1) === 1)
                        <rect x="{{ $x }}" y="{{ $y }}" width="1" height="1" fill="#1F3864" />
                        @endif
                        @endfor
                        @endfor
            </svg>
        </div>

        <p class="text-muted small mb-3">Scan QRIS di atas menggunakan aplikasi e-wallet/m-banking kamu.<br>(Simulasi — QR ini tidak benar-benar dapat dipindai)</p>

        <form action="{{ route('user.qris.confirm', $booking) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-navy w-100">Saya Sudah Bayar</button>
        </form>
        <a href="{{ route('user.booking.riwayat') }}" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
    </div>
</div>
@endsection