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
    <h1>Laporan Absen Perminggu</h1>
    <p>Periode: {{ \Carbon\Carbon::now()->startOfWeek   ()->format('d M Y') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Tipe Karyawan</th>
                <th>Jumlah Absen</th>
                <th>Jumlah Izin</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekap as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['nama_karyawan'] }}</td>
                    <td>{{ $row['tipe_karyawan'] }}</td>
                    <td>{{ $row['absen'] }}</td>
                    <td>{{ $row['izin'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; font-weight: bold;">Tidak ada absen minggu ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
