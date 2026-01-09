@extends('layouts.dashboard')

@section('content')
<x-dashboard-container title="Data Master Diagnosa">
                    <a href="{{ url('patient/create') }}" class="btn btn-primary btn-md"><i class="glyphicon glyphicon-ok"></i> Tambah Data Master Tindakan</a>
                    <a href="{{ url('patient/print') }}" class="btn btn-primary btn-md" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Master Data Tindakan</a>
                    <hr>

                    <div class="unseen">
                        <table class="table table-bordered table-hover table-condensed data-table order-table ">
                            <thead>
                                <tr>
                                <th>Kode ICD</th>
                                <th>Nama Tindakan</th>
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
