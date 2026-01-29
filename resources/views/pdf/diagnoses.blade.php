<!DOCTYPE html>
<html>

<head>
    <title>Master Data Diagnosa</title>
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
        <h2>Master Data Diagnosa</h2>
        <p>Sistem Informasi Praktik Dokter</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%" class="text-center">No</th>
                <th width="25%">Kode ICD</th>
                <th width="65%">Nama Penyakit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($diagnoses as $index => $diagnosis)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $diagnosis->icdCode }}</td>
                    <td>{{ $diagnosis->diseaseName }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="no-data">Tidak ada data diagnosa</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Data: {{ count($diagnoses) }} diagnosa</p>
    </div>
</body>

</html>
