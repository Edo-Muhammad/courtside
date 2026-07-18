<?php

namespace App\Notifications;

use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public Pembayaran $pembayaran)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $lapangan = $this->pembayaran->booking->jadwal->lapangan->nama;

        $pesan = match ($this->pembayaran->status_verifikasi) {
            'terverifikasi' => "Pembayaran kamu untuk booking {$lapangan} telah diverifikasi.",
            'ditolak' => "Pembayaran kamu untuk booking {$lapangan} ditolak. Silakan unggah ulang bukti transfer.",
            default => "Status pembayaran kamu untuk booking {$lapangan} telah diperbarui.",
        };

        return [
            'booking_id' => $this->pembayaran->booking_id,
            'status_verifikasi' => $this->pembayaran->status_verifikasi,
            'message' => $pesan,
        ];
    }
}
