@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Edit Pasien">
        <form
            action="{{ route('patient.edit.action', $patient->id) }}"
            method="post"
            accept-charset="utf-8"
            enctype="multipart/form-data"
        >
        @csrf
            <table class="table table-striped">
                <tbody>
                    @error('name')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Nama Pasien</th>
                        <th>:</th>
                        <th>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $patient->name) }}"
                                class="form-control"
                                id="name"
                                autofocus
                                required
                            >
                        </th>
                    </tr>

                    @error('date-of-birth')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Tanggal Lahir</th>
                        <th>:</th>
                        <th>
                            <input
                                type="date"
                                name="date-of-birth"
                                value="{{ old('date_of_birth', $patient->dateOfBirth->format('Y-m-d')) }}"
                                class="form-control"
                                id="date-of-birth"
                                required
                            >
                        </th>
                    </tr>

                    @error('gender')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Jenis Kelamin</th>
                        <th>:</th>
                        <td>
                            <select name="gender" class="form-control" value={{ old('gender', $patient->gender) }}>
                                <option value="M">Laki-laki</option>
                                <option value="F">Perempuan</option>
                            </select>
                        </td>
                    </tr>

                    @error('address')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Alamat</th>
                        <th>:</th>
                        <th>
                            <textarea
                                name="address"
                                value=""
                                rows="5"
                                class="form-control"
                                id="address"
                                required
                            >{{ old('address', $patient->address) }}</textarea>
                        </th>
                    </tr>

                    @error('history')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Riwayat Sakit</th>
                        <th>:</th>
                        <th>
                            <textarea
                                name="history"
                                rows="5"
                                value="{{ old('history', $patient->history) }}"
                                class="form-control"
                                id="history"
                            >{{ old('history', $patient->history) }}</textarea>
                        </th>
                    </tr>

                    @error('phone-number')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Telp</th>
                        <th>:</th>
                        <th>
                            <input
                                type="number"
                                name="phone-number"
                                value="{{ old('phone-number', $patient->telpNumber) }}"
                                class="form-control"
                                id="phone-number"
                                required
                            >
                        </th>
                    </tr>

                    @error('medical-record')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Upload Rekamedis</th>
                        <th>:</th>
                        <th>
                            <input
                                type="file"
                                class="filestyle"
                                name="medical-record"
                                id="filestyle-0"
                                tabindex="-1"
                                style="position: absolute; clip: rect(0, 0, 0, 0);"
                            >

                            <div class="bootstrap-filestyle input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    disabled
                                >
                                <span class="group-span-filestyle input-group-btn" tabindex="0">
                                    <label for="filestyle-0" class="btn btn-default">
                                        <span class="icon-span-filestyle glyphicon glyphicon-folder-open"></span>
                                        <span class="buttonText">Pilih file</span>
                                    </label>
                                </span>
                            </div>
                        </th>
                    </tr>

                    <tr>
                    <th></th>
                    <th></th>
                    <th>
                        <div class="bg-primary" style="display: flex; gap: 12px; padding: 12px 16px;">
                            <div>
                            <i class="fa fa-file"></i>
                            </div>
                            <div>Preview Rekam Medis</div>
                        </div>
                        <div style="max-height: 100%; max-height: 600px; display: block; overflow-y: hidden;display:flex;justify-content: center;align-items: center;">
                            <img id="preview-image" style="width: 100%;" src="{{ $patient->medicalRecordFileUrl }}" alt="Belum upload rekam medis" />
                        </div>
                    </th>
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
                                href="{{ route("patient.index") }}"
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

@push("scripts")
<script>
    document.getElementById('filestyle-0').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        // validasi sederhana
        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar');
            e.target.value = '';
            return;
        }

        const preview = document.getElementById('preview-image');

        // cara modern & ringan
        preview.src = URL.createObjectURL(file);

        // optional: bersihkan memory
        preview.onload = () => {
            URL.revokeObjectURL(preview.src);
        };
    });
</script>
@endpush
