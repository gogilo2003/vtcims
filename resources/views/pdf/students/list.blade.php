@extends('layout.pdf')

@section('title')
    Students' Register
@endsection

@section('content')
    <table class="w-full text-sm">
        <thead class="bg-gray-200 font-medium">
            <tr>
                <th class="px-3 py-2 text-left">Admission No</th>
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Gender</th>
                <th class="px-3 py-2 text-left">Age</th>
                <th class="px-3 py-2 text-left">Class</th>
                <th class="px-3 py-2 text-left">Course</th>
                <th class="px-3 py-2 text-left">Sponsor</th>
                <th class="px-3 py-2 text-left">Program</th>
            </tr>
        </thead>
        <tbody class="font-normal">
            @foreach ($students as $student)
                <tr class="even:bg-gray-100">
                    <td class="px-3 py-2">{{ $student->admission_no }}</td>
                    <td class="px-3 py-2">
                        {{ $student->first_name }}{{ $student->middle_name ? ' ' . $student->middle_name : '' }}
                        {{ $student->surname }}
                    </td>
                    <td class="px-3 py-2">{{ $student->gender ? 'Female' : 'Male' }}</td>
                    <td class="px-3 py-2">{{ $student->age }}</td>
                    <td class="px-3 py-2">{{ $student->intake->name }}</td>
                    <td class="px-3 py-2">{{ $student->intake->course }}</td>
                    <td class="px-3 py-2">{{ $student->sponsor->name }}</td>
                    <td class="px-3 py-2">{{ $student->program->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
