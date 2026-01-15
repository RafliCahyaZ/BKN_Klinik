@extends('layouts.print')
@section("content")

<div class="no-print">
    <a href="{{ route('diagnosis.index') }}" class="btn btn-danger" style="margin-bottom:12px;">← Back</a>
</div>


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
@endsection
