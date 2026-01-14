@extends('layouts.dashboard')
@section('content')
<x-dashboard-container title="Daftar Antrian Pasien">
    <div class="unseen">
        <table class="table table-bordered table-hover table-condensed data-table order-table ">
            <thead>
                <tr>
                <th>No. Urut</th>
                <th>No. RM</th>
                <th>Nama Pasien</th>
                <th>Alamat</th>
                <th>Umur</th>
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
