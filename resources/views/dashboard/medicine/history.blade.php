@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Histori Transaksi {{ $medicine->name }}">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th width="10%">Nama Obat</th>
                    <th width="5%">:</th>
                    <th width="55%">{{ $medicine->name }}</th>
                </tr>
                <tr>
                    <th>Stok saat ini</th>
                    <th>:</th>
                    <th>{{ $medicine->stock }}</th>
                </tr>
            </tbody>
        </table>
    </x-dashboard-container>

    <div style="margin-left: 15px; padding: 12px 0">
        <a href="{{ route('medicine.history.print', $medicine->id) }}" class="btn btn-primary btn-md"><i
                class="glyphicon glyphicon-print"></i> Cetak Histori Transaksi</a>
    </div>


    <x-dashboard-container title="Tabel Transaksi {{ $medicine->name }}">


        <div class="unseen">
            <table class="table table-bordered table-hover table-condensed data-table order-table ">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Penanggung Jawab</th>
                        <th>Tipe Transaksi</th>
                        <th>Kuantitas</th>
                        <th>Stok Sebelum</th>
                        <th>Stok Setelah</th>
                        <th>Catatan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($medicineStockTransactions as $index => $medicineStockTransaction)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $medicineStockTransaction->inventoryOfficer->fullname }}</td>
                            <td>{{ $medicineStockTransaction->type }}</td>
                            <td>{{ $medicineStockTransaction->quantity }}</td>
                            <td>{{ $medicineStockTransaction->beginningStock }}</td>
                            <td>{{ $medicineStockTransaction->lastStock }}</td>
                            <td>{{ $medicineStockTransaction->notes }}</td>
                            <td>{{ $medicineStockTransaction->createdAt }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div>
                <a href="{{ route('medicine.edit.view', $medicine->id) }}" class="btn btn-danger">
                    Kembali
                </a>
            </div>

            <ul class="pagination pagination-large pull-right"></ul>
        </div>
    </x-dashboard-container>
@endsection
