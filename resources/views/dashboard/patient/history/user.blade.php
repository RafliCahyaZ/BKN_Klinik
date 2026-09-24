@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Detail History Pasien">

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('failed'))
                <div class="alert alert-danger" role="alert">
                    {{ session('failed') }}
                </div>
            @endif

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th width="10%">No. Daftar</th>
                        <th width="5%">:</th>
                        <th width="55%">10</th>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
                        <th>:</th>
                        <th>{{ $patient->name }}</th>
                    </tr>
                    <tr>
                        <th>Umur</th>
                        <th>:</th>
                        <th>{{ $patient->dateOfBirth->age }} Th</th>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <th>:</th>
                        <th>{{ $patient->address }}</th>
                    </tr>
                </tbody>
            </table>

            <a href="{{ route("patient.user.history.print", $patient->id) }}" class="btn btn-info" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Data</a>
            <hr>

                <div class="unseen">
                    <table class="table table-bordered table-hover table-condensed data-table order-table ">
                        <thead>
                            <tr>
                            <th>No. Registrasi</th>
                            <th>Keluhan</th>
                            <th>Tgl Periksa Terakhir</th>
                            <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($registrations as $index => $registration)
                        <tr>
                            <td>{{ $registration->id }}</td>
                            <td>{{ $registration->complaint }}</td>
                            <td>{{ $registration->checkDate ? $registration->checkDate->diffForHumans() : "Belum Periksa" }}</td>
                            <td>
                                <a href="{{ route("patient.history.show", $registration->id) }}" class="btn btn-sm btn-info">
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
            </div>

            <div class="well">
                <a class="btn btn-info" href="{{ route("patient.index") }}">Kembali</a>
            </div>
    </x-dashboard-container>
@endsection
