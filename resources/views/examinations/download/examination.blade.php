@extends('eschool::layout.pdf')

@section('content')
    <h4>Marklist for <small>{{ $examination->title }}</small></h4>
    <h5>
        {{ $intakes }}
    </h5>
    <table class="table table-bordered text-uppercase">
        <thead class="thead-light">
            <tr>
                <th style="width:50px; text-align:center"></th>
                <th style="width:150px; text-align:center">Admission No</th>
                <th>Student Name</th>
                @foreach ($examination->tests as $test)
                <th style="width:120px; text-align:center">{{ $test->title }}</th>
                @endforeach
                <th style="width:120px; text-align:center">Grade</th>
                <th style="width:120px; text-align:center">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 0;
            @endphp
            @foreach ($examination->intakes as $intake)
                @foreach ($intake->students->where('status','In session') as $student)
                    <tr>
                        <td style="text-align:center">{{ ++$i }}</td>
                        <td style="text-align:center">{{ $student->admission_no }}</td>
                        <td>{{ $student->name }}</td>
                        @php
                            $grade = null;
                            $remarks = null;
                            $total = 0;
                        @endphp
                        @foreach ($examination->tests as $test)
                            @php

                            @endphp
                            @forelse ($student->results->where('test_id',$test->id) as $result)
                                @php
                                    $score = $result->score;//round($result->score / $test->outof * 100);
                                    $total += $score;
                                @endphp
                                <td style="text-align:center">{{ $score }}</td>
                            @empty
                                <td style="text-align:center">-</td>
                            @endforelse
                        @php
                            $grade = do_grade($total);
                            $remarks = do_remarks($grade);
                        @endphp
                        @endforeach
                        <td style="text-align:center">{{ $grade }}</td>
                        <td style="text-align:center">{{ $remarks }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection
