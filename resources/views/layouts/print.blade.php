<!DOCTYPE html>
<html lang="id">
<head>
    <title>Sistem Informasi Praktik Dokter</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/web.png') }}">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/fancybox/jquery.fancybox-1.3.4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/easyautocomplete/easy-autocomplete.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/easyautocomplete/easy-autocomplete.themes.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/dataTables/datatables.min.css') }}">

    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; font-size: 12px; }
    </style>

    <style>
        @media print { .no-print { display:none !important; } }
    </style>

    <style>
        body { padding: 70px 0; }
    </style>

    @stack('styles')
</head>
<body onload="window.print()">

@include('partials/navbar')

{{-- CONTENT --}}
<div class="container">
    @yield('content')
</div>

{{-- FOOTER --}}
<div class="container">
    <hr>
    <p>&nbsp;</p>
</div>

{{-- JS --}}
<script src="{{ asset('assets/js/jquery-1.8.3.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datepicker3.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugin/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/exporting.js') }}"></script>


@stack('scripts')
</body>
</html>
