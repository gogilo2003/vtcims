@extends('admin::layout.main')

@section('title')
    E-School::Staff Roles
@endsection

@section('page_title')
    <i class="fa fa-user-o"></i> Staff Roles
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::students.sidebar')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <h3>Enter details of new Role here</h3>
            <form method="post" action="{{route('admin-eschool-staff_roles-add-post')}}" role="form"
                accept-charset="UTF-8" enctype="multipart/form-data">
                <div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
                    <label class="bmd-label-floating" for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" {!!
                        ((old('name')) ? ' value="' .old('name').'"' : '' ) !!}>
                    {!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
                </div>
                <div class="form-group{!! $errors->has('description') ? ' has-error':'' !!}">
                    <label class="bmd-label-floating" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description"
                        >{!! ((old('description')) ? old('description') : '') !!}</textarea>
                    {!! $errors->has('description') ? '<span
                        class="text-danger">'.$errors->first('description').'</span>' : '' !!}
                </div>
                <input type="hidden" name="_token" value="{{csrf_token()}}">

                <button type="submit" class="btn btn-primary">
                    <span class="material-icons">add</span>
                    Add Role
                </button>
            </form>
        </div>
        <div class="col-md-8">
            <table class="table" id="staffRolesDataTable">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff_roles as $staff_role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $staff_role->name }} </td>
                        <td>
                            <a href="{{ route('admin-eschool-staff_roles-edit',$staff_role->id) }}" class="btn btn-fab btn-round btn-primary btn-sm" title="Edit {{ $staff_role->name }}"><i class="material-icons">edit</i></a>
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
        $(document).ready(function(){
            $('#staffRolesDataTable').dataTable()
        })
    </script>
@endsection