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
        body { padding: 70px 0; }
    </style>

    @stack('styles')
</head>
<body>

@include('partials/navbar')

{{-- CONTENT --}}
    @yield('content')

{{-- JS --}}
<script src="{{ asset('assets/js/jquery-1.8.3.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datepicker3.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugin/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/exporting.js') }}"></script>

<script>
    setTimeout(function () {
        $("#pesan").fadeOut(800).slideUp(800, function () {
            $(this).remove();
        });
    }, 2000);

    $(document).ready(function () {
        $('.select2').select2({ width: '100%' });

        $('.data-table').DataTable({ retrieve: true });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
            localStorage.setItem('lastTab', $(this).attr('href'));
        });

        let lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('[href="' + lastTab + '"]').tab('show');
        }
    });
</script>

@stack('scripts')
</body>
</html>
