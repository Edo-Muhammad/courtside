@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran - Courtside')

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Upload Bukti Pembayaran</h3>

<div class="card shadow-sm" style="max-width: 500px;">
    <div class="card-body">
        <p class="mb-1"><strong>Lapangan:</strong> {{ $booking->jadwal->lapangan->nama }}</p>
        <p class="mb-1"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d-m-Y') }}</p>
        <p class="mb-1"><strong>Jam:</strong> {{ substr($booking->jadwal->jam_mulai, 0, 5) }} - {{ substr($booking->jadwal->jam_selesai, 0, 5) }}</p>
        <p class="mb-3"><strong>Total:</strong> Rp {{ number_format($booking->jadwal->lapangan->harga_per_jam, 0, ',', '.') }}</p>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('user.pembayaran.store', $booking) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Bukti Transfer (screenshot/foto)</label>
                <input type="file" name="bukti_transfer" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-navy">Unggah</button>
            <a href="{{ route('user.booking.riwayat') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection