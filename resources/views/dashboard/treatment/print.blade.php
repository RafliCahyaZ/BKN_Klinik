<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cetak Data Tindakan</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
   <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; font-size: 12px; }
    </style>
</head>

<style>
@media print { .no-print { display:none !important; } }
</style>

<div class="no-print">
    <a href="{{ route('treatment.index') }}" class="btn btn-danger" style="margin-bottom:12px;">← Back</a>
</div>

<body onload="window.print()">
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

</body>
</html>
