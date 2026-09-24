@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> <b>Periode Laporan Transaksi
                            Obat</b></h3>
                </div>
                <div class="panel-body">
                    <form action="{{ route('medicine.transaction.report.print') }}" method="get" accept-charset="utf-8"
                        name="adminForm" id="adminForm" class="form-horizontal">
                        <div class="form-group">
                            @csrf
                            <label for="inputkode" class="col-md-2 control-label">Periode Laporan Transaksi Obat</label><br>
                            <div class="col-lg-4">
                                <br>
                                <b><i>Periode Dari :</i></b>
                                <div class="input-group input-group-sm">
                                    <div class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
                                        <input class="form-control" type="text" name="start-date" placeholder="Dari"
                                            required="">
                                    </div>

                                </div>
                                <b><i>Periode Sampai :</i></b>
                                <div class="input-group input-group-sm">
                                    <div class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
                                        <input class="form-control" type="text" name="end-date" placeholder="Sampai"
                                            required>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputkode" class="col-md-2 control-label">Bentuk Laporan</label>
                            <div class="col-lg-4">
                                <select name="display" class="form-control">
                                    <option value="web">Print Preview</option>
                                    <option value="print">Cetak</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="glyphicon glyphicon-retweet"></i> Proses Laporan</button>
                                <button type="reset" name="reset" class="btn btn-danger"><i
                                        class="glyphicon glyphicon-retweet"></i> Ulangi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script text="text/javascript">
        $(".input-group.date").datepicker({
            autoclose: true,
            todayHighlight: true
        });
    </script>
@endpush
