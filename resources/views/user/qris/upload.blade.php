@extends('layouts.app')

@section('title', 'Upload Bukti QRIS - Courtside')

@section('back_url', route('user.qris.create', $booking))

@section('content')
<h3 style="color: var(--navy);" class="mb-3">Upload Bukti Pembayaran QRIS</h3>

<div class="card shadow-sm mx-auto" style="max-width: 480px;">
    <div class="card-body">

        {{-- Info Booking --}}
        <div class="alert alert-info py-2 px-3 mb-4">
            <p class="mb-1"><strong>{{ $booking->jadwal->lapangan->nama }}</strong></p>
            <p class="mb-1 small">{{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d-m-Y') }} &bull; {{ substr($booking->jadwal->jam_mulai, 0, 5) }} - {{ substr($booking->jadwal->jam_selesai, 0, 5) }}</p>
            <p class="mb-0 fw-semibold">Total: Rp {{ number_format($booking->jadwal->totalHarga(), 0, ',', '.') }}</p>
        </div>

        <p class="text-muted small mb-3">
            Upload <strong>screenshot/foto</strong> bukti pembayaran QRIS dari aplikasi e-wallet atau m-banking kamu. Admin akan memverifikasi dan mengkonfirmasi booking setelah bukti dicek.
        </p>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('user.qris.upload.store', $booking) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Bukti Pembayaran QRIS <span class="text-danger">*</span></label>
                <input type="file" name="bukti_transfer" class="form-control" accept="image/*" required
                       onchange="previewImage(event)">
                <div class="form-text">Format: JPG, PNG, GIF. Maks 2MB.</div>
            </div>

            {{-- Preview gambar --}}
            <div id="preview-container" class="mb-3 d-none text-center">
                <p class="small text-muted mb-1">Preview:</p>
                <img id="preview-img" src="#" alt="Preview" class="img-thumbnail" style="max-height: 220px;">
            </div>

            <button type="submit" class="btn btn-navy w-100">
                📤 Kirim Bukti Pembayaran
            </button>
            <a href="{{ route('user.booking.riwayat') }}" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('preview-container').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
