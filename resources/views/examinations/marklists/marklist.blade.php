<table class="table table-bordered text-uppercase">
        <thead class="thead-light">
            <tr>
                <th></th>
                <th>Admission No</th>
                <th>Name</th>
                @foreach ($intake->examinations as $examination)
                    <th>{{ $examination->subject->code }}</th>
                @endforeach
                <th>Total</th>
                <th>Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 0;
            @endphp
            @foreach ($intake->students()->has('results')->get() as $student)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $student->admission_no }}</td>
                    <td>{{ $student->name }}</td>
                    @php
                        $total = 0
                    @endphp
                    @foreach ($intake->examinations as $examination)
                        @php
                            $score = 0;
                        @endphp
                        @foreach ($examination->tests as $test)
                            @foreach ($student->results->where('test_id',$test->id) as $result)
                                @php
                                    $score += $result->score;
                                @endphp
                            @endforeach
                        @endforeach
                        @php
                            $total += $score;
                        @endphp
                        <td>{{ $score }}</td>
                    @endforeach
                    @php
                        $mean = $intake->examinations->count() ? $total/$intake->examinations->count() : 0;
                        $grade = do_grade($mean)
                    @endphp
                    <td>{{ $total }}</td>
                    <td>{{ $grade }}</td>
                    <td>{{ do_remarks($grade) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h4>KEY</h4>
    <ul type="circle">
        @foreach ($intake->examinations as $examination)
            <li>
                {{ $examination->subject->code }} - {{ $examination->subject->name }}
            </li>
        @endforeach
    </ul>
