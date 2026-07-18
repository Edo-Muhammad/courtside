<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking)
    {
        //
    }

    /**
     * Kirim notifikasi lewat database saja (ditampilkan di dalam aplikasi).
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $lapangan = $this->booking->jadwal->lapangan->nama;
        $tanggal = \Carbon\Carbon::parse($this->booking->jadwal->tanggal)->format('d-m-Y');

        $pesan = match ($this->booking->status) {
            'approved' => "Booking kamu untuk {$lapangan} pada {$tanggal} telah disetujui.",
            'rejected' => "Booking kamu untuk {$lapangan} pada {$tanggal} ditolak.",
            default => "Status booking kamu untuk {$lapangan} pada {$tanggal} telah diperbarui.",
        };

        return [
            'booking_id' => $this->booking->id,
            'status' => $this->booking->status,
            'message' => $pesan,
        ];
    }
}
