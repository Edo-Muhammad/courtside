@extends('layouts.app')

@section('title', 'Edit Profil - Courtside')

@section('back_url', auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard'))

@section('content')
<h3 style="color: var(--navy);" class="mb-4">Profil Saya</h3>

<div class="row g-4" style="max-width: 700px;">

    {{-- ── INFO PROFIL ── --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold" style="background: var(--navy); color: #fff;">
                Informasi Akun
            </div>
            <div class="card-body">

                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

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

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $user->nama) }}" required>
                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <p class="text-muted small mb-3">
                        Isi bagian berikut hanya jika ingin <strong>mengganti password</strong>. Kosongkan jika tidak ingin mengubah password.
                    </p>

                    {{-- Password Lama --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Lama</label>
                        <div class="input-group">
                            <input type="password" name="password_lama" id="password_lama"
                                   class="form-control @error('password_lama') is-invalid @enderror"
                                   placeholder="Masukkan password lama kamu" autocomplete="current-password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_lama', this)">👁</button>
                            @error('password_lama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Password Baru --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimal 8 karakter" autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">👁</button>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control"
                                   placeholder="Ulangi password baru" autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation', this)">👁</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-navy px-4">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁';
    }
}
</script>
@endsection
