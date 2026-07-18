@extends('layouts.app')

@section('title', 'Reset Password - Courtside')

@section('content')
<div class="card card-auth shadow-sm">
    <div class="card-body p-4">
        <h4 class="mb-3 text-center" style="color: var(--navy);">Buat Password Baru</h4>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-navy w-100">Reset Password</button>
        </form>
    </div>
</div>
@endsection