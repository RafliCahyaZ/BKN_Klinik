@extends('layouts.dashboard')
@section('content')
    <x-dashboard-container title="Daftar Antrian Pasien">
        @if(session('failed'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>Gagal!</strong> {{ session('failed') }}
        </div>
        @endif
        <div class="unseen">
            <table class="table table-bordered table-hover table-condensed data-table order-table ">
                <thead>
                    <tr>
                        <th>No. Urut</th>
                        <th>Nama Pasien</th>
                        <th>Alamat</th>
                        <th>Umur</th>
                        <th>Waktu Registrasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($registrations as $index => $registration)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $registration->patient->name }}</td>
                            <td>{{ $registration->patient->address }}</td>
                            <td>{{ $registration->patient->dateOfBirth->age }} Tahun</td>
                            <td>{{ $registration->createdAt->format('d-m-Y H:i') }}</td>
                            <td>{{ $registration->cancelledAt ? 'Dibatalkan' : 'Aktif' }}</td>
                            <td>
                                @can('process-appointment')
                                    <a href="{{ route('patient.appointment.view', $registration->id) }}"
                                        class="btn btn-sm btn-success">
                                        <i class="glyphicon glyphicon-search"></i>
                                        Mulai Periksa
                                    </a>
                                @endcan
                                @can('cancel-appointment')
                                    @if (!$registration->cancelledAt)
                                        <form style="display: inline-block;"
                                            action="{{ route('registration.cancel', $registration->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin membatalkan antrian ini?')">
                                            @csrf
                                            @method('POST')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="glyphicon glyphicon-remove"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <ul class="pagination pagination-large pull-right"></ul>
        </div>
    </x-dashboard-container>
@endsection
