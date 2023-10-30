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
                <td>{!! $term_start->format('l, j\<\s\u\p\>S\<\/\s\u\p\> F Y') !!}</td>
                <td>{!! $term_end->format('l, j\<\s\u\p\>S\<\/\s\u\p\> F Y') !!}</td>
            </tr>
        </table>
    </div>
    <div class="table-round mb-3">
        <table class="table black table-sm table-bordered table-striped my-0">
            <thead class="thead-light">
                <tr>
                    <th></th>
                    <th class="text-uppercase text-center">Admission No</th>
                    <th class="text-uppercase text-center">Name</th>
                    @foreach ($days as $key => $week)
                        <th class="text-uppercase text-center">{{ $key }}</th>
                    @endforeach
                </tr>
                {{-- <tr>
                    @foreach ($days as $week)
                        @foreach ($week as $day)
                            <th class=" text-center">{!! strtoupper($day->format('D\<\b\r\>\<\s\m\a\l\l\>\(j-M\)\<\/\s\m\a\l\l\>')) !!}</th>
                        @endforeach
                    @endforeach
                </tr> --}}
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->admission_no }}</td>
                        <td class="text-uppercase">{{ $student->name }}</td>
                        @foreach ($days as $week)
                            {{-- @foreach ($week as $day) --}}
                                <td></td>
                            {{-- @endforeach --}}
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
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
