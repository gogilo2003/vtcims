<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <style>
        {!! file_get_contents(public_path('/css/pdf.css')) !!}
    </style>
    @yield('styles')
    @stack('styles')
    <style type="text/css">
        thead {
            display: table-header-group
        }

        tfoot {
            display: table-row-group
        }

        tr {
            page-break-inside: avoid
        }

        img.logo {
            height: 180px
        }
    </style>
</head>

<body class="font-sans">
    <section class="page">
        @include('layout.pdf-header')
        <hr class="my-0">
        @yield('content')
    </section>
    @yield('scripts_bottom')
</body>

</html>
