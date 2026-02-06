<!DOCTYPE html>
<html>

<head>
    <title>Sistem Informasi Praktik Dokter</title>
    <link rel="shortcut icon" href="<?= url('assets/img/web.png') ?>">
    <link rel="stylesheet" type="text/css" href="<?= url('assets/css/bootstrap.min.css') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        html,
        body {
            overflow: hidden;
            height: 100%;
        }

        .login-wrapper {
            height: 100%;
            display: flex;
            border: none
        }

        .login-left {
            width: 60%;
            display: flex;
            align-items: center;
            justify-content: flex-start; /* geser ke kiri */
            padding-left: 80px;          /* jarak dari sisi kiri */
            background-color: #ffffff;
            height: 100vh;
            border-radius: 8px;
        }

        .login-card {
            width: 100%;
            max-width: 550px;
            padding: 30px;
            background: #ffffff;
        }

        .login-right {
            width: 40%;
            background: #dbecff;
            border-radius: 8px;
            margin : 8px 8px 8px 0px;
            display:flex;
            align-items: center;
            justify-content: center;
            overflow: hidden
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }

            .login-left,
            .login-right {
                width: 100%;
            }

            .login-right {
                height: 200px;
            }
        }
    </style>
</head>

<body>
<div class="login-wrapper">

    <div class="login-left">
        <div class="login-card">

            <h3 class="text-left" style="font-weight:bolder;">
                <i></i><br>
                Login BKN Clinic

            </h3>

            <p style="color:rgb(152, 152, 152)">
                <i class="glyphicon glyphicon-info-sign"></i> Jika <span style="font-weight:bold">belum memiliki akun</span>, silahkan menghubungi <span style="font-weight:bold">Admin</span>.</p>
            <hr>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('failed'))
                <div class="alert alert-danger">
                    {{ session('failed') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <label>Username</label>
                <input type="text" name="username" class="form-control input-lg" placeholder="Masukkan Username"
                    autofocus>
                <br>

                <label>Password</label>
                <input type="password" name="password" class="form-control input-lg" placeholder="Masukkan Password"
                    minlength="15" maxlength="64">
                <br>

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Login
                </button>
                <br>

            </form>
        </div>
    </div>

    <div class="login-right">
        <img src="{{ url("/assets/img/login.png") }}" alt="" style="width:85%;">
    </div>


</div>


</body>

</html>
