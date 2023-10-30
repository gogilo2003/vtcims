<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @auth('admin')
                    <a href="{{ url('/admin/eschool') }}">Home</a>
                @else
                    <a href="{{ url('admin/login') }}">Login</a>
                @endauth
            </div>
        @endif

        <div class="content">
            <img src="{{ asset('logo.png') }}" alt="">
            <div class="title m-b-md">
                {{ config('app.name') }}
            </div>

            <div class="links">
                @if (Route::has('login'))
                    @auth('admin')
                        <a href="{{ url('/admin/eschool') }}">Home</a>
                    @else
                        <a href="{{ url('admin/login') }}">Login</a>
                    @endauth
                @endif
                <a href="{{ url('/') }}">Our website</a>
                <a href="{{ url('/webmail') }}">Staff Email</a>
                <a href="https://sch.micartech.co.ke">Support</a>
            </div>
        </div>
    </div>
</body>

</html>
