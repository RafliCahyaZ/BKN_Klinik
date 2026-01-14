@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Registrasi Pasien">
        <form
            action="{{ route('registration.store', $patient->id) }}"
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
                                class="form-control"
                                value="{{ $patient->name }}"
                                id="name"
                                required
                                disabled
                            >
                        </th>
                    </tr>

                    @error('age')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Umur</th>
                        <th>:</th>
                        <th>
                            <input
                                type="number"
                                name="age"
                                class="form-control"
                                value="{{ $patient->dateOfBirth->age }}"
                                id="age"
                                required
                                disabled
                            >
                        </th>
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
                            <input
                                type="text"
                                name="address"
                                class="form-control"
                                value="{{ $patient->address }}"
                                id="name"
                                disabled
                            >
                        </th>
                    </tr>


                    @error('complaint')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Keluhan</th>
                        <th>:</th>
                        <th>
                            <textarea
                                name="complaint"
                                rows="5"
                                class="form-control"
                                id="complaint"
                                required
                            ></textarea>
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
