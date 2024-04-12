@extends('layout.pdf')
@php
    $intakes = implode(', ', $allocation->intakes->map(fn($item) => $item->name)->toArray());
@endphp
@section('title')
    E-School::Attendance for ({{ $intakes }})
@endsection

@section('content')
    <div class="p-4">
        <table class="table w-full my-0 border-collapse">
            <thead class="bg-gray-100 uppercase">
                <tr>
                    <th class="max-w-[50%] text-left border px-2 py-1 border-gray-800">Class(s)/Intake(s)</th>
                    <th class="text-left border px-2 py-1 border-gray-800">Subject</th>
                    <th class="text-left border px-2 py-1 border-gray-800">From</th>
                    <th class="text-left border px-2 py-1 border-gray-800">To</th>
                </tr>
            </thead>
            <tr>
                <td class="border border-gray-800 p-2 text-sm text-gray-800 font-light">{{ $intakes }}</td>
                <td class="border border-gray-800 p-2 text-sm text-gray-800 font-light">{{ $allocation->subject->name }}
                </td>
                <td class="border border-gray-800 p-2 text-sm text-gray-800 font-light">{!! $start_date !!}</td>
                <td class="border border-gray-800 p-2 text-sm text-gray-800 font-light">{!! $end_date !!}</td>
            </tr>
        </table>
    </div>
    <div class="p-1">
        <table class="w-full border border-gray-800">
            <thead class="bg-gray-100">
                <tr class="">
                    <th rowspan="2" class="text-sm w-12 border border-gray-800"></th>
                    <th rowspan="2" class="text-sm uppercase text-center border border-gray-800" style="width: 130px">
                        Admission
                        No
                    </th>
                    <th rowspan="2" class="text-sm uppercase text-center border border-gray-800" style="width: 280px">
                        Name</th>
                    <th rowspan="2" class="text-sm uppercase text-center border border-gray-800 w-16">
                        Gender</th>
                    <th rowspan="2" class="text-sm uppercase text-center border border-gray-800 w-16">PWD
                    </th>
                    @foreach ($allocation->lessons as $lesson)
                        <th colspan="{{ $lesson->lessons->count() }}"
                            class="text-sm uppercase text-center border border-gray-800">
                            {!! strtoupper($lesson->date) !!}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($allocation->lessons as $lesson)
                        @foreach ($lesson->lessons as $item)
                            <th class="text-sm uppercase text-center border border-gray-800 p-2">{{ $item->short_title }}
                            </th>
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody class="text-sm font-extralight">
                @php
                    $cols = 5;
                @endphp
                @foreach ($students as $student)
                    <tr class="even:bg-gray-50 odd:bg-white">
                        <td class="p-2 text-right border-r border-gray-800">{{ $loop->iteration }}.</td>
                        <td class="p-2  border-r border-gray-800">{{ $student->admission_no }}</td>
                        <td class="p-2 uppercase border-r border-gray-800">{{ $student->name }}</td>
                        <td class="p-2 uppercase border-r border-gray-800">{{ $student->gender }}</td>
                        <td class="p-2 uppercase border-r border-gray-800">{{ $student->plwd }}</td>
                        @foreach ($allocation->lessons as $lesson)
                            @foreach ($lesson->lessons as $item)
                                <td class="border border-gray-800"></td>
                                @php
                                    $cols += 1;
                                @endphp
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="{{ $cols }}" class="text-center p-2 border border-gray-800">
                        @include('students.download.components.key')
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="p-4">
        @include('students.download.components.attendance-footer', [
            'instructor' => $allocation->staff,
        ])
    </div>
@endsection
