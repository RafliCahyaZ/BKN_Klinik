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

<x-dashboard-container title="Detail History Pasien Atas Nama {{ $patient->name }}">
<table class="table table-bordered table-hover table-condensed">

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
            Complaints :
        </tr>

        <tr>
                        <ol>
                            @foreach ($registrations as $index => $registration)
                            <ol>
                                @foreach ($registration->diagnoses as $diagnosis)
                                        <li>{{ $diagnosis->diseaseName}}</li>
                                @endforeach
                            </ol>
                            {{$registration->complaint}}
                            @endforeach
                        </ol>
        </tr>
    </div>
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

</table>

</x-dashboard-container>
@endsection
