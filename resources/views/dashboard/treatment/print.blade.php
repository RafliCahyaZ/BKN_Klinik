@extends('layouts.print')
@section("content")


<div class="no-print" style="display:flex; justify-content:space-between; margin-bottom:12px;">
    <a href="{{ route('patient.index') }}" class="btn btn-danger btn-md">
        <i class="glyphicon glyphicon-arrow-left"></i> Kembali
    </a>

    <button class="btn btn-primary btn-md" onclick="window.print()">
        <i class="glyphicon glyphicon-print"></i> Cetak
    </button>
</div>

<x-dashboard-container title="Data Master Tindakan">
    <table class="table table-bordered table-hover table-condensed data-table order-table">
        <thead>
            <tr>
                <th>Kode Tindakan</th>
                <th>Nama Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($treatments as $treatment)
                <tr>
                    <td>{{ $treatment->treatmentCode }}</td>
                    <td>{{ $treatment->treatmentName }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-dashboard-container>




@endsection
