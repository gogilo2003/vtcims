@extends('admin::layout.main')

@section('title')
    E-School::Programs
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Programs
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('programs.sidebar')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form id="programForm" method="post"
                        action="{{ $errors->count() && old('id') ? route('admin-eschool-programs-edit-post') : route('admin-eschool-programs-add-post') }}"
                        role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                        <div class="form-group{!! $errors->has('program_name') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="program_name">Program name</label>
                            <input type="text" class="form-control" id="program_name"
                                name="program_name"{!! old('program_name') ? ' value="' . old('program_name') . '"' : '' !!}>
                            {!! $errors->has('program_name')
                                ? '<span class="text-danger">' . $errors->first('program_name') . '</span>'
                                : '' !!}
                        </div>
                        <input type="hidden" name="id" value="" id="programId">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-primary btn-round"><span class="material-icons">save</span>
                            Save</button>
                        <a href="JavaScript:" id="cancelProgramEdit" class="btn btn-danger btn-round"
                            style="display: none"><i class="material-icons">cancel</i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Program Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($programs as $program)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $program->name }}</td>
                                        <td>
                                            <a href="JavaScript:"
                                                class="btn btn-primary btn-fab btn-round btn-sm editProgramButton"
                                                data-program="{{ $program->toJson() }}"><i
                                                    class="material-icons">edit</i></a>
                                            <a href="JavaScript:"
                                                class="btn btn-fab btn-round btn-danger btn-sm deleteProgramButton"
                                                data-id="{{ $program->id }}" data-name="{{ $program->name }}"><i
                                                    class="material-icons">delete</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <form id="deleteProgramForm" method="post" action="{{ route('admin-eschool-programs-delete') }}"
                            role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="deleteProgramId">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts_bottom')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.editProgramButton').click(function() {
                url = '{{ route('admin-eschool-programs-edit-post') }}'
                $('#programForm').attr('action', url)

                $('#program_name').val($(this).data('program').name)
                $('#programId').val($(this).data('program').id)

                $('#cancelProgramEdit').attr('style', 'display: inline-block')

                $('#program_name').focus()
            })

            $('#cancelProgramEdit').click(function() {
                url = '{{ route('admin-eschool-programs-add-post') }}'
                $('#programForm').attr('action', url)

                $('#program_name').val(null)
                $('#programId').val(null)

                $('#cancelProgramEdit').attr('style', 'display: none')
                $('#program_name').focus()
            })

            $('.deleteProgramButton').click(function() {
                $('#deleteProgramId').val($(this).data('id'))
                name = $(this).data('name')
                if (confirm("Do you want delete " + name)) {
                    $('#deleteProgramForm').submit()
                }

            })
        })
    </script>
@endsection
