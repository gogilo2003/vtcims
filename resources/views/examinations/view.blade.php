@extends('admin::layout.main')

@section('title')
    E-School::{{ $examination->title }}
@endsection

@section('page_title')
    {{ implode(", ",$examination->intakes()->pluck('name')->toArray()) }}
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::examinations.sidebar')
@endsection

@section('content')

    <a href="{{ route('admin-eschool-examinations-marks',$examination->id) }}" class="btn btn-round btn-primary"><span class="material-icons">content_paste</span> Marks Entry</a>
    <a href="{{ route('admin-eschool-examinations-download',$examination->id) }}" class="btn btn-round btn-primary"><span class="material-icons">cloud_download</span> Download Marklist</a>
    <table class="table table-bordered text-uppercase">
        <thead class="thead-light">
            <tr>
                <th style="width:50px; text-align:center"></th>
                <th style="width:150px; text-align:center">Admission No</th>
                <th>Student Name</th>
                @foreach ($examination->tests as $test)
                <th style="width:120px; text-align:center">{{ $test->title }}</th>
                @endforeach
                <th style="width:120px; text-align:center">GRADE</th>
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

@section('scripts_bottom')

@endsection
