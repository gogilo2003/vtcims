@extends('layout.pdf')

@section('content')
    @if (isset($title))
        <div class="text-2xl font-light py-4 text-center uppercase">{{ $title }}</div>
    @endif
    <table class="w-full text-sm border-b border-gray-200">
        <thead class="bg-gray-800 text-gray-100 font-medium">
            <tr>
                <th class="px-3 py-2 text-left border-r border-gray-600">Admission No</th>
                <th class="px-3 py-2 text-left border-r border-gray-600">Name</th>
                @if (empty(request()->input('g')))
                    <th class="px-3 py-2 text-left border-r border-gray-600">Gender</th>
                @endif
                <th class="px-3 py-2 text-left border-r border-gray-600">Age</th>
                @if (empty(request()->input('i')))
                    <th class="px-3 py-2 text-left border-r border-gray-600">Class</th>
                @endif
                @if (empty(request()->input('c')))
                    <th class="px-3 py-2 text-left border-r border-gray-600">Course</th>
                @endif
                @if (empty(request()->input('sp')))
                    <th class="px-3 py-2 text-left border-r border-gray-600">Sponsor</th>
                @endif
                @if (empty(request()->input('pr')))
                    <th class="px-3 py-2 text-left">Program</th>
                @endif
                @if (empty(request()->input('su')))
                    <th class="px-3 py-2 text-left">Status</th>
                @endif
            </tr>
        </thead>
        <tbody class="font-normal">
            @foreach ($students as $student)
                <tr class="even:bg-gray-100">
                    <td class="px-3 py-2 border-r border-l border-gray-200">{{ $student->admission_no }}</td>
                    <td class="px-3 py-2 border-r border-gray-200 uppercase">
                        {{ $student->first_name }}{{ $student->middle_name ? ' ' . $student->middle_name : '' }}
                        {{ $student->surname }}
                    </td>
                    @if (empty(request()->input('g')))
                        <td class="px-3 py-2 border-r border-gray-200">{{ $student->gender ? 'Female' : 'Male' }}</td>
                    @endif
                    <td class="px-3 py-2 border-r border-gray-200">{{ $student->age }}</td>
                    @if (empty(request()->input('i')))
                        <td class="px-3 py-2 border-r border-gray-200">{{ $student->intake->name }}</td>
                    @endif
                    @if (empty(request()->input('c')))
                        <td class="px-3 py-2 border-r border-gray-200">{{ $student->intake->course }}</td>
                    @endif
                    @if (empty(request()->input('sp')))
                        <td class="px-3 py-2 border-r border-gray-200">{{ $student->sponsor->name }}</td>
                    @endif
                    @if (empty(request()->input('pr')))
                        <td class="px-3 py-2 border-r border-gray-200">{{ $student->program->name }}</td>
                    @endif
                    @if (empty(request()->input('su')))
                        <td class="px-3 py-2 border-r border-gray-200">{{ $student->status }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
