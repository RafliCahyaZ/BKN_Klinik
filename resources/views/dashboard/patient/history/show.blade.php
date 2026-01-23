@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Detail History Pasien">

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

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th width="10%">No. Daftar</th>
                        <th width="5%">:</th>
                        <th width="55%">10</th>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
                        <th>:</th>
                        <th>{{ $registration->patient->name }}</th>
                    </tr>
                    <tr>
                        <th>Umur</th>
                        <th>:</th>
                        <th>{{ $registration->patient->dateOfBirth->age }} Th</th>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <th>:</th>
                        <th>{{ $registration->patient->address }}</th>
                    </tr>
                    <tr>
                        <th>Diperiksa Oleh</th>
                        <th>:</th>
                        <th>{{ $registration->doctor ? $registration->doctor->fullname : 'Belum Diperiksa' }}</th>
                    </tr>
                </tbody>
            </table>

            <a href="{{ route('patient.history.print', $registration->id) }}" class="btn btn-info" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Data</a>
            <hr>

            <h4>Data Medik Pasien</h4>
            <hr>
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="periksa" class="active">
                        <a href="#keluhan" aria-controls="keluhan" role="tab" data-toggle="tab">Keluhan</a>
                    </li>
                    <li role="periksa">
                        <a href="#diagnosa" aria-controls="diagnosa" role="tab" data-toggle="tab">Diagnosa</a>
                    </li>
                    <li role="periksa">
                        <a href="#tindakan" aria-controls="tindakan" role="tab" data-toggle="tab">Tindakan</a>
                    </li>
                    <li role="periksa">
                        <a href="#terapi" aria-controls="terapi" role="tab" data-toggle="tab">Terapi</a>
                    </li>
                    <li role="periksa">
                        <a href="#medicine" aria-controls="medicine" role="tab" data-toggle="tab">Obat</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="keluhan">
                    <textarea disabled style="min-height: 100px; width: 100%">
                        {{$registration->complaint}}
                    </textarea>
                </div>
                <div role="tabpanel" class="tab-pane" id="diagnosa">
                <table class="table table-striped">
                    <tbody id="diagnosisTable">
                        @foreach($registration->diagnoses as $key => $diagnosis)
                            <tr>
                                <td colspan="2">{{ $diagnosis->icdCode }} - {{ $diagnosis->diseaseName }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="tindakan">
                <table class="table table-striped">
                    <tbody id="diagnosisTable">
                        @foreach($registration->treatments as $key => $treatment)
                            <tr>
                                <td colspan="2">{{ $treatment->treatmentCode }} - {{ $treatment->treatmentName }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="terapi">
                    <textarea disabled style="min-height: 100px; width: 100%">
                        {{$registration->receipt}}
                    </textarea>
                </div>

                <div role="tabpanel" class="tab-pane" id="medicine">
                <table class="table table-striped">
                    <tbody id="diagnosisTable">
                        @foreach($registration->medicineStockTransactions as $key => $medicineStockTransaction)
                            <tr>
                                <td colspan="2">{{ $medicineStockTransaction->medicine->name }} - {{ $medicineStockTransaction->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

            </div>

            <div id="dynamic-data">
            </div>

            <div class="well">
                <a class="btn btn-info" href="{{ route("patient.history") }}">Kembali</a>
    </x-dashboard-container>
@endsection

@push("scripts")
<script>
    class AttachManager {
        constructor(
            submitButton,
            tableElement,
            selectElement,
            inputName
        ) {
            this.submitButton = submitButton
            this.tableElement = tableElement
            this.selectElement = selectElement
            this.inputName = inputName
            this.dynamicDataContainer = document.getElementById("dynamic-data");

            this.submitButton.addEventListener("click", () => {
                const selected = this.getSelected();
                this.appendData(selected);
            })
        }

        getSelected()
        {
            const selected = {
                value: this.selectElement.value,
                text: this.selectElement.options[this.selectElement.selectedIndex].text,
                icd: this.selectElement.options[this.selectElement.selectedIndex].dataset.icd
            };

            return selected
        }

        createInputElement(selected)
        {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `${this.inputName}[]`;
            input.value = selected.value;

            this.dynamicDataContainer.appendChild(input);
        }

        appendData(selected) {
            const appendedElement = `
                <tr>
                    <td>${selected.icd} - ${selected.text}</td>
                    <td>
                        <a href="#" onclick="hapus('11','000044','10467','2026-01-14')" title="Hapus Data Diagnosa" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
            `
            this.tableElement.insertAdjacentHTML("beforeend", appendedElement);
            this.createInputElement(selected);
        }
    }

    const diagnosisTable = document.getElementById("diagnosisTable");
    const submitDiagnosisButton = document.getElementById('submitDiagnosisButton');
    const selectDiagnosisElement = document.getElementById("selectDiagnosisElement");

    const diagnosisAttachManager = new AttachManager(submitDiagnosisButton, diagnosisTable, selectDiagnosisElement, 'diagnosis_ids');

    const treatmentTable = document.getElementById("treatmentTable");
    const submitTreatmentButton = document.getElementById('submitTreatmentButton');
    const selectTreatmentElement = document.getElementById("selectTreatmentElement");

    const treatmentAttachManager = new AttachManager(submitTreatmentButton, treatmentTable, selectTreatmentElement, 'treatment_ids');
</script>
@endpush
