@extends('layouts.dashboard')

@section('content')
<x-dashboard-container title="Data Pengguna">
                    <a href="{{ url('patient/create') }}" class="btn btn-warning"><i class="glyphicon glyphicon-plus"></i> Pengguna Baru</a>
                    <hr>

                    <div class="unseen">
                        <table class="table table-bordered table-hover table-condensed data-table order-table ">
                            <thead>
                                <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama Asli</th>
                                <th>Level</th>
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
