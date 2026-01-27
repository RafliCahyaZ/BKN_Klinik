@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Edit Pengguna">
        <form
            action="{{ route('user.edit.action', $user->id) }}"
            method="post"
            accept-charset="utf-8"
            enctype="multipart/form-data"
        >
        @csrf
            <table class="table table-striped">
                <tbody>
                    @error('email')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                        <tr>
							<th>Email</th>
							<th>:</th>
							<th><input disabled type="email" name="email" value="{{ old("email", $user->email) }}" class="form-control" id="nama" autofocus="autofocus" required="required">							</th>
						</tr>

                    @error('username')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                        <tr>
							<th>Username</th>
							<th>:</th>
							<th><input type="text" disabled name="username" value="{{ old("username", $user->username) }}" class="form-control" id="nama" autofocus="autofocus" required="required">							</th>
						</tr>

                    @error('fullname')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                        <tr>
							<th>Nama Asli</th>
							<th>:</th>
							<th><input type="text" name="fullname" value="{{ old("fullname", $user->fullname) }}" class="form-control" id="fullname" autofocus="autofocus" required="required">							</th>
						</tr>

                    @error('role')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Role</th>
                        <th>:</th>
                        <td>
                            <select name="role" class="form-control">
                                <option
                                    {{ ($user->role == \App\Type\UserRole::ADMIN) ? 'selected' : '' }}
                                    value="{{ \App\Type\UserRole::ADMIN }}">
                                    Administrator
                                </option>
                                <option
                                    {{ ($user->role == \App\Type\UserRole::OFFICER) ? 'selected' : '' }}
                                    value="{{ \App\Type\UserRole::OFFICER }}">
                                    Petugas
                                </option>
                                <option
                                    {{ ($user->role == \App\Type\UserRole::DOCTOR) ? 'selected' : '' }}
                                    value="{{ \App\Type\UserRole::DOCTOR }}">
                                    Dokter
                                </option>
                                <option
                                    {{ ($user->role == \App\Type\UserRole::INVENTORY_OFFICER) ? 'selected' : '' }}
                                    value="{{ \App\Type\UserRole::INVENTORY_OFFICER }}">
                                    Petugas Gudang
                                </option>
                            </select>
                        </td>
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
                                href="{{ route("user.index") }}"
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
