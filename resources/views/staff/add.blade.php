<!-- Modal -->
<div class="modal fade" id="staffDialog" tabindex="-1" role="dialog" aria-labelledby="StaffDialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4>Staff Details</h4>
                </div>
            </div>
        </div>
    </div>
</div>

@extends('admin::layout.main')

@section('title')
    E-School::Add Staff
@endsection

@section('page_title')
    <i class="material-icons">person_add</i> E-School::Add Staff
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('students.sidebar')
@endsection

@section('content')
    <form method="post" action="{{ route('admin-eschool-staff-add-post') }}" role="form" accept-charset="UTF-8"
        enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-3">
                <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                    <div class="fileinput-new thumbnail img-raised">
                        <img src="{{ asset(config('admin.path_prefix') . '/vendor/admin/img/person_8x10.png') }}"
                            alt="..." class="img-fluid">
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail img-raised"></div>
                    <div>
                        <span class="btn btn-raised btn-round btn-default btn-file">
                            <span class="fileinput-new">Select image</span>
                            <span class="fileinput-exists">Change</span>
                            <input type="file" name="photo" />
                        </span>
                        <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i
                                class="fa fa-times"></i> Remove</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('idno') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="idno">IDNo.</label>
                            <input type="text" class="form-control" id="idno" name="idno"
                                {!! old('idno') ? ' value="' . old('idno') . '"' : '' !!}>
                            {!! $errors->has('idno') ? '<span class="text-danger">' . $errors->first('idno') . '</span>' : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('pfno') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="pfno">Personal File Number</label>
                            <input type="text" class="form-control" id="pfno" name="pfno"
                                {!! old('pfno') ? ' value="' . old('pfno') . '"' : '' !!}>
                            {!! $errors->has('pfno') ? '<span class="text-danger">' . $errors->first('pfno') . '</span>' : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('manno') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="manno">ManNo</label>
                            <input type="text" class="form-control" id="manno" name="manno"
                                {!! old('manno') ? ' value="' . old('manno') . '"' : '' !!}>
                            {!! $errors->has('manno') ? '<span class="text-danger">' . $errors->first('manno') . '</span>' : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('surname') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="surname">Surname</label>
                            <input type="text" class="form-control" id="surname" name="surname"
                                {!! old('surname') ? ' value="' . old('surname') . '"' : '' !!}>
                            {!! $errors->has('surname')
                                ? '<span
                                                            class="text-danger">' .
                                    $errors->first('surname') .
                                    '</span>'
                                : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('first_name') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                {!! old('first_name') ? ' value="' . old('first_name') . '"' : '' !!}>
                            {!! $errors->has('first_name')
                                ? '<span
                                                            class="text-danger">' .
                                    $errors->first('first_name') .
                                    '</span>'
                                : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('middle_name') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="middle_name">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name"
                                {!! old('middle_name') ? ' value="' . old('middle_name') . '"' : '' !!}>
                            {!! $errors->has('middle_name')
                                ? '<span
                                                            class="text-danger">' .
                                    $errors->first('middle_name') .
                                    '</span>'
                                : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('box_no') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="box_no">Box No</label>
                            <input type="text" class="form-control" id="box_no" name="box_no"
                                {!! old('box_no') ? ' value="' . old('box_no') . '"' : '' !!}>
                            {!! $errors->has('box_no') ? '<span class="text-danger">' . $errors->first('box_no') . '</span>' : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('post_code') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="post_code">Post Code</label>
                            <input type="text" class="form-control" id="post_code" name="post_code"
                                {!! old('post_code') ? ' value="' . old('post_code') . '"' : '' !!}>
                            {!! $errors->has('post_code')
                                ? '<span
                                                            class="text-danger">' .
                                    $errors->first('post_code') .
                                    '</span>'
                                : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('town') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="town">Town</label>
                            <input type="text" class="form-control" id="town" name="town"
                                {!! old('town') ? ' value="' . old('town') . '"' : '' !!}>
                            {!! $errors->has('town') ? '<span class="text-danger">' . $errors->first('town') . '</span>' : '' !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                {!! old('email') ? ' value="' . old('email') . '"' : '' !!}>
                            {!! $errors->has('email') ? '<span class="text-danger">' . $errors->first('email') . '</span>' : '' !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group{!! $errors->has('phone') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                {!! old('phone') ? ' value="' . old('phone') . '"' : '' !!}>
                            {!! $errors->has('phone') ? '<span class="text-danger">' . $errors->first('phone') . '</span>' : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('gender') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="gender">Gender</label>
                            <select class="form-control selectpicker" id="gender" name="gender"
                                data-style="btn btn-link" {!! old('gender') ? ' value="' . old('gender') . '"' : '' !!}>
                                @foreach (['Male', 'Female'] as $element)
                                    <option>{{ $element }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('employer') ? '<span class="text-danger">' . $errors->first('employer') . '</span>' : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('employer') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="employer">Employer</label>
                            <select class="form-control selectpicker" id="employer" name="employer"
                                data-style="btn btn-link" {!! old('employer') ? ' value="' . old('employer') . '"' : '' !!}>
                                @foreach (['Nairobi City County', 'Board of Governors'] as $element)
                                    <option>{{ $element }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('employer') ? '<span class="text-danger">' . $errors->first('employer') . '</span>' : '' !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{!! $errors->has('staff_role_id') ? ' has-error' : '' !!}">
                            <label class="bmd-label-floating" for="staff_role_id">Staff Role</label>
                            <select class="form-control selectpicker" id="staff_role_id" name="staff_role_id"
                                data-style="btn btn-link" {!! old('staff_role_id') ? ' value="' . old('staff_role_id') . '"' : '' !!}>
                                @foreach (\Ogilo\Eschool\Models\StaffRole::all() as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('staff_role_id')
                                ? '<span class="text-danger">' . $errors->first('staff_role_id') . '</span>'
                                : '' !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 offset-md-3">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-primary"><span class="material-icons">add</span> Save new
                    staff</button>
            </div>
        </div>
    </form>
@endsection
