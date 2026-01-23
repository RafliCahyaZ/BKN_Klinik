@extends('layouts.print')
@section("content")

<div class="no-print" style="display:flex; justify-content:space-between; margin-bottom:12px;">
    <a href="{{route('medicine.index') }}" class="btn btn-danger btn-md">
        <i class="glyphicon glyphicon-arrow-left"></i> Kembali
    </a>

    <button class="btn btn-primary btn-md" onclick="window.print()">
        <i class="glyphicon glyphicon-print"></i> Cetak
    </button>
</div>

<x-dashboard-container title="Data Master Obat">
    <table class="table table-bordered table-hover table-condensed data-table order-table">
        <thead>
            <tr>
                <th>Kode Obat</th>
                <th>Nama Obat</th>
                <th>Stok</th>
                <th>Unit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($medicines as $medicine)
                <tr>
                    <td>{{ $medicine->id }}</td>
                    <td>{{ $medicine->name }}</td>
                    <td>{{ $medicine->stock }}</td>
                    <td>{{ $medicine->unit->name}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-dashboard-container>
@endsection
