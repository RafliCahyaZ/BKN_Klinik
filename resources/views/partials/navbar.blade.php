@php
    $user = auth()->user();
    $role = $user->role ?? "admin";
@endphp

<nav class="navbar navbar-expand-lg navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">
                <i class="glyphicon glyphicon-plus"></i> SI Praktik Dokter
            </a>
        </div>

        <ul class="nav navbar-nav">
            <li class="active"> <a href="{{ url("/") }}">Home</a></li>

            @canany(['get-all-patient', 'get-all-diagnosis', 'get-all-treatment', 'get-all-medicine'])
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Master Data <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        @can('get-all-patient')
                            <li> <a href="{{ url("patient") }}">Data Pasien</a></li>
                        @endcan

                        @can('get-all-diagnosis')
                            <li> <a href="{{ url("diagnosis") }}">Data Diagnosa</a></li>
                        @endcan

                        @can('get-all-treatment')
                            <li> <a href="{{ url("treatment") }}">Data Tindakan</a></li>
                        @endcan

                        @can('get-all-medicine')
                            <li> <a href="{{ route("medicine.index") }}">Data Obat</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['get-all-appointment', 'get-history-appointment'])
                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dokter <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    @can('get-all-appointment')
                        <li> <a href="{{ url("patient/queue") }}">Antrian Pasien</a></li>
                    @endcan
                    @can('get-history-appointment')
                        <li> <a href="{{ url("patient/history") }}">History Pasien</a></li>
                    @endcan
                </ul>
                </li>
            @endcanany

            @can('get-all-user')
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
                <li> <a href="{{ route("report.index") }}">Laporan Pasien Per Periode</a></li>
                <li> <a href="{{ route("patient.history") }}">Laporan History Pemeriksaan Pasien</a></li>

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
                    <li><a href="{{ route('user.profile.view') }}">Profil</a></li>
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
