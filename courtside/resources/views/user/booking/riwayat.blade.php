@extends('layouts.app')

@section('title', 'Riwayat Booking - Courtside')

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Riwayat Booking</h3>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Lapangan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                <tr>
                    <td>{{ $booking->jadwal->lapangan->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ substr($booking->jadwal->jam_mulai, 0, 5) }} - {{ substr($booking->jadwal->jam_selesai, 0, 5) }}</td>
                    <td>
                        @php
                        $badgeClass = match($booking->status) {
                        'pending' => 'bg-warning text-dark',
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        'cancelled' => 'bg-secondary',
                        default => 'bg-secondary',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                    <td>
                        @if ($booking->pembayaran)
                        <span class="badge bg-info text-dark">{{ ucfirst($booking->pembayaran->status_verifikasi) }}</span>
                        @elseif ($booking->status === 'approved' && Route::has('user.pembayaran.create'))
                        <a href="{{ route('user.pembayaran.create', $booking) }}" class="btn btn-sm btn-outline-primary">Upload Bukti</a>
                        @else
                        <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($booking->status === 'pending')
                        <a href="{{ route('user.qris.create', $booking) }}" class="btn btn-sm btn-navy">Bayar QRIS</a>
                        <form action="{{ route('user.booking.cancel', $booking) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan booking ini?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan</button>
                        </form>
                        @elseif ($booking->status === 'approved' && !$booking->ulasan)
                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#ulasanModal{{ $booking->id }}">Beri Ulasan</button>

                        <div class="modal fade" id="ulasanModal{{ $booking->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('user.ulasan.store', $booking) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Beri Ulasan - {{ $booking->jadwal->lapangan->nama }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label">Rating</label>
                                            <select name="rating" class="form-select mb-3" required>
                                                <option value="">-- Pilih Rating --</option>
                                                @for ($i = 5; $i >= 1; $i--)
                                                <option value="{{ $i }}">{{ str_repeat('★', $i) }} ({{ $i }})</option>
                                                @endfor
                                            </select>
                                            <label class="form-label">Komentar (opsional)</label>
                                            <textarea name="komentar" class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-navy">Kirim Ulasan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @elseif ($booking->ulasan)
                        <span class="text-muted small">Sudah diulas</span>
                        @else
                        <span class="text-muted small">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Kamu belum memiliki riwayat booking.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $bookings->links() }}
</div>
@endsection