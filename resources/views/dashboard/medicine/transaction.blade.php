@extends('layouts.dashboard')

@section('content')

    <x-dashboard-container title="Tambah Transaksi Obat">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th width="10%">Nama Obat</th>
                    <th width="5%">:</th>
                    <th width="55%">{{ $medicine->name }}</th>
                </tr>
                <tr>
                    <th>Stok saat ini</th>
                    <th>:</th>
                    <th>{{ $medicine->stock }} {{ $medicine->unit->name }}</th>
                </tr>
            </tbody>
        </table>
    </x-dashboard-container>

    <x-dashboard-container title="Form Transaksi">
        <form
            action="{{ route('medicine.transaction.create.action', $medicine->id) }}"
            method="post"
            accept-charset="utf-8"
            enctype="multipart/form-data"
        >
        @csrf
            <table class="table table-striped">
                <tbody>
                    @error('transasctionType')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Tipe<span class="text-danger">*</span></th>
                        <th>:</th>
                        <th>
                        <select name="transactionType">
                            @foreach($transactionTypes as $index => $transactionType)
                                <option value="{{ $transactionType }}">{{ $transactionType }}</option>
                            @endforeach
                        </select>
                        </th>
                    </tr>

                    @error('quantity')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Kuantitas<span class="text-danger">*</span></th>
                        <th>:</th>
                        <th><input type="number" name="quantity" value="1" class="form-control" id="quantity" required="required"></th>
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
                                href="{{ route("medicine.edit.view", $medicine->id) }}"
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
