@extends('admin::layout.main')

@section('title')
    E-School::Examination Groups
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Examination Groups
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('examinations.sidebar')
@endsection

@section('content')
    <a class="btn btn-primary btn-round" href="{{ route('admin-eschool-examinations-groups') }}"><span
            class="material-icons">view_list</span> Examination Groups</a>
    <a href="{{ route('admin-eschool-examinations-groups-add') }}" class="btn btn-primary btn-round"><span
            class="material-icons">add</span> Add Examination Group</a>
    <div class="card">
        <form id="examination_groupForm" method="post" action="{{ route('admin-eschool-examinations-groups-edit-post') }}"
            role="form" accept-charset="UTF-8" enctype="multipart/form-data">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('term') ? ' has-error' : '' !!}">
                            <label for="term" class="bmd-label-static">Term</label>
                            <select data-live-search="true" data-style="btn-link" class="form-control selectpicker"
                                id="term" name="term">
                                @foreach (\Ogilo\Eschool\Models\Term::all() as $term)
                                    <option value="{{ $term->id }}">{{ $term->year_name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('term') ? '<span class="text-danger">' . $errors->first('term') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('intake') ? ' has-error' : '' !!}">
                            <label for="intake" class="bmd-label-statick">Intake</label>
                            <select multiple data-live-search="true" class="form-control selectpicker" id="intake"
                                name="intake[]" data-style="btn btn-link">
                                @foreach (\Ogilo\Eschool\Models\Intake::orderBy('name', 'ASC')->get() as $intake)
                                    <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('intake') ? '<span class="text-danger">' . $errors->first('intake') . '</span>' : '' !!}
                        </div>
                        <br>
                        <div class="form-group{!! $errors->has('title') ? ' has-error' : '' !!}">
                            <label class="bmd-label-static" for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{!! old('title') ? old('title') : $examination_group->title !!}">
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
                    </div>
                    <div class="col-md-8">
                        <table class="table table-striped table-bordered" id="studentsTable">
                            <thead class="thead-light">
                                <tr>
                                    <th><span class="material-icons"></span></th>
                                    <th>Admission No</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($examination_group->intakes as $intake)
                                    @php
                                        $students = $intake->students;
                                        $selected = $examination_group->students->pluck('id')->toArray();
                                    @endphp
                                    @foreach ($students as $student)
                                        @php
                                            $ck = old('students')
                                                ? in_array($student->id, old('students'))
                                                : in_array($student->id, $selected);
                                            $checked = $ck ? 'checked' : '';
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input studentsCheckBox" type="checkbox"
                                                            value="{{ $student->id }}" name="students[]"
                                                            {{ $checked }}>
                                                        <span class="form-check-sign">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>{{ $student->admission_no }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->intake->name }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <input type="hidden" name="id" value="{{ $examination_group->id }}" id="examination_groupId">
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
@endsection

@section('scripts_bottom')
    <script type="text/javascript">
        $(document).ready(function() {
            $('select#term').selectpicker('val', {!! old('term') ? old('term') : ($examination_group->term_id ? $examination_group->term_id : 'null') !!})
            $('select#intake').selectpicker('val', {!! old('intake')
                ? json_encode(old('intake'))
                : ($examination_group->intakes->count()
                    ? json_encode($examination_group->intakes->pluck('id')->toArray())
                    : 'null') !!})

            $('table#studentsTable').dataTable()
        })
    </script>
@endsection
