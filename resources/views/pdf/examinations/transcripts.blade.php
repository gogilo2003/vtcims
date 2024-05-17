@extends('layout.pdf')

@section('title')
    E-School::Transcript
@endsection

@section('content')
    @foreach ($transcripts as $transcript)
        <div style="{{ $loop->first ? '' : 'page-break-before: always' }}">
            <h1 style="height: 0; overflow:hidden; border: 0">{{ $transcript->admission_no }}</h1>
            @if (!$loop->first)
                @include('layout.pdf-header')
            @endif
            @if (file_exists(resource_path('views/pdf/custom/examinations/transcript')))
                @include('pdf.custom.examinations.transcript', [
                    'transcript' => $transcript,
                    'term' => $term,
                ])
            @else
                @include('pdf.examinations.transcript', ['transcript' => $transcript, 'term' => $term])
            @endif
        </div>
    @endforeach
@endsection
