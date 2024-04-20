@extends('admin::layout.main')

@section('title')
    E-School::Subject Allocation
@endsection

@section('page_title')
    <i class="fa fa-th-list"></i> Subject Allocation
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('subjects.sidebar')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <form id="subjectAllocationForm" method="post" action="{{ route('admin-eschool-subjects-allocate-post') }}"
                    role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="card-header card-header-primary">
                        <h4>Change/Allocate Subject</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group bmd-form-group{!! $errors->has('id') ? ' has-error' : '' !!}">
                            <input type="hidden" class="form-control" id="id" name="id"{!! old('id') ? ' value="' . old('id') . '"' : '' !!}>
                            {!! $errors->has('id') ? '<span class="text-danger">' . $errors->first('id') . '</span>' : '' !!}
                        </div>

                        <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('subject') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="subject">Subject</label>
                            <select data-live-search="true" class="form-control selectpicker" data-style="btn btn-link"
                                id="subject" name="subject"{!! old('subject') ? ' value="' . old('subject') . '"' : '' !!}>
                                @foreach (Ogilo\Eschool\Models\Subject::all() as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('subject') ? '<span class="text-danger">' . $errors->first('subject') . '</span>' : '' !!}
                        </div>
                        <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('class') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="class">Class/Intake</label>
                            <select data-live-search="true" class="form-control selectpicker" data-style="btn btn-link"
                                id="class" name="class"{!! old('class') ? ' value="' . old('class') . '"' : '' !!}>
                                @foreach (Ogilo\Eschool\Models\Intake::all() as $intake)
                                    <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('class') ? '<span class="text-danger">' . $errors->first('class') . '</span>' : '' !!}
                        </div>
                        <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('teacher') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="teacher">Teacher</label>
                            <select data-live-search="true" class="form-control selectpicker" data-style="btn btn-link"
                                id="teacher" name="teacher"{!! old('teacher') ? ' value="' . old('teacher') . '"' : '' !!}>
                                @foreach (Ogilo\Eschool\Models\Staff::all() as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('teacher') ? '<span class="text-danger">' . $errors->first('teacher') . '</span>' : '' !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary btn-round"><i class="material-icons">save</i>
                            Save</button>
                        <a href="JavaScript:" id="cancelSubjectAllocationButton" class="btn btn-round btn-danger"
                            style="display: none"><i class="material-icons">cancel</i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-8 table-responsive">
            <table id="subjectAllocationsTable" class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Teacher</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allocations as $allocation)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $allocation->subject->name }}</td>
                            <td>{{ $allocation->intake->name }}</td>
                            <td>{{ $allocation->staff->name }}</td>
                            <td>
                                <a href="JavaScript:" data-allocation="{{ $allocation->toJson() }}"
                                    class="btn btn-fab btn-round btn-primary btn-sm editSubjectAllocation"><i
                                        class="material-icons">edit</i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts_bottom')
    <script type="text/javascript">
        $('.editSubjectAllocation').click(function() {

            $('#subjectAllocationForm #id').val($(this).data('allocation').id)
            $('#subjectAllocationForm #subject').selectpicker('val', $(this).data('allocation').subject_id)
            $('#subjectAllocationForm #class').selectpicker('val', $(this).data('allocation').intake_id)
            $('#subjectAllocationForm #teacher').selectpicker('val', $(this).data('allocation').staff_id)

            $('#cancelSubjectAllocationButton').css('display', 'inline-block');

        })

        $('#cancelSubjectAllocationButton').click(function() {

            $('#subjectAllocationForm #id').val(null)
            $('#subjectAllocationForm #subject').selectpicker('val', null)
            $('#subjectAllocationForm #class').selectpicker('val', null)
            $('#subjectAllocationForm #teacher').selectpicker('val', null)

            $('#cancelSubjectAllocationButton').css('display', 'none');

        })
        $('#subjectAllocationsTable').dataTable();
    </script>
@endsection
