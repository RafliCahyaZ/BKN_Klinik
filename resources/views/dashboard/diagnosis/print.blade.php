@extends('layouts.print')
@section("content")

<div class="no-print" style="display:flex; justify-content:space-between; margin-bottom:12px;">
    <a href="{{ route('diagnosis.index') }}" class="btn btn-danger btn-md">
        <i class="glyphicon glyphicon-arrow-left"></i> Kembali
    </a>

    <button class="btn btn-primary btn-md" onclick="window.print()">
        <i class="glyphicon glyphicon-print"></i> Cetak
    </button>
</div>

<x-dashboard-container title="Data Master Diagnosa">

    <table class="table table-bordered table-hover table-condensed data-table order-table">
        <thead>
            <tr>
                <th>Kode ICD</th>
                <th>Nama Penyakit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($diagnoses as $diagnosis)
                <tr>
                    <td>{{ $diagnosis->icdCode }}</td>
                    <td>{{ $diagnosis->diseaseName }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-dashboard-container>



@endsection
