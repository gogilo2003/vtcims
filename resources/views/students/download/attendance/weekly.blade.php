@extends('eschool::layout.pdf')

@section('title')
    E-School::Attendance for ({{ $classes }})
@endsection


@section('content')
    <div class="table-round mb-3">
        <table class="table my-0">
            <thead class="thead-light text-uppercase">
                <tr>
                    <th style="max-width: 50%">Class(s)/Intake(s)</th>
                    <th>Subject</th>
                    <th>From</th>
                    <th>To</th>
                </tr>
            </thead>
            <tr>
                <td>{{ $classes }}</td>
                <td>{{ $subject->name }}</td>
                <td>{!! $days[array_key_first($days)]->format('l, j\<\s\u\p\>S\<\/\s\u\p\> F Y') !!}</td>
                <td>{!! $days[array_key_last($days)]->format('l, j\<\s\u\p\>S\<\/\s\u\p\> F Y') !!}</td>
            </tr>
        </table>
    </div>
    <div class="table-round mb-3">
        <table class="table black table-sm table-bordered table-striped my-0">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2" style="width: 50px;"></th>
                    <th rowspan="2" class="text-uppercase text-center" style="width: 130px">Admission No</th>
                    <th rowspan="2" class="text-uppercase text-center" style="width: 280px">Name</th>
                    @foreach ($days as $day)
                        <th colspan="3" class="text-uppercase text-center">{!! strtoupper($day->format('D\<\b\r\>\<\s\m\a\l\l\>\(j-M-Y\)\<\/\s\m\a\l\l\>')) !!}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($days as $day)
                        <th class="text-uppercase text-center">L1</th>
                        <th class="text-uppercase text-center">L2</th>
                        <th class="text-uppercase text-center">L3</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td class="text-right">{{ $loop->iteration }}</td>
                        <td>{{ $student->admission_no }}</td>
                        <td class="text-uppercase">{{ $student->name }}</td>
                        @foreach ($days as $day)
                            <td></td>
                            <td></td>
                            <td></td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="18">
                        Key: L1 - Lesson 1 (8:00 Am - 10:00 Am), L2 - Lesson 2 (10:30 Am - 12:30 Pm), L3 - Lesson 3 (2:00 Pm
                        - 4:00 Pm)
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="table-round mt-3">
        <table class="table table-bordered text-uppercase my-0">
            <thead class="thead-light">
                <tr>
                    <th style="width: 20%">
                        Teacher's Name
                    </th>
                    <th style="width: 15%">
                        Date
                    </th>
                    <th style="width: 15%">
                        Signature
                    </th>
                    <th style="width: 20%">
                        Principal's Name
                    </th>
                    <th style="width: 15%">
                        Date
                    </th>
                    <th style="width: 15%">
                        Signature
                    </th>
                </tr>
            </thead>
            <tr>
                <td>{{ $staff->min_name }}</td>
                <td></td>
                <td></td>
                <td>{{ $principal->min_name }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
@endsection

@section('styles')
    <style type="text/css">
        .table.black>thead>tr>th,
        .table.black>tbody>tr>td,
        .table.black .thead-light tr th {
            border-color: #222;
        }

    </style>
@endsection
