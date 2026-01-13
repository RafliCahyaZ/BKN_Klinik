@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Edit Data Master Diagnosa">
        <form
            action="{{ route('diagnosis.edit.action', $diagnosis->id) }}"
            method="post"
            accept-charset="utf-8"
            enctype="multipart/form-data"
        >
        @csrf
            <table class="table table-striped">
                <tbody>
                    @error('icd_code')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                        <tr>
							<th>Kode ICD</th>
							<th>:</th>
							<th><input type="text" name="icd_code" value="{{ old('icd_code', $diagnosis->icdCode) }}" class="form-control" id="nama" autofocus="autofocus" required="required">							</th>
						</tr>

                    @error('disease_name')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Nama Penyakit</th>
                        <th>:</th>
                        <th><input type="text" name="disease_name" value="{{ old('disease_name', $diagnosis->diseaseName) }}" class="form-control" id="nama" required="required">							</th>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <input
                                type="submit"
                                name="submit"
                                value="Simpan Data"
                                class="btn btn-success"
                            >
                            <a
                                href="{{ route("diagnosis.index") }}"
                                class="btn btn-danger"
                            >
                                Kembali
                            </a>
                        </th>
                    </tr>
                </tbody>
            </table>
        </form>
    </x-dashboard-container>
@endsection
