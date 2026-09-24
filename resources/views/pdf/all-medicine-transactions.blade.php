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
        <h2>Data Transaksi Obat</h2>
        <p>Sistem Informasi Praktik Dokter</p>
        <p>Dicetak pada:
            {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="10%" class="text-center">Tipe</th>
                <th width="20%">Nama Obat</th>
                <th width="8%" class="text-center">Jumlah</th>
                <th width="8%" class="text-center">Stok Awal</th>
                <th width="8%" class="text-center">Stok Akhir</th>
                <th width="15%">Petugas</th>
                <th width="12%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicineStockTransactions as $index => $transaction)
                @php
                    $typeValue =
                        $transaction->type instanceof \App\Type\TransactionType
                            ? $transaction->type->value
                            : strtolower((string) ($transaction->type ?? ''));
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->createdAt)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}
                    </td>
                    <td class="text-center">
                        @if ($typeValue === 'in')
                            <span class="badge-in">MASUK</span>
                        @elseif ($typeValue === 'out')
                            <span class="badge-out">KELUAR</span>
                        @elseif ($typeValue === 'adjustment')
                            <span class="badge-adjustment">PENYESUAIAN</span>
                        @elseif ($typeValue === 'expired')
                            <span class="badge-expired">KADALUARSA</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $transaction->medicine->name ?? '-' }}</td>
                    <td class="text-center">{{ $transaction->quantity }}</td>
                    <td class="text-center">{{ $transaction->beginningStock }}</td>
                    <td class="text-center">{{ $transaction->lastStock }}</td>
                    <td>{{ $transaction->inventoryOfficer->fullname ?? '-' }}</td>
                    <td>{{ $transaction->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="no-data">Tidak ada data transaksi obat</td>
                </tbody>
                </tr>
            @endforelse
    </table>

    <div class="footer">
        <p>Total Transaksi: {{ count($medicineStockTransactions) }} transaksi</p>
    </div>
</body>

</html>
