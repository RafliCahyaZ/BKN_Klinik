<!DOCTYPE html>
<html>

<head>
    <title>Data Laporan Periodik Klinik</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 10px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 8px;
        }

        .text-center {
            text-align: center;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: right;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Data Laporan Periodik Klinik</h2>
        <h2>Tanggal {{ $start->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY') }} -
            {{ $end->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY') }}</h2>
        <p>Sistem Informasi Praktik Dokter</p>
        <p>Dicetak pada:
            {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">No. Registrasi</th>
                <th width="30%">Nama Pasien</th>
                <th width="8%" class="text-center">Umur</th>
                <th width="10%" class="text-center">L/P</th>
                <th width="20%">Tanggal Periksa</th>
                <th width="12%">Dokter</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $index => $registration)
                @if ($registration->doctor)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $registration->id }}</td>
                        <td>{{ $registration->patient->name }}</td>
                        <td class="text-center">{{ $registration->patient->age }} Tahun</td>
                        <td class="text-center">{{ $registration->patient->gender }}</td>
                        <td>{{ \Carbon\Carbon::parse($registration->checkDate)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMM YYYY') }}
                        </td>
                        <td>{{ $registration->doctor->fullname }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="7" class="no-data">Tidak ada data registrasi pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Registrasi: {{ count($registrations) }} pasien</p>
    </div>
</body>

</html>
