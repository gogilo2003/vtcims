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
                <th class="border-r border-gray-800 px-3 py-2 w-56">Admission No</th>
                <th class="border-r border-gray-800 px-3 py-2 text-left">Student Name</th>
                @foreach ($examination->tests as $test)
                    <th class="border-r border-gray-800 px-3 py-2" style="width:120px; text-align:center">
                        {{ $test->title }}
                    </th>
                @endforeach

                @if (!isset($blank))
                    <th class="border-r border-gray-800 px-3 py-2 w-20">Grade</th>
                    <th class="border-r border-gray-800 px-3 py-2 text-left" style="width:120px;">Remarks</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($examination->students as $student)
                <tr class="even:bg-gray-100">
                    <td class="text-center border-l border-r border-gray-800 px-3 py-2">{{ $loop->iteration }}</td>
                    <td class="text-center border-r border-gray-800 px-3 py-2">{{ $student->admission_no }}</td>
                    <td class="border-r border-gray-800 px-3 py-2">{{ $student->name }}</td>
                    @foreach ($student->results as $result)
                        @if (isset($blank))
                            <td class="text-center border-r border-gray-800 px-3 py-2"></td>
                        @else
                            @if ($result->score)
                                <td class="text-center border-r border-gray-800 px-3 py-2">{{ $result->score }}</td>
                            @else
                                <td class="text-center border-r border-gray-800 px-3 py-2">-</td>
                            @endif
                        @endif
                    @endforeach
                    @if (!isset($blank))
                        <td class="text-center border-r border-gray-800 px-3 py-2">{{ $student->grade }}</td>
                        <td class="border-r border-gray-800 px-3 py-2">{{ $student->remarks }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
