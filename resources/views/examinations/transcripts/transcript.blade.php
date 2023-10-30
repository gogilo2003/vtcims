@if (isset($student))
<div class="transcript-wrapper">
    <div class="transcript-title">
        Academic Transcript
    </div>
    <div class="transcript-header">
        <div class="transcript-item"><span class="caption">Name: </span><span class="underline">{{ $student->name }}</span></div>
        <div class="transcript-item"><span class="caption">Admission No: </span><span class="underline">{{ $student->admission_no }}</span></div>
        <div class="transcript-item"><span class="caption">Course: </span><span class="underline">{{ $student->intake->course->name }}</span></div>
        <div class="transcript-item"><span class="caption">Program/Exam: </span><span class="underline">{{ $student->program->name }}</span></div>
        <div class="transcript-item"><span class="caption">Class: </span><span class="underline">{{ $student->intake->name }}</span></div>
        <div class="transcript-item"><span class="caption">Session: </span><span class="underline">{{ $term->year_name }}</span></div>
    </div>
    <table class="list-table">
        <thead>
            <tr>
                <th style="width: 8rem">Subject Code</th>
                <th>Subject</th>
                <th>Score</th>
                <th>Out Of</th>
                <th>Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
        @php
            $total = 0;
            $counter = 0;
            $max_score = 0;
        @endphp
        @foreach($student->intake->examinations->where('term_id',$term->id) as $examination)
            @php
                $score = 0;
            @endphp
            <tr>
                <td>{{ $examination->subject->code }}</td>
                <td>{{ $examination->subject->name }}</td>
                @foreach ($examination->results->where('student_id',$student->id) as $result)
                    @php
                        $score += $result->score
                    @endphp
                @endforeach
                <td>{{ $score }}</td>
                <td>{{ $examination->tests->sum('outof') }}</td>
                <td>{{ $grade = do_grade($score) }}</td>
                <td>{{ do_remarks($grade) }}</td>
            </tr>
            @php
                $total += $score;
                $max_score += $examination->tests->sum('outof');
            @endphp
        @endforeach
        @php
            $mean = $student->intake->examinations->count()  ? $total / $student->intake->examinations->count() : 0;
            $grade = do_grade($mean);
        @endphp
        <tbody>
    </table>
    <hr>
    <p class="total-score" colspan="5"><span class="caption">TOTAL SCORE: </span><span class="value">{{ $total }} out of {{ $max_score }}</span></p>
    <p class="mean-grade" colspan="5"><span class="caption">MEAN GRADE: </span><span class="value">{{ $grade }}</span></p>
    <p class="mean-grade" colspan="5"><span class="caption">REMARKS: </span><span class="value">{{ do_remarks($grade) }}</span></p>
    <p>Date of Issue: {{ date('j-F-Y') }}</p>
    <hr>
    <div class="authority">
        <div class="principal">
            <div class="stamp"></div>
            <div class="signature"></div>
            <div class="name">{{ $principal->min_name }}</div>
            <div class="position">Principal of {{ config('eschool.name') }}</div>
        </div>
    </div>
</div>
@else
<p>No student provided</p>
@endif

@push('styles')
    <!-- Transcript styles -->
    @php
        $filename = "transcript.css";
    @endphp

    @if (file_exists(public_path('vendor/eschool/css/'.$filename)))
        <link rel="stylesheet" href="{{ asset('vendor/eschool/css/'.$filename) }}">
    @else
        @php
            $file = str_replace('storage/framework/views','',dirname(__FILE__)).'vendor/gogilo/eschool/public/css/'.$filename;
        @endphp
        <style>

        {{ file_get_contents($file) }}

        </style>
    @endif
@endpush
