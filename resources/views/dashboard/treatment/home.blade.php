@extends('layouts.dashboard')

@section('content')
<x-dashboard-container title="Data Master Tindakan">
                    <a href="{{ route('treatment.create') }}" class="btn btn-primary btn-md"><i class="glyphicon glyphicon-ok"></i> Tambah Data Master Tindakan</a>
                    <a href="{{ url('patient/print') }}" class="btn btn-primary btn-md" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Master Data Tindakan</a>
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
                                <th>Kode ICD</th>
                                <th>Nama Tindakan</th>
                                <th>Kelola</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($treatments as $index => $treatment)
                                <tr>
                                    <td>{{ $treatment->treatmentCode }}</td>
                                    <td>{{ $treatment->treatmentName }}</td>
                                    <td>
                                        <a href="{{ route("treatment.edit.view", $treatment->id) }}" class="btn btn-sm btn-info" title="Ubah Data"><i class="glyphicon glyphicon-pencil"></i></a>
                                    <form
                                        style="display: inline-block;"
                                        action="{{ route('treatment.destroy', $treatment->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </button>
                                    </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <ul class="pagination pagination-large pull-right"></ul>
                    </div>
</x-dashboard-container>
@endsection
