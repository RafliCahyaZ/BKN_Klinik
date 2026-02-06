@extends('layouts.homedashboard')

@section('content')
    <style>
        html,
        body {
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

        .glow-text {
            color: white;
            text-shadow: 0 0 5px #7fccff;
        }

        .username-link {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.25s ease, text-shadow 0.25s ease;
            cursor: pointer;
        }

        .username-link:hover {
            color: #1f3c88;
            /* biru lebih gelap */
            text-shadow: 0 0 6px rgba(0, 0, 0, 0.4);
        }
    </style>

    <div
        style="height:60vh; min-height:250px; position: relative; margin-top: -50px; padding-top: 50px;
    background-image:
      linear-gradient(to bottom, #3498db, #3498db 10%, rgba(30,144,255,0.15) 40%, rgba(30,144,255,0) 55%),
      linear-gradient(to top, rgba(255,255,255,0.95), rgba(255,255,255,0.95) 10%, rgba(255,255,255,0) 35%),
      url('{{ url('assets/img/kantor.webp') }}');
    background-size: cover, cover, cover;
    background-position: center;
    background-repeat: no-repeat;">
        <div style="position:relative; z-index:1; height:100%; display:flex; align-items:center; justify-content:center">
            <div style="text-align:center; color:#fff;">
                <h2 style="margin:0; font-weight:600; font-size:40px;" class="glow-text">
                    Selamat Datang, <a href="{{ url('/user/profile') }}"
                        class="username-link">{{ auth()->user()->username ?? '' }}</a>
                </h2>
                <p class="glow-text" style="margin:6px 0 0; font-size:20px;">Sistem Informasi Klinik Kantor</p>
            </div>
        </div>
    </div>

    <div
        style="background:#fff; border-top-left-radius:8px; border-top-right-radius:8px;
    margin-top:-80px; padding:30px 15px; min-height:50vh;
    box-shadow:0 -2px 8px rgba(0,0,0,0.05); position:relative; z-index:2;">
        <div class="container">
            <div class="row">

                <div class="col-md-6">
                    <h5 style="text-align:center">Pasien Hari Ini</h5>

                    <div style="display:flex; gap:16px;">
                        <div style="flex:1; background:#f7fcff; border-radius:8px; padding:24px; text-align:center;">
                            <div id="patients-checked" style="font-size:56px; font-weight:700; color:#3498db;">0</div>
                            <div style="color:#666;">Sudah Diperiksa</div>
                        </div>

                        <div style="flex:1; background:#fff7f7; border-radius:8px; padding:24px; text-align:center;">
                            <div id="patients-unchecked" style="font-size:56px; font-weight:700; color:#e74c3c;">0</div>
                            <div style="color:#666;">Belum Diperiksa</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 style="text-align:center">Pasien per Minggu</h5>
                    <div class="chart-container">
                        <canvas id="weeklyPatientsChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let weeklyChart = null;


        async function fetchPatientsStatus() {
            try {
                const res = await fetch('{{ url('/dashboard/api/patients-status') }}');
                const data = await res.json();

                document.getElementById('patients-checked').innerText = data.checked ?? 0;
                document.getElementById('patients-unchecked').innerText = data.unchecked ?? 0;
            } catch (e) {
                console.error(e);
            }
        }


        async function fetchWeeklyPatients() {
            const res = await fetch('{{ url('/dashboard/api/weekly-patients') }}');
            const data = await res.json();

            const labels = data.map(d => d.label);
            const registrations = data.map(d => d.registrations);
            const checked = data.map(d => d.checked);

            if (weeklyChart) {
                weeklyChart.data.labels = labels;
                weeklyChart.data.datasets[0].data = registrations;
                weeklyChart.data.datasets[1].data = checked;
                weeklyChart.update();
                return;
            }

            const ctx = document.getElementById('weeklyPatientsChart').getContext('2d');

            weeklyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Registrasi',
                            data: registrations,
                            backgroundColor: '#ffda43'
                        },
                        {
                            label: 'Sudah Diperiksa',
                            data: checked,
                            backgroundColor: '#3498db'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 20,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        }


        async function refreshAll() {
            await Promise.all([fetchPatientsStatus(), fetchWeeklyPatients()]);
        }

        refreshAll();
        setInterval(refreshAll, 20000);
    </script>
@endsection
