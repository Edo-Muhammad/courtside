@extends('layouts.app')

@section('title', 'Edit Jadwal - Courtside')

@section('back_url', route('admin.jadwal.index'))

@section('content')
<h3 style="color: var(--navy);">Edit Jadwal</h3>

<div class="card shadow-sm mt-3">
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if ($terkunci)
        <div class="alert alert-warning">
            Jadwal ini sudah memiliki booking aktif (<strong>{{ ucfirst($jadwal->booking->status) }}</strong>) sehingga
            tanggal, jam, dan lapangan tidak dapat diubah. Tolak atau batalkan booking tersebut terlebih dahulu
            dari menu <em>Konfirmasi Booking</em> jika perlu mengubah slot ini.
        </div>
        @endif

        <form action="{{ route('admin.jadwal.update', $jadwal) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Lapangan</label>
                <select name="lapangan_id" class="form-select" required {{ $terkunci ? 'disabled' : '' }}>
                    @foreach ($lapanganList as $l)
                    <option value="{{ $l->id }}" {{ old('lapangan_id', $jadwal->lapangan_id) == $l->id ? 'selected' : '' }}>{{ $l->nama }} ({{ $l->jenis }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', \Carbon\Carbon::parse($jadwal->tanggal)->format('Y-m-d')) }}" required {{ $terkunci ? 'disabled' : '' }}>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}" required {{ $terkunci ? 'disabled' : '' }}>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai', substr($jadwal->jam_selesai, 0, 5)) }}" required {{ $terkunci ? 'disabled' : '' }}>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="status_tersedia" value="1" class="form-check-input" id="status_tersedia" {{ $jadwal->status_tersedia ? 'checked' : '' }} {{ $terkunci ? 'disabled' : '' }}>
                <label class="form-check-label" for="status_tersedia">Slot tersedia untuk dibooking</label>
            </div>

            @if ($terkunci)
            <button type="button" class="btn btn-navy" disabled>Update</button>
            @else
            <button type="submit" class="btn btn-navy">Update</button>
            @endif
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection