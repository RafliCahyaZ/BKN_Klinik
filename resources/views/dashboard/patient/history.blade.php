@extends('layouts.dashboard')

@section('content')
<x-dashboard-container title="Daftar History Pasien">
    <div class="unseen">
        <table class="table table-bordered table-hover table-condensed data-table order-table ">
            <thead>
                <tr>
                <th>No. Registrasi</th>
                <th>Nama Pasien</th>
                <th>Jenis Kelamin</th>
                <th>Umur</th>
                <th>Alamat</th>
                <th>No. Telp</th>
                <th>Tgl Periksa Terakhir</th>
                <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($histories as $index => $history)
            <tr>
                <td>{{ $history->id }}</td>
                <td>{{ $history->patient->name }}</td>
                <td>{{ $history->patient->gender }}</td>
                <td>{{ $history->patient->age }} Tahun</td>
                <td>{{ $history->patient->address }}</td>
                <td>{{ $history->patient->telpNumber }}</td>
                <td>{{ $history->checkDate->diffForHumans() }}</td>
                <td>
                    {{-- <a href="{{ route("patient.history.show", $history->id) }}" class="btn btn-sm btn-info">
                        <i class="glyphicon glyphicon-search"></i>
                        Detail
                    </a> --}}
                    <a href="{{ route("patient.history.show", $history->id) }}" class="btn btn-sm btn-info">
                        <i class="glyphicon glyphicon-search"></i>
                        Detail
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
