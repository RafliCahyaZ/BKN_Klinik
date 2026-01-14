@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Pemeriksaan Pasien">
        <form
            method="post"
            action="{{ route('patient.appointment.action', $registration->id) }}"
        >
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
                        <th>Keluhan</th>
                        <th>:</th>
                        <th>{{ $registration->complaint }}</th>
                    </tr>
                    <tr>
                        <th>Diperiksa Oleh</th>
                        <th>:</th>
                        <th>
                            <div class="form-group">
                                <select name="tampil" class="form-control">
                                @foreach($doctors as $index => $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->fullname }}</option>
                                @endforeach
                                </select>
                            </div>
                        </th>
                    </tr>
                </tbody>
            </table>

            <h4>Data Medik Pasien</h4>
            <hr>
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="periksa" class="active">
                        <a href="#diagnosa" aria-controls="diagnosa" role="tab" data-toggle="tab">Diagnosa</a>
                    </li>
                    <li role="periksa">
                        <a href="#tindakan" aria-controls="tindakan" role="tab" data-toggle="tab">Tindakan</a>
                    </li>
                    <li role="periksa">
                        <a href="#terapi" aria-controls="terapi" role="tab" data-toggle="tab">Terapi</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="diagnosa">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th colspan="2">Input Diagnosa</th>
                        </tr>

                        <tr>
                            <th>
                                <select name="diagnosa" id="selectDiagnosisElement" class="form-control select2">
                                    @foreach($diagnoses as $index => $diagnosis)
                                        <option data-icd="{{ $diagnosis->icdCode }}" value="{{ $diagnosis->id }}">{{ $diagnosis->diseaseName }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <input type="button" name="" value="Tambah" id="submitDiagnosisButton" class="btn btn-info">
                            </th>
                        </tr>
                    </table>
                    <table class="table table-striped">
                        <tbody id="diagnosisTable">
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="tindakan">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th colspan="2">Input Tindakan</th>
                        </tr>

                        <tr>
                            <th>
                                <select name="diagnosa" id="selectTreatmentElement" class="form-control select2">
                                    @foreach($treatments as $index => $treatment)
                                        <option data-icd="{{ $treatment->treatmentCode }}" value="{{ $treatment->id }}">{{ $treatment->treatmentName }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <input type="button" name="" value="Tambah" id="submitTreatmentButton" class="btn btn-info">
                            </th>
                        </tr>
                    </table>
                    <table class="table table-striped">
                        <tbody id="treatmentTable">
                        </tbody>
                    </table>
                </div>

                <div role="tabpanel" class="tab-pane" id="terapi">
                <table class="table">
                    <tr>
                        <th>
                            Resep Dokter
                        </th>
                    </tr>
                    <tr>
                        <th>
                            <textarea name="receipt" style="min-height: 100px; width: 100%" rows="" cols=""></textarea>
                        </th>
                    </tr>
                </table>
                </div>

            </div>

            <div class="well">
                <input type="submit" name="submit" value="Selesai Pemeriksaan" id="submit" class="btn btn-success">            </div>
            </form>
    </x-dashboard-container>
@endsection

@push("scripts")
<script>
    class AttachManager {
        constructor(
            submitButton,
            tableElement,
            selectElement
        ) {
            this.submitButton = submitButton
            this.tableElement = tableElement
            this.selectElement = selectElement

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
        }
    }

    const diagnosisTable = document.getElementById("diagnosisTable");
    const submitDiagnosisButton = document.getElementById('submitDiagnosisButton');
    const selectDiagnosisElement = document.getElementById("selectDiagnosisElement");

    const diagnosisAttachManager = new AttachManager(submitDiagnosisButton, diagnosisTable, selectDiagnosisElement);

    const treatmentTable = document.getElementById("treatmentTable");
    const submitTreatmentButton = document.getElementById('submitTreatmentButton');
    const selectTreatmentElement = document.getElementById("selectTreatmentElement");

    const treatmentAttachManager = new AttachManager(submitTreatmentButton, treatmentTable, selectTreatmentElement);
</script>
@endpush
