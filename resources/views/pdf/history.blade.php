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

    <div class="content">
        <div class="header">
            <h2>HISTORY REKAM MEDIS</h2>
            
            <!-- Patient Information -->
            <div class="patient-info">
                <table class="info-table">
                    <tr>
                        <td class="label">ID Registrasi</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $registration->id }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nama Pasien</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $registration->patient->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $registration->patient->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Lahir</td>
                        <td class="colon">:</td>
                        <td class="value">
                            @if($registration->patient->dateOfBirth)
                                {{ \Carbon\Carbon::parse($registration->patient->dateOfBirth)->locale('id')->isoFormat('D MMMM YYYY') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $registration->patient->address ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Diagnoses Section -->
        <div class="section">
            <h3>Daftar Diagnosa</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="20%">Kode ICD</th>
                        <th width="80%">Nama Penyakit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registration->diagnoses as $diag)
                    <tr>
                        <td>{{ $diag->icdCode }}</td>
                        <td>{{ $diag->diseaseName }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="no-data">Tidak ada data diagnosa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Medicine History Section -->
        <div class="section">
            <h3>Riwayat Obat</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="70%">Nama Obat</th>
                        <th width="30%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registration->medicineStockTransactions as $trans)
                    <tr>
                        <td>{{ $trans->medicine->name }}</td>
                        <td class="text-center">{{ $trans->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="no-data">Tidak ada data obat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .content { 
            margin: 0 auto; 
            max-width: 800px; 
            padding: 20px;
        }
        .patient-info { margin-bottom: 30px; }
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
        .section { margin-bottom: 25px; }
        .section h3 { margin-bottom: 10px; color: #333; }
        .data-table { 
            max-width: 100%; 
            margin: 0 auto; 
        }
        .data-table th { text-align: left; font-weight: bold; }
        .text-center { text-align: center; }
        .no-data { text-align: center; color: #666; font-style: italic; }
    </style>
</body>
</html>