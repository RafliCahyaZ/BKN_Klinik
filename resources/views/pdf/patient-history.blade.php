<!DOCTYPE html>
<html>

<head>
    <title>History Rekam Medis</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <div class="content">
        <div class="header">
            <h2>HISTORY REKAM MEDIS</h2>

            <!-- Patient Information -->
            <div class="patient-info">
                <table class="info-table">
                    <tr>
                        <td class="label">Nama Pasien</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $patient->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $patient->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Lahir</td>
                        <td class="colon">:</td>
                        <td class="value">
                            @if ($patient->dateOfBirth)
                                {{ \Carbon\Carbon::parse($patient->dateOfBirth)->locale('id')->isoFormat('D MMMM YYYY') }}
                                ({{ $patient->dateOfBirth->age }} Tahun)
                            @else
                                -
                            @endif
                        </td>
                    </tr>


                    <tr>
                        <td class="label">Alamat</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $patient->address ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Diagnoses Section -->
        <div class="section">
            @foreach ($registrations as $registration)
                @if ($registration->checkDate)
                    <div style="margin-bottom:20px; border:1px solid #000; padding:10px">

                        <strong>No. Registrasi:</strong> {{ $registration->id }} <br>
                        <strong>Tanggal:</strong>
                        {{ $registration->checkDate ? $registration->checkDate->format('d-m-Y') : 'Belum diperiksa' }}
                        <br>

                        <strong>Keluhan:</strong>
                        {{ $registration->complaint ?? '-' }}

                        <hr>

                        @if ($registration->diagnoses)
                            <strong>Diagnosis:</strong>
                            <ol>
                                {{ $diagnosis->name }}
                            </ol>
                        @endif


                        @if ($registration->treatments)
                            <strong>Treatment:</strong>
                            <ol>
                                {{ $treatment->name }}
                            </ol>
                        @endif

                        @if ($registration->medicineStockTransactions)
                            <strong>Obat:</strong>
                            <ol>
                                @foreach ($registration->medicineStockTransactions as $trx)
                                    <li>
                                        {{ $trx->medicine->name }}
                                        ({{ $trx->quantity }})
                                    </li>
                                @endforeach
                            </ol>
                        @endif


                    </div>
                @endif
            @endforeach


        </div>

    </div>

    <style>
        .content {
            margin: 0 auto;
            max-width: 800px;
            padding: 20px;
        }

        .patient-info {
            margin-bottom: 30px;
        }

        .info-table {
            border: none;
            margin-bottom: 20px;
            max-width: 400px;
        }

        .info-table td {
            padding: 5px 0;
            border: none;
        }

        .label {
            font-weight: bold;
            width: 150px;
            text-align: left;
        }

        .colon {
            width: 20px;
            text-align: center;
        }

        .value {
            padding-left: 10px;
            text-align: left;
        }

        .section {
            margin-bottom: 25px;
        }

        .section h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .data-table {
            max-width: 100%;
            margin: 0 auto;
        }

        .data-table th {
            text-align: left;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
        }
    </style>
</body>

</html>
