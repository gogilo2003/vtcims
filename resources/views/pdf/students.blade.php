<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Students for Allocation {{ $allocation->id }}</title>
    <style>
        {!! $styles !!}
    </style>
</head>

<body>
    <header>
        <div class="h-24 max-w">
            <img src="{{ $logos['logo1'] }}" class="w-24 h-auto" style="float:left">

            <img src="{{ $logos['logo2'] }}" class="ml-auto w-24 h-auto" style="float:right">
        </div>
    </header>
    <h1>Allocation ID: {{ $allocation->id }}</h1>
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
                    <td class="px-2 py-1 border">{{ $student->name }}</td>
                    <td class="px-2 py-1 border">{{ $student->gender }}</td>
                    <td class="px-2 py-1 border"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
