<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Students for Allocation {{ $allocation->id }}</title>
    <style>
        {!! $styles !!} body {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 14pt;
        }
    </style>
</head>

<body>
    <header>
        <div class="h-24 max-w">
            <img src="{{ $logos['logo1'] }}" class="w-24 h-auto" style="float:left">
            <div>
                <div>{{ config('eschool.name') }}</div>
            </div>

            <img src="{{ $logos['logo2'] }}" class="ml-auto w-24 h-auto" style="float:right">
        </div>
    </header>
    <table class="w-full text-sm">
        <thead>
            <tr>
                <th class="border px-3 py-2">Admission Number</th>
                <th class="border px-3 py-2">Name</th>
                <th class="border px-3 py-2">Gender</th>
                <th class="border px-3 py-2">Attendance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr class="odd:bg-gray-100">
                    <td class="px-2 py-1 border">{{ $student->admission_no }}</td>
                    <td class="px-2 py-1 border uppercase">{{ $student->name }}</td>
                    <td class="px-2 py-1 border">{{ $student->gender }}</td>
                    <td class="px-2 py-1 border"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
