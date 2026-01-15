@php
    $user = auth()->user();
    $role = $user->role ?? "admin";
@endphp

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">
                <i class="glyphicon glyphicon-plus"></i> SI Praktik Dokter
            </a>
        </div>

        <ul class="nav navbar-nav">
            <li class="active"> <a href="{{ url("/") }}">Home</a></li>
            @can('is-officer-or-admin')
                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Master Data <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li> <a href="{{ url("patient") }}">Data Pasien</a></li>
                    <li> <a href="{{ url("diagnosis") }}">Data Diagnosa</a></li>
                    <li> <a href="{{ url("treatment") }}">Data Tindakan</a></li>
                </ul>
                </li>
            @endcan
            @can('is-doctor-or-admin')
                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dokter <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li> <a href="{{ url("patient/queue") }}">Antrian Pasien</a></li>
                    <li> <a href="{{ url("patient/history") }}">History Pasien</a></li>
                </ul>
                </li>
            @endcan
            @can('is-admin')
                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pengguna <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li> <a href="{{ url("user") }}">Data Pengguna</a></li>
                </ul>
                </li>
            @endcan
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Laporan <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li> <a href="http://localhost:8000/laporan">Laporan Pasien Per Periode</a></li>
                <li> <a href="http://localhost:8000/pasien/cetak" target="_blank">Laporan Pasien Keseluruhan</a></li>
                <li> <a href="http://localhost:8000/terapi/history">Laporan History Pemeriksaan Pasien</a></li>

              </ul>
            </li>
                  </ul>

        <ul class="nav navbar-nav navbar-right">
            <li>
                <a href="#">
                    Hai, {{ auth()->user()->fullname }} ({{ auth()->user()->role }})
                </a>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pengaturan</a>
                <ul class="dropdown-menu">
                    <li><a href="/">Profil</a></li>
                    <li>
                        <form action="{{ route("auth.logout") }}" method="POST">
                            @csrf
                            <button class="btn btn-link">Keluar</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
