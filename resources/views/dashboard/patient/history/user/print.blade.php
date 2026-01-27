@extends('layouts.print')
@section("content")

<div class="no-print" style="display:flex; justify-content:space-between; margin-bottom:12px;">
    <a href="{{ route('patient.index') }}" class="btn btn-danger btn-md">
        <i class="glyphicon glyphicon-arrow-left"></i> Kembali
    </a>

    <button class="btn btn-primary btn-md" onclick="window.print()">
        <i class="glyphicon glyphicon-print"></i> Cetak
    </button>
</div>

<x-dashboard-container title="Detail History Pasien a.n. {{ $patient->name }}">

<table class="table-bordered table-condensed table-hover">
    <div>
        <tr>
            Nama :
        </tr>

        <tr>
            {{ $patient->name }}
        </tr>
    </div>

    <div>
        <tr>
            Umur :
        </tr>

        <tr>
            {{ $patient->dateOfBirth->age }}
        </tr>
    </div>

    <div>
        <tr>
            Alamat :
        </tr>

        <tr>
            {{ $patient->address }}
        </tr>
    </div>

        <div>
        <tr>
            Jenis Kelamin :
        </tr>

        <tr>
            {{ $patient->gender }}
        </tr>
    </div>

    <br></br>
</table>

@foreach ($registrations as $registration)
    <div style="margin-bottom:20px; border:1px solid #000; padding:10px">

        <strong>No. Registrasi:</strong> {{ $registration->id }} <br>
        <strong>Tanggal:</strong>
        {{ $registration->checkDate
            ? $registration->checkDate->format('d-m-Y')
            : 'Belum diperiksa' }}
        <br>

        <strong>Keluhan:</strong>
        {{ $registration->complaint ?? '-' }}

        <hr>


        <strong>Diagnosis:</strong>
        <ul>
            @forelse ($registration->diagnoses as $diagnosis)
                <li>{{ $diagnosis->name }}</li>
            @empty
                <li>-</li>
            @endforelse
        </ul>


        <strong>Treatment:</strong>
        <ul>
            @forelse ($registration->treatments as $treatment)
                <li>{{ $treatment->name }}</li>
            @empty
                <li>-</li>
            @endforelse
        </ul>


        <strong>Obat:</strong>
        <ul>
            @forelse ($registration->medicineStockTransactions as $trx)
                <li>
                    {{ $trx->medicine->name }}
                    ({{ $trx->quantity }})
                </li>
            @empty
                <li>-</li>
            @endforelse
        </ul>

    </div>
@endforeach



{{-- <table class="table table-bordered table-hover table-condensed">

                {{-- <tr>
                    <td>Nama</td>
                    <td>{{ $patient->name }}</td>
                </tr>
                <tr>
                    <td>Umur</td>
                    <td>{{ $patient->dateOfBirth->age }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>{{ $patient->address }}</td>
                </tr>
                <tr>
                    <td>Registration</td>
                        <td>
                            @foreach ($registrations as $index => $registration)
                                <li>
                                    REG-No.
                                    {{ $registration->id }}.
                                    {{ $registration->complaint }},
                                    {{ $registration->checkDate ? $registration->checkDate->diffForHumans() : "Belum Periksa" }}
                                </li>
                            @endforeach
                        </td>
                </tr> --}}

</table> --}}

</x-dashboard-container>
@endsection
