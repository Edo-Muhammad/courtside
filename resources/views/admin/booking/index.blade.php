@extends('layouts.app')

@section('title', 'Konfirmasi Booking - Courtside')

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Konfirmasi Booking</h3>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Penyewa</th>
                    <th>Lapangan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($booking as $item)
                <tr>
                    <td>{{ $item->user->nama }}</td>
                    <td>{{ $item->jadwal->lapangan->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->jadwal->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ substr($item->jadwal->jam_mulai, 0, 5) }} - {{ substr($item->jadwal->jam_selesai, 0, 5) }}</td>
                    <td>
                        @php
                        $badgeClass = match($item->status) {
                        'pending' => 'bg-warning text-dark',
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        'cancelled' => 'bg-secondary',
                        default => 'bg-secondary',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                    </td>
                    <td>
                        @if ($item->status === 'pending')
                        <form action="{{ route('admin.booking.approve', $item) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                        </form>
                        <form action="{{ route('admin.booking.reject', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak booking ini?');">
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
                    <td colspan="6" class="text-center text-muted py-4">Belum ada booking yang masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $booking->links() }}
</div>
@endsection