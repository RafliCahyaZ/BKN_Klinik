@extends('layouts.dashboard')
@section('content')
<x-dashboard-container title="Daftar Antrian Pasien">
    <div class="unseen">
        <table class="table table-bordered table-hover table-condensed data-table order-table ">
            <thead>
                <tr>
                <th>No. Urut</th>
                <th>Nama Pasien</th>
                <th>Alamat</th>
                <th>Umur</th>
                <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($registrations as $index => $registration)
                <tr>
                    <td>{{ $index }}</td>
                    <td>{{ $registration->patient->name }}</td>
                    <td>{{ $registration->patient->address }}</td>
                    <td>{{ $registration->patient->dateOfBirth->age }} Tahun</td>
                    <td>
                        <a href="{{ route("patient.appointment.view", $registration->id) }}" class="btn btn-sm btn-success">
                            <i class="glyphicon glyphicon-search"></i>
                            Mulai Periksa
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <ul class="pagination pagination-large pull-right"></ul>
    </div>
</x-dashboard-container>
@endsection
