@extends('layouts.app')

@section('title', 'Lupa Password - Courtside')

@section('content')
<div class="card card-auth shadow-sm">
    <div class="card-body p-4">
        <h4 class="mb-3 text-center" style="color: var(--navy);">Lupa Password</h4>
        <p class="text-muted small text-center">Masukkan email akun kamu. Kami akan kirimkan link untuk membuat password baru.</p>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <button type="submit" class="btn btn-navy w-100">Kirim Link Reset</button>
        </form>

        <p class="text-center mt-3 mb-0">
            <a href="{{ route('login') }}">&larr; Kembali ke Login</a>
        </p>
    </div>
</div>
@endsection