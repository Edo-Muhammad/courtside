<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Booking Courtside</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            color: #1a1a1a;
        }

        h2 {
            color: #1F3864;
            margin-bottom: 0;
        }

        p.subtitle {
            color: #555;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            font-size: 13px;
            text-align: left;
        }

        th {
            background-color: #1F3864;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f4f6f9;
        }

        .summary {
            margin-top: 20px;
            font-size: 14px;
        }

        .no-print {
            margin-top: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <h2>Laporan Booking - Courtside</h2>
    <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($dariTanggal)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($sampaiTanggal)->format('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Penyewa</th>
                <th>Lapangan</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Harga</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $item)
            <tr>
                <td>{{ $item->user->nama }}</td>
                <td>{{ $item->jadwal->lapangan->nama }}</td>
                <td>{{ \Carbon\Carbon::parse($item->jadwal->tanggal)->format('d-m-Y') }}</td>
                <td>{{ substr($item->jadwal->jam_mulai, 0, 5) }} - {{ substr($item->jadwal->jam_selesai, 0, 5) }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>Rp {{ number_format($item->jadwal->lapangan->harga_per_jam, 0, ',', '.') }}</td>
                <td>{{ $item->pembayaran ? ucfirst($item->pembayaran->status_verifikasi) : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;">Tidak ada data booking pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p class="summary"><strong>Total Estimasi Pendapatan (booking disetujui):</strong> Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>

    <div class="no-print">
        <button onclick="window.print()">Cetak / Simpan sebagai PDF</button>
    </div>
</body>

</html>