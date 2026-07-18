@extends('layouts.app')

@section('title', 'Bayar - Courtside')

@section('back_url', route('user.booking.riwayat'))

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Pilih Metode Pembayaran</h3>

<div class="card shadow-sm mx-auto" style="max-width: 460px;">
    <div class="card-body">
        {{-- Info Booking --}}
        <div class="text-center mb-4">
            <p class="mb-1"><strong>{{ $booking->jadwal->lapangan->nama }}</strong></p>
            <p class="text-muted mb-1">{{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d-m-Y') }} &bull; {{ substr($booking->jadwal->jam_mulai, 0, 5) }} - {{ substr($booking->jadwal->jam_selesai, 0, 5) }}</p>
            <h4 style="color: var(--navy);" class="mb-1">Rp {{ number_format($booking->jadwal->totalHarga(), 0, ',', '.') }}</h4>
            <p class="text-muted small">({{ rtrim(rtrim(number_format($booking->jadwal->durasiJam(), 1), '0'), '.') }} jam &times; Rp {{ number_format($booking->jadwal->lapangan->harga_per_jam, 0, ',', '.') }}/jam)</p>
        </div>

        <hr>

        {{-- Pilihan 1: QRIS --}}
        <div class="mb-4">
            <h6 class="fw-semibold mb-3">
                <span class="badge bg-primary me-1">1</span> Bayar via QRIS
            </h6>

            {{-- QR dummy simulasi --}}
            @php
                $seed = $booking->id;
                $size = 9;
                mt_srand($seed);
            @endphp
            <div class="d-flex justify-content-center mb-2">
                <div class="d-inline-block p-3 bg-white border rounded">
                    <svg width="180" height="180" viewBox="0 0 {{ $size }} {{ $size }}">
                        <rect width="{{ $size }}" height="{{ $size }}" fill="#fff" />
                        @for ($y = 0; $y < $size; $y++)
                            @for ($x = 0; $x < $size; $x++)
                                @if (($x < 3 && $y < 3) || ($x >= $size - 3 && $y < 3) || ($x < 3 && $y >= $size - 3))
                                    <rect x="{{ $x }}" y="{{ $y }}" width="1" height="1" fill="#000" />
                                @elseif (mt_rand(0, 1) === 1)
                                    <rect x="{{ $x }}" y="{{ $y }}" width="1" height="1" fill="#1F3864" />
                                @endif
                            @endfor
                        @endfor
                    </svg>
                </div>
            </div>

            <p class="text-muted small text-center mb-3">
                Scan QRIS di atas menggunakan aplikasi e-wallet/m-banking kamu.<br>
                <em>(Simulasi — QR ini tidak benar-benar dapat dipindai)</em>
            </p>

            <form action="{{ route('user.qris.confirm', $booking) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-navy w-100">
                    ✅ Saya Sudah Bayar via QRIS
                </button>
            </form>
            <p class="text-muted small text-center mt-2 mb-0">
                Kamu akan diminta upload <strong>screenshot bukti pembayaran</strong> setelahnya.
            </p>
        </div>

        <hr>

        {{-- Pilihan 2: Bayar di Tempat --}}
        <div class="mb-3">
            <h6 class="fw-semibold mb-2">
                <span class="badge bg-secondary me-1">2</span> Bayar di Tempat
            </h6>
            <p class="text-muted small mb-3">
                Datang langsung ke kasir dan lakukan pembayaran saat hari booking. Admin akan mengkonfirmasi booking kamu setelah pembayaran diterima.
            </p>

            <form action="{{ route('user.qris.bayar-di-tempat', $booking) }}" method="POST"
                  onsubmit="return confirm('Kamu memilih untuk bayar di tempat. Admin akan memverifikasi saat kamu datang. Lanjutkan?');">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100">
                    🏪 Bayar di Tempat
                </button>
            </form>
        </div>

        <a href="{{ route('user.booking.riwayat') }}" class="btn btn-outline-danger w-100 mt-2">Batal</a>
    </div>
</div>
@endsection
