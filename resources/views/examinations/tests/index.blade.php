@extends('admin::layout.main')

@section('title')
    E-School::Tests
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> {{ isset($examination) ? $examination->title : 'Tests' }}
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('examinations.sidebar')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <form id="testForm" method="post"
                    action="{{ $errors->count() && old('id') ? route('admin-eschool-examinations-tests-edit-post') : route('admin-eschool-examinations-tests-add-post') }}"
                    role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group{!! $errors->has('title') ? ' has-error' : '' !!}">
                            <label class="bmd-label-static" for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                {!! old('title') ? ' value="' . old('title') . '"' : '' !!}>
                            {!! $errors->has('title') ? '<span class="text-danger">' . $errors->first('title') . '</span>' : '' !!}
                        </div>
                        @if (isset($examination))
                            <input type="hidden" name="examination" value="{{ $examination->id }}">
                        @else
                            <div class="form-group{!! $errors->has('examination') ? ' has-error' : '' !!}">
                                <label for="examination" class="bmd-label-static">Select Examination</label>
                                <select data-live-search="true" data-style="btn-link" class="form-control selectpicker"
                                    id="examination" name="examination" {!! old('examination') ? ' value="' . old('examination') . '"' : '' !!}>
                                    @foreach (\Ogilo\Eschool\Models\Examination::all() as $exam)
                                        <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('examination') ? '<span class="text-danger">' . $errors->first('examination') . '</span>' : '' !!}
                            </div>
                        @endif
                        <br>
                        <div class="form-group{!! $errors->has('outof') ? ' has-error' : '' !!}">
                            <label class="bmd-label-static" for="outof">Marks</label>
                            <input type="text" class="form-control" id="outof" name="outof"
                                {!! old('outof') ? ' value="' . old('outof') . '"' : '' !!}>
                            {!! $errors->has('outof') ? '<span class="text-danger">' . $errors->first('outof') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('taken_on') ? ' has-error' : '' !!}">
                            <label class="bmd-label-static" for="taken_on">Taken On</label>
                            <input type="text" class="form-control datepicker" id="taken_on" name="taken_on"
                                {!! old('taken_on') ? ' value="' . old('taken_on') . '"' : '' !!}>
                            {!! $errors->has('taken_on') ? '<span class="text-danger">' . $errors->first('taken_on') . '</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('notes') ? ' has-error' : '' !!}">
                            <label class="bmd-label-static" for="name">Notes</label>
                            {!! $errors->has('notes') ? '<span class="text-danger">' . $errors->first('notes') . '</span>' : '' !!}
                            <textarea class="form-control" id="notes" name="notes"> {!! old('notes') ? old('notes') : '' !!}</textarea>
                        </div>
                        <div class="form-group{!! $errors->has('id') ? ' has-error' : '' !!}">
                            {!! $errors->has('id') ? '<span class="text-danger">' . $errors->first('id') . '</span>' : '' !!}
                        </div>
                        <input type="hidden" name="id" value="" id="testId">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-round"><span class="material-icons">save</span>
                            Save</button>
                        <a id="cancelTestButton" style="display:none" href="JavaScript:"
                            class="btn btn-round btn-danger"><span class="material-icons">cancel</span> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="testsDataTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Title</th>
                                    <th>Examination</th>
                                    <th>Marks</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (isset($tests) ? $tests : $examination->tests as $test)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $test->title }}</td>
                                        <td>{{ $test->examination->title }}</td>
                                        <td>{{ $test->outof }}</td>
                                        <td>{{ date_create($test->taken_on)->format('j, F Y') }}</td>
                                        <td>
                                            <a href="JavaScript:"
                                                class="btn btn-fab btn-primary btn-sm btn-round editTestButton"
                                                data-test="{{ $test->toJson() }}"><i class="material-icons">edit</i></a>
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
            $('.editTestButton').click(function() {
                url = '{{ route('admin-eschool-examinations-tests-edit-post') }}'
                $('#testForm').attr('action', url)
                $('#examination').val($(this).data('test').examination_id)
                $('#outof').val($(this).data('test').outof)
                $('#title').val($(this).data('test').title)
                $('#taken_on').val($(this).data('test').taken_on)
                $('#notes').val($(this).data('test').notes)
                $('#testId').val($(this).data('test').id)
                $('#title').focus()
                $('a#cancelTestButton').css('display', 'inline-block')
            });
            $('a#cancelTestButton').click(() => {
                url = '{{ route('admin-eschool-examinations-tests-add-post') }}'
                $('#testForm').attr('action', url)
                $('#examination').val($(this).data('test').examination_id)
                $('#outof').val($(this).data('test').outof)
                $('#title').val($(this).data('test').title)
                $('#taken_on').val($(this).data('test').taken_on)
                $('#notes').val($(this).data('test').notes)
                $('#testId').val($(this).data('test').id)
                $(this).css('display', 'none')
            })

            $('table#testsDataTable').dataTable()
        })
    </script>
@endsection
