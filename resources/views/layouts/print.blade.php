<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ $printTitle ?? 'Sistem Informasi Praktik Dokter' }}</title>


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
    @media print {

        .container{
            width: 100%;
            max-width: 100%;

        }

        body {
            font-family: "Source Code Pro", monospace;
            font-weight: lighter;
            font-size: 12px;
        }


        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
        }


        th, td {
            border: 1px dashed #000;
            padding: 6px;
            font-size: 11px;
            vertical-align: top;

            white-space: normal;
            word-break: break-word;
        }


        td:nth-child(7),
        th:nth-child(7) {
            width: 18%;
            font-size: 10px;
            word-break: break-all;
        }


        td:nth-child(9),
        th:nth-child(9) {
            width: 12%;
            word-break: break-all;
            text-align: center;
        }

        td:nth-child(5),
        th:nth-child(5) {
            width: 6%;
            word-break: break-all;
            text-align: center;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: semi-bold;
            font-family: "Source Code Pro", monospace;
        }


        .no-print,
        nav,
        .navbar,
        .btn {
            display: none !important;
        }

        @page {
            size: A4 portrait;
            margin: 15mm;
        }

    }
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
