@extends('admin::layout.main')

@section('title')
    E-School::Intakes/Classes
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Intake (Classes)
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('intakes.sidebar')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form id="intakeForm" method="post"
                        action="{{ $errors->count() && old('id') ? route('admin-eschool-intakes-edit-post') : route('admin-eschool-intakes-add-post') }}"
                        role="form" accept-charset="UTF-8" enctype="multipart/form-data">

                        <div class="form-group{!! $errors->has('start_date') ? ' has-error' : '' !!}">
                            <label for="start_date">Start Date</label>
                            <input type="text" class="form-control datepicker" id="start_date" name="start_date"
                                {!! old('start_date') ? ' value="' . old('start_date') . '"' : '' !!}>
                            {!! $errors->has('start_date') ? '<span class="text-danger">' . $errors->first('start_date') . '</span>' : '' !!}
                        </div>

                        <div class="form-group{!! $errors->has('end_date') ? ' has-error' : '' !!}">
                            <label for="end_date">End Date</label>
                            <input type="text" class="form-control datepicker" id="end_date" name="end_date"
                                {!! old('end_date') ? ' value="' . old('end_date') . '"' : '' !!}>
                            {!! $errors->has('end_date') ? '<span class="text-danger">' . $errors->first('end_date') . '</span>' : '' !!}
                        </div>

                        <div class="form-group{!! $errors->has('course') ? ' has-error' : '' !!}">
                            <label for="course">Course</label>
                            <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true"
                                id="course" name="course" {!! old('course') ? ' value="' . old('course') . '"' : '' !!}>
                                @foreach (\Ogilo\Eschool\Models\Course::all() as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('course') ? '<span class="text-danger">' . $errors->first('course') . '</span>' : '' !!}
                        </div>

                        <div class="form-group{!! $errors->has('instructor_incharge') ? ' has-error' : '' !!}">
                            <label for="instructor_incharge">Instructor Incharge</label>
                            <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true"
                                id="instructor_incharge" name="instructor_incharge" {!! old('instructor_incharge') ? ' value="' . old('instructor_incharge') . '"' : '' !!}>
                                @foreach (\Ogilo\Eschool\Models\Staff::all() as $instructor_incharge)
                                    <option value="{{ $instructor_incharge->id }}">{{ $instructor_incharge->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('instructor_incharge')
                                ? '<span class="text-danger">' . $errors->first('instructor_incharge') . '</span>'
                                : '' !!}
                        </div>

                        <input type="hidden" name="id" value="" id="intakeId">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-primary btn-round"><span class="material-icons">save</span>
                            Save</button>
                        <a href="JavaScript:" id="cancelEditButton"class="btn btn-round btn-danger"
                            style="display: none;"><i class="material-icons">cancel</i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped datatable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Instructor</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($intakes as $intake)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $intake->name }}</td>
                                        <td>{{ $intake->course->name }}</td>
                                        <td>{{ date_create($intake->start_date)->format('j-M-Y') }}</td>
                                        <td>{{ date_create($intake->end_date)->format('j-M-Y') }}</td>
                                        <td>{{ $intake->staff->name }}</td>
                                        <td>
                                            <a href="JavaScript:"
                                                class="btn btn-primary btn-round btn-fab btn-sm editIntake"
                                                data-intake="{{ $intake->toJson() }}"><i
                                                    class="material-icons">edit</i></a>
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
@endsection

@section('scripts_bottom')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.editIntake').click(function() {
                url = '{{ route('admin-eschool-intakes-edit-post') }}'
                $('#intakeForm').attr('action', url)
                $('#start_date').val($(this).data('intake').start_date)
                $('#end_date').val($(this).data('intake').end_date)
                $('#course').val($(this).data('intake').course_id)
                $('#instructor_incharge').val($(this).data('intake').staff_id)
                $('#intakeId').val($(this).data('intake').id)

                $('#cancelEditButton').attr('style', 'display: inline-block')
            })

            $('#cancelEditButton').click(() => {
                url = '{{ route('admin-eschool-intakes-add-post') }}'
                $('#intakeForm').attr('action', url)

                $('#start_date').val(null)
                $('#end_date').val(null)
                $('#course').val(null)
                $('#instructor_incharge').val(null)
                $('#intakeId').val(null)

                $('#cancelEditButton').attr('style', 'display: none')
            })
        })
    </script>
@endsection
