@extends('layouts.homedashboard')

@section('content')

<style>
  html, body {
    overflow: hidden;
    height: 100%;
  }

  .chart-container {
    height: calc(50vh - 100px);
    max-height: 45vh;
    min-height: 200px;
    overflow: hidden;
    position: relative;
  }

  .chart-container canvas {
    position: absolute;
    inset: 0;
    width: 100% !important;
    height: 100% !important;
  }
</style>

<div style="height:60vh; min-height:250px; position: relative; margin-top: -50px; padding-top: 50px;
  background-image:
    linear-gradient(to bottom, #3498db, #3498db 10%, rgba(30,144,255,0.15) 40%, rgba(30,144,255,0) 55%),
    linear-gradient(to top, rgba(255,255,255,0.95),  rgba(255,255,255,0.95) 10% , rgba(255,255,255,0) 35%),
    url('{{ url('assets/img/kantor.jpeg') }}');
  background-size: cover, cover, cover;
  background-position: center;
  background-repeat: no-repeat;">
  <div style="position:relative; z-index:1; height:100%; display:flex; align-items:center; justify-content:center">
    <div style="text-align:center; color:#fff;">
      <h2 style="margin:0; font-weight:600;">Selamat datang, {{ auth()->user()->username ?? '' }}</h2>
      <p style="margin:6px 0 0;">Sistem Informasi Klinik Kantor</p>
    </div>
  </div>
</div>

<!-- White info area (separate, overlaps banner) -->
<div style="background:#fff; border-top-left-radius:8px; border-top-right-radius:8px; margin-top:-80px; padding:30px 15px; min-height:50vh; box-shadow:0 -2px 8px rgba(0,0,0,0.05); position:relative; z-index:2;">
  <div class="container">
    <div class="row">
      <!-- patients chart: grouped bars per interval -->
      <div class="col-md-6">
        <h5>Perbandingan Pasien Hari Ini: Sudah Diperiksa vs Belum Diperiksa</h5>
        <div class="chart-container">
          <canvas id="patientsStatusChart"></canvas>
        </div>
      </div>
      <div class="col-md-6">
        <h5>Stok Obat per Jenis</h5>
        <div class="chart-container">
          <canvas id="medicineStockChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js CDN + script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  let patientsChart = null;
  let medicineChart = null;

  async function fetchPatientsStatus() {
    const res = await fetch('{{ url("/dashboard/api/patients-status") }}');
    const data = await res.json();

    // force two labels (safe if API already returns them)
    const labels = ['Sudah Diperiksa', 'Belum Diperiksa'];
    const values = [data.examined ?? 0, data.not_examined ?? 0];

    if (patientsChart) {
      patientsChart.data.labels = labels;
      patientsChart.data.datasets[0].data = values;
      patientsChart.update();
      return;
    }

    const ctx = document.getElementById('patientsStatusChart').getContext('2d');
    patientsChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Jumlah Pasien',
          data: values,
          backgroundColor: ['#2ecc71', '#e74c3c']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, precision: 0 } },
        plugins: { legend: { display: false } }
      }
    });
  }

  async function fetchMedicineStock() {
    const res = await fetch('{{ url("/dashboard/api/medicine-stock") }}');
    const data = await res.json();
    const labels = data.map(i => i.name);
    const values = data.map(i => i.stock);

    if (medicineChart) {
      medicineChart.data.labels = labels;
      medicineChart.data.datasets[0].data = values;
      medicineChart.update();
      return;
    }

    const ctx = document.getElementById('medicineStockChart').getContext('2d');
    medicineChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Stok',
          data: values,
          backgroundColor: '#3498db'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, precision:0 } }
      }
    });
  }

  async function refreshAll() {
    await Promise.all([fetchPatientsStatus(), fetchMedicineStock()]);
  }

  
  refreshAll();
  setInterval(refreshAll, 20000);
</script>

@endsection
