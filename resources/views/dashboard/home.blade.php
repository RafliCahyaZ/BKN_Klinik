@extends('layouts.dashboard')

@section('content')
<div class="container">
  <div class="row">
    <br>
    <div class="col-md-12">
      <div class="well">
        <div class="media">
          <a class="pull-left">
           <img src="<?php echo url('assets/img/md.png');?>" class="media-object" style="width:64px;" alt="" />
         </a>
         <div class="media-body">
          <h2 class="media-heading" align="center">Selamat datang, {{ auth()->user()->username }}</h2>
          <h2 align="center">Sistem Informasi</h2>
          <h2 align="center">KLINIK KANTOR</h2>
          <h4 align="center">KANREG I BKN YOGYAKARTA</h4>
        </div>
      </div>
    </div>
            <!-- <blockquote>
              <h2>
                <p align="center">Selamat datang, Farhannivta</p>
                <p align="center">SISTEM INFORMASI PRAKTIK DOKTER  </p>
                <p align="center">dr. Hj. Siti Sundari, Sp.M,. M.kes  </p>
              </h2>
            </blockquote>-->
          </div>
        </div>
      </div>
@endsection
