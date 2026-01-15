@extends('layouts.print')
@section("content")

<div class="no-print">
    <a href="{{ route('patient.index') }}" class="btn btn-danger" style="margin-bottom:12px;">← Back</a>
</div>

<table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Rekam</th>
                <th>Nama Pasien</th>
                <th>JK</th>
                <th>Umur</th>
                <th>Riwayat Penyakit</th>
                <th>Berkas RM</th>
                <th>Alamat</th>
                <th>Telp</th>
                <th>Tgl. Register</th>
            </tr>
        </thead>
        <tbody>
        @foreach($patients as $index => $patient)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $patient->medicalRecordNumber }}</td>
                <td>{{ $patient->name }}</td>
                <td>{{ ($patient->gender === "F") ? "Perempuan" : "Laki-laki" }}</td>
                <td>{{ $patient->age }}</td>
                <td>{{ $patient->history }}</td>
                <td>{{ $patient->medicalRecordFileUrl }}</td>
                <td>{{ $patient->address }}</td>
                <td>{{ $patient->telpNumber }}</td>
                <td>{{ $patient->createdAt->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
</table>
@endsection
