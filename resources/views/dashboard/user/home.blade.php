@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Data Pengguna">
        <a href="{{ route('user.create') }}" class="btn btn-warning"><i class="glyphicon glyphicon-plus"></i> Pengguna Baru</a>
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
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama Asli</th>
                        <th>Level</th>
                        <th>Status Akun</th>
                        <th>Kelola</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1}}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                @if ($user->isActive)
                                    <span class="label label-success">Aktif</span>
                                @else
                                    <span class="label label-danger">Non-Aktif</span>
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if (!($user->isActive && $user->role === \App\Type\UserRole::ADMIN && auth()->user()->role === \App\Type\UserRole::ADMIN->value))
                                    <form style="display: inline-block;" action="{{ route('user.activate', $user->id) }}"
                                        method="POST" onsubmit="return confirm('Yakin ingin {{ $user->isActive ? 'menonaktifkan' : 'mengaktifkan' }} akun ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn {{ $user->isActive ? 'btn-danger' : 'btn-success' }} btn-sm" aria-label="{{ $user->isActive ? 'Nonaktifkan akun' : 'Aktifkan akun' }}"
                                            title="{{ $user->isActive ? 'Nonaktifkan akun' : 'Aktifkan akun' }}">
                                            <i class="fa {{ $user->isActive ? 'fa-unlock-alt' : 'fa-lock' }}"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('user.edit.view', $user->id) }}" class="btn btn-sm btn-warning"><i
                                        class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                                <form style="display: inline-block;" action="{{ route('user.destroy', $user->id) }}"
                                    method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
