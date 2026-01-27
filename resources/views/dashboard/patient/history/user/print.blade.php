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
     <tbody>
                <tr>
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
                    <td>registration</td>
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
                </tr>
 </tbody>
</table>

</x-dashboard-container>
@endsection
