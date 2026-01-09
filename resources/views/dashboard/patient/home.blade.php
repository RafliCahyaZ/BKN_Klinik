@extends('layouts.dashboard')

@section('content')
<x-dashboard-container title="Data Pasien">
                    <a href="{{ url('patient/create') }}" class="btn btn-primary btn-md"><i class="glyphicon glyphicon-ok"></i> Tambah Pasien</a>
                    <a href="{{ url('patient/print') }}" class="btn btn-primary btn-md" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Data Pasien</a>
                    <hr>

                    <div class="unseen">
                        <table class="table table-bordered table-hover table-condensed data-table order-table ">
                            <thead>
                                <tr>
                                <th>No</th>
                                <th>No. Rekam</th>
                                <th width="10%">Nama Pasien</th>
                                <th>JK</th>
                                <th>Umur</th>
                                <th>Riwayat Penyakit</th>
                                <th>Berkas RM</th>
                                <th>Alamat</th>
                                <th>Telp</th>
                                <th>Tgl. Register</th>
                                <th>Kelola</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <ul class="pagination pagination-large pull-right"></ul>
                    </div>
</x-dashboard-container>
@endsection
