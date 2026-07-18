@extends('layouts.app')

@section('title', 'Profil Saya - Courtside')

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Profil Saya</h3>

<div class="card shadow-sm" style="max-width: 500px;">
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

        <form action="{{ route('profil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-2">
                <span class="badge bg-secondary">Role: {{ ucfirst($user->role) }}</span>
            </div>

            <hr>
            <p class="text-muted small mb-2">Kosongkan bagian di bawah ini jika tidak ingin mengganti password.</p>

            <div class="mb-3">
                <label class="form-label">Password Lama</label>
                <input type="password" name="password_lama" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-navy">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection