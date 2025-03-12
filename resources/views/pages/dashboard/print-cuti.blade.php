<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Cuti Perminggu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body onload="window.print()">
    <h1>Laporan Cuti Perminggu</h1>
    <p>Periode: {{ \Carbon\Carbon::now()->startOfWeek()->format('d M Y') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Cuti</th>
                <th>Jumlah Hari</th>
                <th>Alasan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $cuti)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $cuti->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($cuti->created_at)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($cuti->tanggal_cuti)->format('d M Y') }} - {{ \Carbon\Carbon::parse($cuti->tanggal_cuti_selesai)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($cuti->tanggal_cuti)->diffInDays($cuti->tanggal_cuti_selesai) + 1 }} hari</td>
                    <td>{{ $cuti->alasan_cuti }}</td>
                    <td>
                        @if ($cuti->status_permohonan == true)
                            <span style="color: green; font-weight: bold;">Disetujui</span>
                        @elseif ($cuti->status_permohonan == false)
                            <span style="color: red; font-weight: bold;">Ditolak</span>
                        @else
                            <span style="color: orange; font-weight: bold;">Menunggu</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; font-weight: bold;">Tidak ada data cuti minggu ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
