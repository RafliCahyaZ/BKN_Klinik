<!DOCTYPE html>
<html>

<head>
    <title>Data Transaksi Obat</title>
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
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        .badge-in {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-out {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-adjustment {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-expired {
            background-color: #f5c6cb;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        td {
            padding: 6px;
            font-size: 11px;
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
        <h2>Laporan Stok Obat Periode</h2>
        <p>Sistem Informasi Praktik Dokter</p>
        <p><strong>Periode:</strong>
            {{ \Carbon\Carbon::parse($startDate)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY') }}
            s/d
            {{ \Carbon\Carbon::parse($endDate)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY') }}
        </p>
        <p>Dicetak pada:
            {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%" class="text-center">No</th>
                <th width="30%">Nama Obat</th>
                <th width="15%" class="text-center">Stok Awal Periode</th>
                <th width="15%" class="text-center">Stok Akhir Periode</th>
                <th width="15%" class="text-center">Selisih</th>
                <th width="17%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicineStockTransactions as $index => $medicineStock)
                @php
                    $selisih = $medicineStock['last_stock'] - $medicineStock['beginning_stock'];
                    $status = $selisih > 0 ? 'Bertambah' : ($selisih < 0 ? 'Berkurang' : 'Tetap');
                    $statusClass = $selisih > 0 ? 'badge-in' : ($selisih < 0 ? 'badge-out' : 'badge-adjustment');
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $medicineStock['name'] }}</td>
                    <td class="text-center">{{ $medicineStock['beginning_stock'] }}</td>
                    <td class="text-center">{{ $medicineStock['last_stock'] }}</td>
                    <td class="text-center">{{ $selisih > 0 ? '+' . $selisih : $selisih }}</td>
                    <td class="text-center">
                        <span class="{{ $statusClass }}">{{ $status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">Tidak ada data obat pada periode yang dipilih</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Total Obat:</strong> {{ count($medicineStockTransactions) }} obat</p>
        <p><strong>Periode Laporan:</strong>
            {{ \Carbon\Carbon::parse($startDate)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY') }}
            -
            {{ \Carbon\Carbon::parse($endDate)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY') }}
        </p>
    </div>
</body>

</html>
