@extends('layouts.dashboard')

@section('content')
<x-dashboard-container title="Data Master Diagnosa">
                @can('create-diagnosis')
                    <a href="{{ route('diagnosis.create') }}" class="btn btn-primary btn-md"><i class="glyphicon glyphicon-ok"></i> Tambah Data Master Diagnosa</a>
                @endcan

                @can('print-diagnosis')
                    <a href="{{ route('diagnosis.print') }}" class="btn btn-primary btn-md" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Master Data Diagnosa</a>
                @endcan

                @canany(['create-diagnosis', 'print-diagnosis'])
                    <hr>
                @endcanany

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
                                <th>Nama Penyakit</th>
                                @canany(['edit-diagnosis', 'delete-diagnosis'])
                                    <th>Kelola</th>
                                @endcanany
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($diagnoses as $index => $diagnosis)
                                <tr>
                                    <td>{{ $diagnosis->icdCode }}</td>
                                    <td>{{ $diagnosis->diseaseName }}</td>
                                @canany(['edit-diagnosis', 'delete-diagnosis'])
                                    <td>
                                        @can('edit-diagnosis')
                                            <a href="{{ route("diagnosis.edit.view", $diagnosis->id) }}" class="btn btn-sm btn-info" title="Ubah Data"><i class="glyphicon glyphicon-pencil"></i></a>
                                        @endcan
                                        @can('delete-diagnosis')
                                        <form
                                            style="display: inline-block;"
                                            action="{{ route('diagnosis.destroy', $diagnosis->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                    @endcanany
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <ul class="pagination pagination-large pull-right"></ul>
                    </div>
</x-dashboard-container>
@endsection
