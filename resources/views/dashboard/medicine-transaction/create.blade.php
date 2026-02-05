@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Form Transaksi">
        <form
            action="{{ route('medicine.transaction.store') }}"
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
                        <th>Obat<span class="text-danger">*</span></th>
                        <th>:</th>
                        <th>
                        <select id="medicineIdInput" name="medicineId">
                            @foreach($medicines as $index => $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                            @endforeach
                        </select>
                        </th>
                    </tr>
                    @error('transasctionType')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Tipe<span class="text-danger">*</span></th>
                        <th>:</th>
                        <th>
                        <select id="transactionTypeInput" name="transactionType">
                            @foreach($transactionTypes as $index => $transactionType)
                                @if ($transactionType->value !== 'out')
                                <option value="{{ $transactionType }}">{{ $transactionType }}</option>
                                @endif
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

                    @error('notes')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr id="notesInputRow">
                        <th>Notes<span class="text-danger">*</span></th>
                        <th>:</th>
                        <th><textarea name="notes" class="form-control"></textarea></th>
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

@push('scripts')
<script>
    const transactionTypeInput = document.getElementById("transactionTypeInput");
    const notesInputRow = document.getElementById("notesInputRow");

    if(transactionTypeInput.value === "adjustment") {
        notesInputRow.style.display = "";
    } else {
        notesInputRow.style.display = "none";
    }

    transactionTypeInput.addEventListener("change", function () {
        if(this.value === "adjustment") {
            notesInputRow.style.display = "";
        } else {
            notesInputRow.style.display = "none";
        }
    });
</script>
@endpush
