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

@php
    $currentPatientId = null;
@endphp

@foreach ($registrations as $registration)

    {{-- JIKA PASIEN BERUBAH --}}
    @if ($currentPatientId !== $registration->patient->id)

        {{-- PAGE BREAK (kecuali data pertama) --}}
        @if ($currentPatientId !== null)
            <div class="page-break"></div>
        @endif

        @php
            $currentPatientId = $registration->patient->id;
            $patient = $registration->patient;
        @endphp

        <h2 style="text-align:center;">HISTORY REKAM MEDIS</h2>

        {{-- DATA PASIEN --}}
        <table>
            <tr>
                <td class="label">Nama Pasien</td>
                <td class="colon">:</td>
                <td class="value">{{ $patient->name }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="colon">:</td>
                <td class="value">
                    {{ $patient->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                </td>
            </tr>
            <tr>
                <td class="label">Tanggal Lahir</td>
                <td class="colon">:</td>
                <td class="value">
                    @if ($patient->dateOfBirth)
                        {{ $patient->dateOfBirth->locale('id')->isoFormat('D MMMM YYYY') }}
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

        <hr>
    @endif

    {{-- DATA REGISTRASI --}}
    <div class="registration-box">
        <strong>No. Registrasi:</strong> {{ $registration->id }} <br>
        <strong>Tanggal:</strong>
        {{ $registration->checkDate ? $registration->checkDate->format('d-m-Y') : 'Belum diperiksa' }} <br>

        <strong>Keluhan:</strong>
        {{ $registration->complaint ?? '-' }}

        <hr>

        <strong>Diagnosis:</strong>
        <ol>
            @forelse ($registration->diagnoses as $diagnosis)
                <li>{{ $diagnosis->name }}</li>
            @empty
                <li>Belum Ada Diagnosa</li>
            @endforelse
        </ol>

        <strong>Treatment:</strong>
        <ol>
            @forelse ($registration->treatments as $treatment)
                <li>{{ $treatment->name }}</li>
            @empty
                <li>Belum Ada Tindakan</li>
            @endforelse
        </ol>

        <strong>Obat:</strong>
        <ol>
            @forelse ($registration->medicineStockTransactions as $trx)
                <li>
                    {{ $trx->medicine->name }} ({{ $trx->quantity }})
                </li>
            @empty
                <li>Belum Ada Obat</li>
            @endforelse
        </ol>
    </div>

@endforeach



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
