@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Edit Data Master Obat">

        <div style="padding: 12px 0">
            <a href="{{ route('medicine.transaction.create.view', $medicine->id) }}" class="btn btn-primary btn-md"><i class="glyphicon glyphicon-ok"></i> Buat Transaksi</a>
        </div>

        <hr/>

        <form
            action="{{ route('medicine.edit.action', $medicine->id) }}"
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
							<th>Nama Obat</th>
							<th>:</th>
							<th><input type="text" name="name" value="{{old('name', $medicine->name)}}" class="form-control" id="nama" autofocus="autofocus" required="required">							</th>
						</tr>

                    @error('unit')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Unit</th>
                        <th>:</th>
                        <th>
                        <select name="unit">
                            @foreach($units as $index => $unit)
                                <option {{ ($medicine->unit->id == $unit->id) ? 'selected' : '' }} value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
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
                                href="{{ route("medicine.index") }}"
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
