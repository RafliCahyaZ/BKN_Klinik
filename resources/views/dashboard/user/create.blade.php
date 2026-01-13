@extends('layouts.dashboard')

@section('content')
    <x-dashboard-container title="Tambah Pengguna">
        <form
            action="{{ route('user.store') }}"
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
							<th><input type="email" name="email" value="{{ old("email") }}" class="form-control" id="nama" autofocus="autofocus" required="required">							</th>
						</tr>

                    @error('username')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                        <tr>
							<th>Username</th>
							<th>:</th>
							<th><input type="text" name="username" value="{{ old("username") }}" class="form-control" id="nama" autofocus="autofocus" required="required">							</th>
						</tr>

                    @error('password')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Password</th>
                        <th>:</th>
                        <th><input type="password" name="password" value="{{ old("password") }}" class="form-control" id="password" required="required">							</th>
                    </tr>

                    @error('confirm_password')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Konfirmasi Password</th>
                        <th>:</th>
                        <th><input type="password" name="confirm_password" value="" class="form-control" id="confirm_password" required="required">							</th>
                    </tr>

                    @error('fullname')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                        <tr>
							<th>Nama Asli</th>
							<th>:</th>
							<th><input type="text" name="fullname" value="{{ old("fullname") }}" class="form-control" id="fullname" autofocus="autofocus" required="required">							</th>
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
                            <select name="role" class="form-control" value="{{ old("role") }}">
                                <option value="administrator">Administrator</option>
                                <option value="operator">Petugas</option>
                                <option value="docktor">Dokter</option>
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
