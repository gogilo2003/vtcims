@extends('admin::layout.main')

@section('title')
    E-School::Examinations
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Examinations
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('examinations.sidebar')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="JavaScript:" data-toggle="modal" data-target="#studentTranscriptModal"
                class="btn btn-primary btn-round"><span class="material-icons">contacts</span> Transcripts</a>
        </div>
        <div class="col-md-4">

            <div class="card">
                <form id="examinationForm" method="post"
                    action="{{ $errors->count() && old('id') ? route('admin-eschool-examinations-edit-post') : route('admin-eschool-examinations-add-post') }}"
                    role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group{!! $errors->has('intake') ? ' has-error' : '' !!}">
                            <label for="intake" class="bmd-label-static">Intake</label>
                            <select multiple="multiple" data-live-search="true" class="form-control selectpicker"
                                id="intake" name="intakes[]" data-style="btn btn-link">
                                @foreach (\Ogilo\Eschool\Models\Intake::orderBy('created_at', 'DESC')->get() as $intake)
                                    <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('intake') ? '<span class="text-danger">' . $errors->first('intake') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('subject') ? ' has-error' : '' !!}">
                            <label for="subject" class="bmd-label-static">Subject</label>
                            <select data-live-search="true" class="form-control selectpicker" id="subject" name="subject"
                                data-style="btn btn-link">
                                @foreach (\Ogilo\Eschool\Models\Subject::orderBy('name', 'ASC')->get() as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('subject') ? '<span class="text-danger">' . $errors->first('subject') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('term') ? ' has-error' : '' !!}">
                            <label for="term" class="bmd-label-static">Term</label>
                            <select data-live-search="true" class="form-control selectpicker" id="term" name="term"
                                data-style="btn btn-link">
                                @foreach (\Ogilo\Eschool\Models\Term::orderBy('created_at', 'DESC')->get() as $term)
                                    <option value="{{ $term->id }}">{{ $term->year_name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('term') ? '<span class="text-danger">' . $errors->first('term') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('title') ? ' has-error' : '' !!}">
                            <label class="bmd-label-static" for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                {!! old('title') ? ' value="' . old('title') . '"' : '' !!}>
                            {!! $errors->has('title') ? '<span class="text-danger">' . $errors->first('title') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('notes') ? ' has-error' : '' !!}">
                            <label class="bmd-label-static" for="name">Notes</label>
                            {!! $errors->has('notes') ? '<span class="text-danger">' . $errors->first('notes') . '</span>' : '' !!}
                            <textarea class="form-control" id="notes" name="notes"> {!! old('notes') ? old('notes') : '' !!}</textarea>
                        </div>
                        <div class="form-group{!! $errors->has('id') ? ' has-error' : '' !!}">
                            {!! $errors->has('id') ? '<span class="text-danger">' . $errors->first('id') . '</span>' : '' !!}
                        </div>
                        <input type="hidden" name="id" value="" id="examinationId">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-round"><span class="material-icons">save</span>
                            Save</button>
                        <a style="display:none" href="JavaScript:" class="btn btn-danger btn-round"
                            id="cancelExaminationButton"><span class="material-icons">cancel</span> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="examinationsDataTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Title</th>
                                    <th>Tests</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($examinations as $examination)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $examination->title }}</td>
                                        <td><a href="{{ route('admin-eschool-examinations-tests', $examination->id) }}"
                                                class="btn btn-sm btn-round btn-primary">{{ $examination->tests->count() }}
                                                Tests</a></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle btn-round btn-sm"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="material-icons">settings</i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                    <a href="{{ route('admin-eschool-examinations-download', $examination->id) }}"
                                                        class="dropdown-item">
                                                        <i class="material-icons">cloud_download</i>&nbsp;&nbsp;
                                                        Download Details
                                                    </a>
                                                    <a href="JavaScript:" class="dropdown-item editExaminationButton"
                                                        data-examination="{{ $examination->toJson() }}"><i
                                                            class="material-icons">edit</i> Edit</a>
                                                    <a href="{{ route('admin-eschool-examinations-view', $examination->id) }}"
                                                        class="dropdown-item"><i class="material-icons">view_list</i> View
                                                        Details</a>
                                                    <a href="{{ route('admin-eschool-examinations-marks', $examination->id) }}"
                                                        class="dropdown-item"><i class="material-icons">assessment</i>
                                                        Marks Entry</a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('examinations.transcripts.student-modal')
@endsection


@section('scripts_bottom')
    <script type="text/javascript">
        $(document).ready(function() {
            $('select#intake').selectpicker('val', {!! old('intake') ? old('intake') : 'null' !!})
            $('select#subject').selectpicker('val', {!! old('subject') ? old('subject') : 'null' !!})

            $('select#subject, select#term').change(function() {
                setTitle()
            })

            $('select#intake').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                setTitle()
            });

            $('.editExaminationButton').click(function() {
                url = '{{ route('admin-eschool-examinations-edit-post') }}'
                $('#examinationForm').attr('action', url)
                $('#intake').selectpicker('val', $(this).data('examination').intake_ids)
                $('#subject').selectpicker('val', $(this).data('examination').subject_id)
                $('#title').val($(this).data('examination').title)
                $('#name').val($(this).data('examination').name)
                $('#examinationId').val($(this).data('examination').id)
                $('#title').focus()
                $('#cancelExaminationButton').css('display', 'inline-block')
            });
            $('#cancelExaminationButton').click(function() {
                url = '{{ route('admin-eschool-examinations-add-post') }}'
                $('#examinationForm').attr('action', url)
                $('#intake').selectpicker('val', null)
                $('#subject').selectpicker('val', null)
                $('#title').val(null)
                $('#name').val(null)
                $('#examinationId').val(null)
                $('#title').focus()
                $('span.text-danger').html('')
                $('div.form-group').removeClass('has-error')
                $('#cancelExaminationButton').css('display', 'none')
            });

            $('select#intake').change()

            $('table#examinationsDataTable').dataTable()
        })

        setTitle = function() {
            let term = $('select#term option:selected').html()
            let subject = $('select#subject option:selected').html()
            let intakes = $('select#intake option:selected')
            // console.log(intakes.length)
            let intake = intakes.length === 1 ? '-' + intakes[0].innerHTML : ''
            subject = typeof(subject) === 'undefined' ? '' : subject
            intake = typeof(intake) === 'undefined' ? '' : intake
            let title = term + intake + (subject ? ' - ' + subject : '');
            // if(($('input#title').val().length == 0) || ($('input#title').val() == $('select#intake option:selected').html())){
            $('input#title').val(title)
            // }
        }
    </script>
@endsection
