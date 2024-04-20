@extends('admin::layout.main')

@section('title')
    E-School::Transcript
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> {{ isset($student) ? $student->name : 'Transcript' }}
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('examinations.sidebar')
@endsection

@section('content')
    <a href="JavaScript:" data-toggle="modal" data-target="#studentTranscriptModal" class="btn btn-primary btn-round"><span
            class="material-icons">contacts</span> Transcripts</a>

    @isset($student)
        <form method="post" action="{{ route('admin-eschool-examinations-transcripts-post') }}" style="display: inline-block;">
            <input type="hidden" name="admission_no" value="{{ $student->id }}">
            <input type="hidden" name="term" value="{{ $term->id }}">
            <input type="hidden" name="download" value="true">
            @csrf
            <button type="submit" class="btn btn-outline-primary rounded-pill"><span
                    class="material-icons">cloud_download</span> Download</button>
        </form>
    @endisset
    @isset($intake)
        <form method="post" action="{{ route('admin-eschool-examinations-transcripts-post') }}" style="display: inline-block;">
            <input type="hidden" name="intake" value="{{ $intake->id }}">
            <input type="hidden" name="term" value="{{ $term->id }}">
            <input type="hidden" name="download" value="true">
            @csrf
            <button type="submit" class="btn btn-outline-primary rounded-pill"><span
                    class="material-icons">cloud_download</span> Download</button>
        </form>
    @endisset

    @isset($student)
        @include('examinations.transcripts.transcript')
    @endisset

    @isset($intake)
        @foreach ($intake->students as $student)
            @include('examinations.transcripts.transcript', ['student' => $student, 'term' => $term])
            <hr>
        @endforeach
    @endisset

    @include('examinations.transcripts.student-modal')
@endsection

@section('scripts_bottom')
    <script type="text/javascript">
        $(document).ready(() => {
            $('select#examination_group').selectpicker('val',
                {{ old('examination_group') ? old('examination_group') : (isset($examination_group) ? $examination_group->id : 'null') }}
                )

            $('select#examination_group').change(() => {
                let group = $('select#examination_group').val()
                $('form#downloadForm input[name="examination_group"]').val(group)
            })

            $('input#admission_no').change(() => {
                let admission_no = $('input#admission_no').val()
                $('form#downloadForm input[name="admission_no"]').val(admission_no)
            })

            $('a#resetForm').click(() => {
                $('select#examination_group').selectpicker('val', null)
                $('input#admission_no').val(null)
            })
        })
    </script>
@endsection
