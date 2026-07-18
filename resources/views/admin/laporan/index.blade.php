@extends('layouts.app')

@section('title', 'Laporan Booking - Courtside')

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Laporan Booking</h3>

<form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-2 mb-4">
    <div class="col-md-3">
        <label class="form-label">Dari Tanggal</label>
        <input type="date" name="dari" class="form-control" value="{{ $dariTanggal }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Sampai Tanggal</label>
        <input type="date" name="sampai" class="form-control" value="{{ $sampaiTanggal }}">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-navy w-100">Filter</button>
    </div>
    <div class="col-md-3 d-flex align-items-end gap-2">
        <a href="{{ route('admin.laporan.export', ['dari' => $dariTanggal, 'sampai' => $sampaiTanggal]) }}" class="btn btn-outline-success w-100">Excel (CSV)</a>
        <a href="{{ route('admin.laporan.cetak', ['dari' => $dariTanggal, 'sampai' => $sampaiTanggal]) }}" target="_blank" class="btn btn-outline-danger w-100">Cetak PDF</a>
    </div>
</form>

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
                    <th>Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($booking as $item)
                <tr>
                    <td>{{ $item->user->nama }}</td>
                    <td>{{ $item->jadwal->lapangan->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->jadwal->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ substr($item->jadwal->jam_mulai, 0, 5) }} - {{ substr($item->jadwal->jam_selesai, 0, 5) }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>{{ $item->pembayaran ? ucfirst($item->pembayaran->status_verifikasi) : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Tidak ada data booking pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $booking->appends(['dari' => $dariTanggal, 'sampai' => $sampaiTanggal])->links() }}
</div>
@endsection