@extends('layouts.print')
@section("content")

<div class="no-print" style="display:flex; justify-content:space-between; margin-bottom:12px;">
    <a href="{{ route('patient.history') }}" class="btn btn-danger btn-md">
        <i class="glyphicon glyphicon-arrow-left"></i> Kembali
    </a>

    <button class="btn btn-primary btn-md" onclick="window.print()">
        <i class="glyphicon glyphicon-print"></i> Cetak
    </button>
</div>

<x-dashboard-container title="Detail History Pasien No. Reg {{ $registration->id }}">

    <table class="table table-bordered table-hover table-condensed">
        <tbody>

                <tr>
                    <td>name</td>
                    <td>{{ $registration->patient->name }}</td>
                </tr>
                <tr>
                    <td>age</td>
                    <td>{{ $registration->patient->age }}</td>
                </tr>
                <tr>
                    <td>diagnosis</td>
                    <td>
                        <ol>
                            @foreach ($registration->diagnoses as $diagnosis)
                                <li>{{ $diagnosis->diseaseName}}</li>
                            @endforeach
                        </ol>
                    </td>

                </tr>
                <tr>
                    <td>treatment</td>
                    <td>
                        <ol>
                            @foreach ($registration->treatments as $treatment)
                                <li>{{ $treatment->treatmentName}}</li>
                            @endforeach
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td>medicine</td>
                    <td>
                        <ol>
                            @foreach ($registration->medicineStockTransactions as $medicineStockTransaction)
                                <li>{{$medicineStockTransaction->medicine->name}}</li>
                            @endforeach
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td>Resep</td>
                    <td>
                        {{ $registration->receipt }}
                    </td>
                </tr>
        </tbody>
    </table>


</x-dashboard-container>


@endsection
