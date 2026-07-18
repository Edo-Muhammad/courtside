@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Courtside')

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Verifikasi Pembayaran</h3>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Bukti Transfer</th>
                    <th>Penyewa</th>
                    <th>Lapangan</th>
                    <th>Tanggal Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pembayaran as $item)
                <tr>
                    <td>
                        <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank">
                            <img src="{{ asset('storage/' . $item->bukti_transfer) }}" width="70" class="rounded">
                        </a>
                    </td>
                    <td>{{ $item->booking->user->nama }}</td>
                    <td>{{ $item->booking->jadwal->lapangan->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d-m-Y') }}</td>
                    <td>
                        @php
                        $badgeClass = match($item->status_verifikasi) {
                        'menunggu' => 'bg-warning text-dark',
                        'terverifikasi' => 'bg-success',
                        'ditolak' => 'bg-danger',
                        default => 'bg-secondary',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($item->status_verifikasi) }}</span>
                    </td>
                    <td>
                        @if ($item->status_verifikasi === 'menunggu')
                        <form action="{{ route('admin.pembayaran.verify', $item) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Verifikasi</button>
                        </form>
                        <form action="{{ route('admin.pembayaran.reject', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak pembayaran ini?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Tolak</button>
                        </form>
                        @else
                        <span class="text-muted small">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada pembayaran yang diunggah.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $pembayaran->links() }}
</div>
@endsection