
@extends('layouts.dashboard')

@section('content')
<x-dashboard-container title="Data Master Obat">
                    <a href="{{ route('medicine.create') }}" class="btn btn-primary btn-md"><i class="glyphicon glyphicon-ok"></i> Tambah Data Master Obat</a>
                    <a href="{{ route('medicine.print') }}" class="btn btn-primary btn-md" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Master Data Obat</a>
                    <hr>

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

                    <div class="unseen">
                        <table class="table table-bordered table-hover table-condensed data-table order-table ">
                            <thead>
                                <tr>
                                <th>Kode ICD</th>
                                <th>Nama Penyakit</th>
                                <th>Kelola</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($medicines as $index => $medicine)
                                <tr>
                                    <td>{{ $medicine->id }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td>
                                        <a href="{{ route("diagnosis.edit.view", $medicine->id) }}" class="btn btn-sm btn-info" title="Ubah Data"><i class="glyphicon glyphicon-pencil"></i></a>
                                    <form
                                        style="display: inline-block;"
                                        action="{{ route('medicine.destroy', $medicine->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </button>
                                    </form>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <ul class="pagination pagination-large pull-right"></ul>
                    </div>
</x-dashboard-container>
@endsection
