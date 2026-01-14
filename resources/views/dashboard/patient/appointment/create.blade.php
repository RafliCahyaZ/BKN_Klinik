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
                                <select name="diagnosa" id="formdiagnosa" class="form-control select2">
                                    @foreach($diagnoses as $index => $diagnosis)
                                        <option value="{{ $diagnosis->id }}">{{ $diagnosis->diseaseName }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <input type="button" name="" value="Tambah" id="simpandiagnosa" class="btn btn-info">
                            </th>
                        </tr>
                    </table>
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>Z99.3 - Ketergantungan pada kursi roda</td>
                            <td>
                                <a href="#" onclick="hapus('11','000044','10467','2026-01-14')" title="Hapus Data Diagnosa" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i></a>
                            </td>
                        </tr>
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
                                <select name="diagnosa" id="formdiagnosa" class="form-control select2">
                                    @foreach($treatments as $index => $treatment)
                                        <option value="{{ $treatment->id }}">{{ $treatment->treatmentName }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <input type="button" name="" value="Tambah" id="simpandiagnosa" class="btn btn-info">
                            </th>
                        </tr>
                    </table>
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>Z99.3 - Ketergantungan pada kursi roda</td>
                            <td>
                                <a href="#" onclick="hapus('11','000044','10467','2026-01-14')" title="Hapus Data Diagnosa" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i></a>
                            </td>
                        </tr>
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
