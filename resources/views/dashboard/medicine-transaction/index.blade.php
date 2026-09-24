@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Data Transaksi Obat">
        @if(session('failed'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>Gagal!</strong> {{ session('failed') }}
        </div>
        @endif
        @can('create-transaction-medicine')
            <a href="{{ route('medicine.transaction.create.view') }}" class="btn btn-primary btn-md"><i
                    class="glyphicon glyphicon-ok"></i> Tambah Transaksi Obat</a>
        @endcan
        @can('print-all-transaction-medicine')
            <a href="{{ route('medicine.all.transaction.print') }}" class="btn btn-primary btn-md" target="_blank"><i
                    class="glyphicon glyphicon-print"></i> Cetak Data Transaksi Obat</a>
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
                        <th>No</th>
                        <th>Tanggal Transaksi</th>
                        <th>Jenis Transaksi</th>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th>Stok Awal</th>
                        <th>Stok Akhir</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $index => $transaction)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $transaction->createdAt->timezone('Asia/Jakarta')->format('H:i:s d-m-Y') }}</td>
                            <td>{{ $transaction->type }}</td>
                            <td>{{ $transaction->medicine->name }}</td>
                            <td>{{ $transaction->quantity }}</td>
                            <td>{{ $transaction->beginningStock }}</td>
                            <td>{{ $transaction->lastStock }}</td>
                            <td>{{ $transaction->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <ul class="pagination pagination-large pull-right"></ul>
        </div>
    </x-dashboard-container>
@endsection
