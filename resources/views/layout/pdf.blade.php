<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <style type="text/css">
        {!! file_get_contents(public_path(config('admin.path_prefix') . '/vendor/admin/css/font-awesome-4-base64.css')) !!} {!! file_get_contents(public_path(config('admin.path_prefix') . '/vendor/admin/material-design-icons/material-icons.css')) !!} {!! file_get_contents(public_path(config('admin.path_prefix') . '/vendor/admin/iconmoon/linea-icon.css')) !!} {!! file_get_contents(public_path(config('admin.path_prefix') . '/vendor/admin/material-dashboard-master/assets/css/print.css')) !!}
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

<body>


    <section class="page">
        @include('eschool::layout.pdf-header')
        <hr class="my-0">
        @yield('content')
    </section>
    @yield('scripts_bottom')
</body>

</html>
