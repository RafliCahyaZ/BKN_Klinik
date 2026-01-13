@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Edit Data Master Tindakan">
        <form
            action="{{ route('treatment.edit.action', $treatment->id) }}"
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
							<th><input type="text" name="icd_code" value="{{ $treatment->treatmentCode }}" class="form-control" id="nama" autofocus="autofocus" required="required">							</th>
						</tr>

                    @error('treatment_name')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Nama Tindakan</th>
                        <th>:</th>
                        <th><input type="text" name="treatment_name" value="{{ $treatment->treatmentName}}" class="form-control" id="nama" required="required">							</th>
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
                                href="{{ route("treatment.index") }}"
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
