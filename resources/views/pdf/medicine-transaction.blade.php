<!DOCTYPE html>
<html>

<head>
    <title>Histori Transaksi Obat - {{ $medicine->name }}</title>
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

        .medicine-info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .medicine-info table {
            width: 100%;
            border: none;
        }

        .medicine-info table td {
            padding: 5px;
            border: none;
        }

        .medicine-info table td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .medicine-info table td:nth-child(2) {
            width: 10px;
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

        .badge-in {
            background-color: #d4edda;
            color: #155724;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
        }

        .badge-out {
            background-color: #f8d7da;
            color: #721c24;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Histori Transaksi Obat</h2>
        <p>Sistem Informasi Praktik Dokter</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</p>
    </div>

    <div class="medicine-info">
        <table>
            <tr>
                <td>Nama Obat</td>
                <td>:</td>
                <td>{{ $medicine->name }}</td>
            </tr>
            <tr>
                <td>Stok Saat Ini</td>
                <td>:</td>
                <td><strong>{{ $medicine->stock }} {{ $medicine->unit->name }}</strong></td>
            </tr>
        </table>
    </div>

    <h3 style="margin-top: 20px; font-size: 14px;">Tabel Transaksi</h3>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Penanggung Jawab</th>
                <th width="10%" class="text-center">Tipe Transaksi</th>
                <th width="8%" class="text-center">Kuantitas</th>
                <th width="8%" class="text-center">Stok Sebelum</th>
                <th width="8%" class="text-center">Stok Setelah</th>
                <th width="26%">Catatan</th>
                <th width="20%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicineStockTransactions as $index => $transaction)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $transaction->inventoryOfficer->fullname }}</td>
                    <td class="text-center">
                        @if ($transaction->type == 'IN')
                            <span class="badge-in">MASUK</span>
                        @else
                            <span class="badge-out">KELUAR</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $transaction->quantity }}</td>
                    <td class="text-center">{{ $transaction->beginningStock }}</td>
                    <td class="text-center">{{ $transaction->lastStock }}</td>
                    <td>{{ $transaction->notes ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->createdAt)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="no-data">Tidak ada data transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Transaksi: {{ count($medicineStockTransactions) }} transaksi</p>
    </div>
</body>

</html>
