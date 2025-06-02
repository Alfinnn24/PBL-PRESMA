<!DOCTYPE html>
<html>
<head>
    <title>Laporan Prestasi Mahasiswa</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h3 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h3>Laporan Prestasi Mahasiswa</h3>
    <table>
        <thead>
            <tr>
                <th>Mahasiswa</th>
                <th>Lomba</th>
                <th>Bidang Keahlian</th>
                <th>Tingkat</th>
                <th>Tahun Prestasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ optional(optional($item->detailPrestasi->first())->mahasiswa)->nama_lengkap ?? '-' }}</td>
                <td>{{ optional($item->lomba)->nama ?? '-' }}</td>
                <td>{{ optional(optional($item->lomba)->bidangKeahlian)->keahlian ?? '-' }}</td>
                <td>{{ optional($item->lomba)->tingkat ?? '-' }}</td>
                <td>
                    @if(!empty($item->lomba->tanggal_mulai))
                        {{ \Carbon\Carbon::parse($item->lomba->tanggal_mulai)->format('Y') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
