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
            </tbody>
        </table>

        <ul class="pagination pagination-large pull-right"></ul>
    </div>
</x-dashboard-container>
@endsection
