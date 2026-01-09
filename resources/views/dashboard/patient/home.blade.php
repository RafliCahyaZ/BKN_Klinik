@extends('layouts.dashboard')

@section('content')
<x-dashboard-container title="Data Pasien">
                    <a href="{{ url('patient/create') }}" class="btn btn-primary btn-md"><i class="glyphicon glyphicon-ok"></i> Tambah Pasien</a>
                    <a href="{{ url('patient/print') }}" class="btn btn-primary btn-md" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Data Pasien</a>
                    <hr>

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


                    <div class="unseen">
                        <table class="table table-bordered table-hover table-condensed data-table order-table ">
                            <thead>
                                <tr>
                                <th>No</th>
                                <th>No. Rekam</th>
                                <th>Nama Pasien</th>
                                <th>JK</th>
                                <th>Umur</th>
                                <th>Riwayat Penyakit</th>
                                <th>Berkas RM</th>
                                <th>Alamat</th>
                                <th>Telp</th>
                                <th>Tgl. Register</th>
                                <th>Kelola</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($patients as $index => $patient)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $patient->medicalRecordNumber }}</td>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->gender }}</td>
                                <td>{{ $patient->age }}</td>
                                <td>{{ $patient->history }}</td>
                                <td>{{ $patient->medicalRecordFileUrl }}</td>
                                <td>{{ $patient->address }}</td>
                                <td>{{ $patient->telpNumber }}</td>
                                <td>{{ $patient->createdAt->format('Y-m-d'); }}</td>
                                <td>
<a href="http://localhost:8000/pasien/ubah/000046" class="btn btn-sm btn-info" title="Ubah Pasien"><i class="glyphicon glyphicon-pencil"></i></a>
<a href="http://localhost:8000/pasien/hapus/000046" class="btn btn-sm btn-danger" title="Hapus Pasien" onclick="return confirm('Yakin mau hapus data ini?')"><i class="glyphicon glyphicon-trash"></i></a>
<a href="http://localhost:8000/registrasi/daftar/000046" class="btn btn-sm btn-success" title="Registrasi Pasien"><i class="glyphicon glyphicon-download-alt"></i></a>
                                </td>

                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <ul class="pagination pagination-large pull-right"></ul>
                    </div>
</x-dashboard-container>
@endsection
