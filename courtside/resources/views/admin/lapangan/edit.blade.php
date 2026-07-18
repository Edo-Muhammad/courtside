@extends('layouts.app')

@section('title', 'Edit Lapangan - Courtside')

@section('content')
<h3 style="color: var(--navy);">Edit Lapangan</h3>

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

        <form action="{{ route('admin.lapangan.update', $lapangan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Lapangan</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $lapangan->nama) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Olahraga</label>
                <input type="text" name="jenis" class="form-control" value="{{ old('jenis', $lapangan->jenis) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga per Jam (Rp)</label>
                <input type="number" name="harga_per_jam" class="form-control" value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" min="0" step="1000" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Lapangan</label>
                @if ($lapangan->foto)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $lapangan->foto) }}" width="120" class="rounded d-block">
                </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
            </div>

            <button type="submit" class="btn btn-navy">Update</button>
            <a href="{{ route('admin.lapangan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection