@extends('eschool::layout.pdf')

@section('title')
    E-School::Transcript
@endsection

@section('content')
    @isset($student)
    @include('eschool::examinations.transcripts.transcript')
    @endisset
    @isset($intake)
        @foreach ($intake->students as $student)
            <div style="{{ $loop->first ? '' : 'page-break-before: always' }}">
                <h1 style="height: 0; overflow:hidden; border: 0">{{ $student->admission_no }}</h1>
                @if (!$loop->first)
                    @include('eschool::layout.pdf-header')
                @endif
                @include('eschool::examinations.transcripts.transcript',['student'=>$student,'term'=>$term])
            </div>
        @endforeach
    @endisset
@endsection
