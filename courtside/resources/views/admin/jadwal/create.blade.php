@extends('layouts.app')

@section('title', 'Tambah Jadwal - Courtside')

@section('content')
<h3 style="color: var(--navy);">Tambah Jadwal</h3>

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

        @if ($lapanganList->isEmpty())
        <div class="alert alert-warning mb-0">
            Belum ada data lapangan. Silakan <a href="{{ route('admin.lapangan.create') }}">tambah lapangan</a> terlebih dahulu.
        </div>
        @else
        <form action="{{ route('admin.jadwal.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Lapangan</label>
                <select name="lapangan_id" class="form-select" required>
                    <option value="">-- Pilih Lapangan --</option>
                    @foreach ($lapanganList as $l)
                    <option value="{{ $l->id }}" {{ old('lapangan_id') == $l->id ? 'selected' : '' }}>{{ $l->nama }} ({{ $l->jenis }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="status_tersedia" value="1" class="form-check-input" id="status_tersedia" checked>
                <label class="form-check-label" for="status_tersedia">Slot tersedia untuk dibooking</label>
            </div>

            <button type="submit" class="btn btn-navy">Simpan</button>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
        @endif
    </div>
</div>
@endsection