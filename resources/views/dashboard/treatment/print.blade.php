@extends('layouts.print')
@section("content")


<div class="no-print">
    <a href="{{ route('treatment.index') }}" class="btn btn-danger" style="margin-bottom:12px;">← Back</a>
</div>

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

@endsection
