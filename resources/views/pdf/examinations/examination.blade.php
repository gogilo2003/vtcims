@extends('layout.pdf')

@section('content')
    <div class="py-5">
        <h4 class="text-2xl font-extralight">Marklist for <small>{{ $examination->title }}</small></h4>
        <h5 class="text-sm font-medium text-gray-700">{{ $intakes }}</h5>
    </div>
    <table class="w-full border border-gray-800 px-3 py-2 uppercase text-sm">
        <thead class="bg-gray-500 text-gray-50">
            <tr>
                <th class="border-l border-r border-gray-800 px-3 py-2" style="width:50px; text-align:center"></th>
                <th class="border-r border-gray-800 px-3 py-2" style="width:150px; text-align:center">Admission No</th>
                <th class="border-r border-gray-800 px-3 py-2 text-left">Student Name</th>
                @foreach ($examination->tests as $test)
                    <th class="border-r border-gray-800 px-3 py-2" style="width:120px; text-align:center">
                        {{ $test->title }}
                    </th>
                @endforeach

                @if (!isset($blank))
                    <th class="border-r border-gray-800 px-3 py-2" style="width:120px; text-align:center">Grade</th>
                    <th class="border-r border-gray-800 px-3 py-2" style="width:120px; text-align:center">Remarks</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $i = 0;
            @endphp
            {{-- @foreach ($examination->intakes as $intake) --}}
            @foreach ($examination->students as $student)
                <tr class="even:bg-gray-100">
                    <td class="text-center border-l border-r border-gray-800 px-3 py-2">{{ ++$i }}</td>
                    <td class="text-center border-r border-gray-800 px-3 py-2">{{ $student->admission_no }}</td>
                    <td class="border-r border-gray-800 px-3 py-2">{{ $student->name }}</td>
                    @php
                        $grade = null;
                        $remarks = null;
                        $total = 0;
                    @endphp
                    @if (isset($blank))
                        <td class="text-center border-r border-gray-800 px-3 py-2"></td>
                    @else
                        @foreach ($examination->tests as $test)
                            @php

                            @endphp
                            @forelse ($student->results->where('test_id',$test->id) as $result)
                                @php
                                    $score = $result->score; //round($result->score / $test->outof * 100);
                                    $total += $score;
                                @endphp
                                <td class="text-center border-r border-gray-800 px-3 py-2">{{ $score }}</td>
                            @empty
                                <td class="text-center border-r border-gray-800 px-3 py-2">-</td>
                            @endforelse
                            @php
                                $grade = do_grade($total);
                                $remarks = do_remarks($grade);
                            @endphp
                        @endforeach
                        <td class="text-center border-r border-gray-800 px-3 py-2">{{ $grade }}</td>
                        <td class="text-center border-r border-gray-800 px-3 py-2">{{ $remarks }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
