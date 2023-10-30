@extends('admin::layout.main')

@section('title')
    E-School::Departments
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Departments
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::departments.sidebar')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ ($errors->count() && old('id')) ? route('admin-eschool-departments-edit-post') : route('admin-eschool-departments-add-post')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data" id="departmentDataForm">
                        <div class="form-group{!! $errors->has('code') ? ' has-error':'' !!}">
                            <label class="bmd-label-floating" for="code">Code</label>
                            <input type="text" class="form-control" id="code" name="code"{!! ((old('code')) ? ' value="'.old('code').'"' : '') !!}>
                            {!! $errors->has('code') ? '<span class="text-danger">'.$errors->first('code').'</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
                            <label class="bmd-label-floating" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"{!! ((old('name')) ? ' value="'.old('name').'"' : '') !!}>
                            {!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
                        </div>
                        <div class="form-group{!! $errors->has('hod') ? ' has-error':'' !!}">
                            <label class="bmd-label-static" for="hod">Head of Department</label>
                            <select data-live-search="true" data-style="btn btn-link" class="form-control selectpicker" id="hod" name="hod"{!! ((old('hod')) ? ' value="'.old('hod').'"' : '') !!}>
                                @foreach (\Ogilo\Eschool\Models\Staff::orderBy('surname')->get() as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('hod') ? '<span class="text-danger">'.$errors->first('hod').'</span>' : '' !!}
                        </div>
                        <input type="hidden" name="id" value="{{ old('id') ? old('id') : '' }}" id="editDepartmentId">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-primary"><span class="material-icons">save</span>  Save</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="departmentsDataTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Courses</th>
                                    <th>HOD</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $department->code }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>{{ $department->courses->count() }}</td>
                                    <td>{{ $department->hod->name }}</td>
                                    <td>
                                        <a href="javascript:" data-department="{{ $department->toJson() }}" class="btn btn-fab btn-primary btn-round btn-sm editDepartment"><i class="material-icons">edit</i></a>
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
        $(document).ready(function(){
            $('.editDepartment').click(function(){
                let department = $(this).data('department')
                let url = '{{ route('admin-eschool-departments-edit-post') }}'
                
                $('input#editDepartmentId').val(department.id)
                $('input#code').val(department.code)
                $('input#name').val(department.name)
                $('select#hod').selectpicker('val',department.staff_id)
                $('#departmentDataForm').attr('action',url)
                $('input#code').focus()
            })
            $('#departmentsDataTable').dataTable();
        })
    </script>
@endsection