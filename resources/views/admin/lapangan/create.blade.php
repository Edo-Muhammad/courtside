@extends('layouts.app')

@section('title', 'Tambah Lapangan - Courtside')

@section('back_url', route('admin.lapangan.index'))

@section('content')
<h3 style="color: var(--navy);">Tambah Lapangan</h3>

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

        <form action="{{ route('admin.lapangan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Lapangan</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Olahraga</label>
                <input type="text" name="jenis" class="form-control" value="{{ old('jenis') }}" placeholder="Futsal / Badminton / dll" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga per Jam (Rp)</label>
                <input type="number" name="harga_per_jam" class="form-control" value="{{ old('harga_per_jam') }}" min="0" step="1000" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Lapangan</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
            </div>

            <button type="submit" class="btn btn-navy">Simpan</button>
            <a href="{{ route('admin.lapangan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection