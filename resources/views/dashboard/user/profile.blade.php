@extends('layouts.dashboard')

@section('content')
<x-dashboard-container title="Edit Profil">

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="well">
	  	<ul>
	  		<li>Silahkan ini data sesuai dengan yang anda inginkan.</li>
	  	</ul>
	  </div>

    <div>
        <form action="{{ route("user.profile.action") }}" method="post" accept-charset="utf-8">
            @csrf
            <table class="table table-striped">
                <tbody>
                    @error('password')
                        <tr>
                            <th class="text-danger" colspan="3">{{ $message }}</th>
                        </tr>
                    @enderror
                    <tr>
                        <th>Password Baru</th>
                        <th>:</th>
                        <th><input type="text" name="password" value="" id="password" class="form-control" required="required"></th>
                    </tr>
                    <tr>
                        <th>Konfirmasi Password</th>
                        <th>:</th>
                        <th><input type="text" name="password_confirmation" value="" id="passlagi" class="form-control" required="required"></th>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>
                            <input type="submit" name="submit" value="Update Data" id="submit" class="btn btn-success">
                            <a href="{{ url("/") }}" class="btn btn-danger">Batal</a>
                        </th>
                    </tr>
                </tbody>
            </table>
	  	</form>
    </div>
</x-dashboard-container>
@endsection
