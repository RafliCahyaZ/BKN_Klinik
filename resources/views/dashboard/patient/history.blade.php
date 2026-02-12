@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Daftar History Pasien">

        <a href="{{ route('patient.all-history.print') }}" class="btn btn-info" target="_blank"><i
                class="glyphicon glyphicon-print"></i> Cetak Data Semua Registrasi Pasien</a>
        <hr>

        <form action="" method="GET" >
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label>
                    <div class="btn-group" style="display: block;">
                        <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-filter"></i>
                            Filter</button>
                        <a href="{{ route('patient.history') }}" class="btn btn-default">Reset</a>
                        
                    </div>
                </div>
            </div>
        </form>

        <div class="unseen">
            <table class="table table-bordered table-hover table-condensed data-table order-table ">
                <thead>
                    <tr>
                        <th>No. Registrasi</th>
                        <th>ID Pasien</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin</th>
                        <th>Umur</th>
                        <th>Alamat</th>
                        <th>No. Telp</th>
                        <th>Tanggal Periksa Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($histories as $index => $history)
                        <tr>
                            <td>{{ $history->id }}</td>
                            <td>{{ $history->patient->id }}</td>
                            <td>{{ $history->patient->name }}</td>
                            <td>{{ $history->patient->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $history->patient->age }} Tahun</td>
                            <td>{{ $history->patient->address }}</td>
                            <td>{{ $history->patient->telpNumber }}</td>
                            <td>{{ $history->checkDate->locale('id')->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('patient.history.show', $history->id) }}" class="btn btn-sm btn-info">
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
