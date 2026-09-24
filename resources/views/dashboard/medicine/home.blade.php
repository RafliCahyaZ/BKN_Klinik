
@extends('layouts.dashboard')

@section('content')
<x-dashboard-container title="Data Master Obat">
                    @can('create-medicine')
                        <a href="{{ route('medicine.create') }}" class="btn btn-primary btn-md"><i class="glyphicon glyphicon-ok"></i> Tambah Data Master Obat</a>
                    @endcan

                    @can('print-medicine')
                        <a href="{{ route('medicine.print') }}" class="btn btn-primary btn-md" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Master Data Obat</a>
                    @endcan
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
                                <th>Kode Obat</th>
                                <th>Nama Obat</th>
                                <th>Stok</th>
                                @canany(['delete-medicine', 'edit-medicine', 'transaction-medicine'])
                                    <th>Kelola</th>
                                @endcanany
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($medicines as $index => $medicine)
                                <tr>
                                    <td>{{ $medicine->id }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->stock }} {{ $medicine->unit->name }}</td>
                                @canany(['delete-medicine', 'edit-medicine', 'get-transaction-medicine'])
                                    <td>
                                        @can('edit-medicine')
                                            <a href="{{ route("medicine.edit.view", $medicine->id) }}" class="btn btn-sm btn-info" title="Ubah Data"><i class="glyphicon glyphicon-pencil"></i></a>
                                        @endcan
                                        @can('get-transaction-medicine')
                                            <a class="btn btn-sm btn-warning" href="{{ route('medicine.transaction', $medicine->id) }}"  class="btn btn-sm btn-info" title="Lihat Data History"><i class="fa fa-history"></i></a>
                                        @endcan
                                        @can('delete-medicine')
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
                                        @endcan
                                    </td>
                                @endcanany
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <ul class="pagination pagination-large pull-right"></ul>
                    </div>
</x-dashboard-container>
@endsection
