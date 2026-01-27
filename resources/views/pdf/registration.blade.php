<!DOCTYPE html>
<html>
<head>
    <title>History Rekam Medis</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>HISTORY REKAM MEDIS</h2>
        <p>Nama Pasien: {{ $registration->patient->name }} | ID: {{ $registration->id }}</p>
    </div>

    <h3>Daftar Diagnosa</h3>
    <table>
        <thead>
            <tr>
                <th>Kode ICD</th>
                <th>Nama Penyakit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registration->diagnoses as $diag)
            <tr>
                <td>{{ $diag->icd_code }}</td>
                <td>{{ $diag->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Riwayat Obat</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Obat</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registration->medicineStockTransactions as $trans)
            <tr>
                <td>{{ $trans->medicine->name }}</td>
                <td>{{ $trans->quantity }}</td>
                <td>{{ strtoupper($trans->type) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>