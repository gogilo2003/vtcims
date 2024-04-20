<!-- Add/Edit Dialog -->
<div class="modal fade" data-backdrop="static" id="studentDialog" tabindex="-1" role="dialog"
    aria-labelledby="StduentDetails">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="card">
                <form id="studentDetailsForm" method="post"
                    action="{{ $errors->count() && old('id') ? route('admin-eschool-students-add-post') : route('admin-eschool-students-add-post') }}"
                    role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="card-header card-header-primary">
                        <h4>Student Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('id') ? ' has-error' : '' !!}">
                                    <input type="hidden" class="form-control" id="studentId"
                                        name="id"{!! old('id') ? ' value="' . old('id') . '"' : '' !!}>
                                    {!! $errors->has('id') ? '<span class="text-danger">' . $errors->first('id') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{!! $errors->has('surname') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="surname">Surname</label>
                                    <input type="text" class="form-control" id="surname"
                                        name="surname"{!! old('surname') ? ' value="' . old('surname') . '"' : '' !!}>
                                    {!! $errors->has('surname') ? '<span class="text-danger">' . $errors->first('surname') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{!! $errors->has('middle_name') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control" id="middle_name"
                                        name="middle_name"{!! old('middle_name') ? ' value="' . old('middle_name') . '"' : '' !!}>
                                    {!! $errors->has('middle_name') ? '<span class="text-danger">' . $errors->first('middle_name') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{!! $errors->has('first_name') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name"
                                        name="first_name"{!! old('first_name') ? ' value="' . old('first_name') . '"' : '' !!}>
                                    {!! $errors->has('first_name') ? '<span class="text-danger">' . $errors->first('first_name') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{!! $errors->has('phone') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone"
                                        name="phone"{!! old('phone') ? ' value="' . old('phone') . '"' : '' !!}>
                                    {!! $errors->has('phone') ? '<span class="text-danger">' . $errors->first('phone') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="email">Email</label>
                                    <input type="text" class="form-control" id="email"
                                        name="email"{!! old('email') ? ' value="' . old('email') . '"' : '' !!}>
                                    {!! $errors->has('email') ? '<span class="text-danger">' . $errors->first('email') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{!! $errors->has('box_no') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="box_no">Box No</label>
                                    <input type="text" class="form-control" id="box_no"
                                        name="box_no"{!! old('box_no') ? ' value="' . old('box_no') . '"' : '' !!}>
                                    {!! $errors->has('box_no') ? '<span class="text-danger">' . $errors->first('box_no') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{!! $errors->has('post_code') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="post_code">Post Code</label>
                                    <input type="text" class="form-control" id="post_code"
                                        name="post_code"{!! old('post_code') ? ' value="' . old('post_code') . '"' : '' !!}>
                                    {!! $errors->has('post_code') ? '<span class="text-danger">' . $errors->first('post_code') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{!! $errors->has('town') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="town">Town</label>
                                    <input type="text" class="form-control" id="town"
                                        name="town"{!! old('town') ? ' value="' . old('town') . '"' : '' !!}>
                                    {!! $errors->has('town') ? '<span class="text-danger">' . $errors->first('town') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('physical_address') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="physical_address">Physical Address</label>
                                    <textarea class="form-control" id="physical_address" name="physical_address">{!! old('physical_address') ? old('physical_address') : '' !!}</textarea>
                                    {!! $errors->has('physical_address')
                                        ? '<span class="text-danger">' . $errors->first('physical_address') . '</span>'
                                        : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group is-filled is-focused {!! $errors->has('date_of_birth') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-static" for="date_of_birth">Date of Birth</label>
                                    <input type="text" class="form-control datepicker" id="date_of_birth"
                                        name="date_of_birth"{!! old('date_of_birth') ? ' value="' . old('date_of_birth') . '"' : '' !!}>
                                    {!! $errors->has('date_of_birth')
                                        ? '<span class="text-danger">' . $errors->first('date_of_birth') . '</span>'
                                        : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{!! $errors->has('birth_certificate_no') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="birth_certificate_no">Birth Certificate
                                        No</label>
                                    <input type="text" class="form-control" id="birth_certificate_no"
                                        name="birth_certificate_no"{!! old('birth_certificate_no') ? ' value="' . old('birth_certificate_no') . '"' : '' !!}>
                                    {!! $errors->has('birth_certificate_no')
                                        ? '<span class="text-danger">' . $errors->first('birth_certificate_no') . '</span>'
                                        : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{!! $errors->has('idno') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="idno">IDNo</label>
                                    <input type="text" class="form-control" id="idno"
                                        name="idno"{!! old('idno') ? ' value="' . old('idno') . '"' : '' !!}>
                                    {!! $errors->has('idno') ? '<span class="text-danger">' . $errors->first('idno') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('gender') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-static" for="gender">Gender</label>
                                    <select data-live-search="true" class="form-control selectpicker"
                                        data-style="btn btn-link" id="gender" name="gender"
                                        {!! old('gender') ? ' value="' . old('gender') . '"' : '' !!}>
                                        <option value="0">Male</option>
                                        <option value="1">Female</option>
                                    </select>
                                    {!! $errors->has('gender') ? '<span class="text-danger">' . $errors->first('gender') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('date_of_admission') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-floating" for="date_of_admission">Date of
                                        Admission</label>
                                    <input type="text" class="form-control datepicker" id="date_of_admission"
                                        name="date_of_admission"{!! old('date_of_admission') ? ' value="' . old('date_of_admission') . '"' : '' !!}>
                                    {!! $errors->has('date_of_admission')
                                        ? '<span class="text-danger">' . $errors->first('date_of_admission') . '</span>'
                                        : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('intake') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-static" for="intake">Intake</label>
                                    <select data-live-search="true" class="form-control selectpicker"
                                        data-style="btn btn-link" id="intake" name="intake"
                                        {!! old('intake') ? old('intake') : '' !!}>
                                        @foreach (\Ogilo\Eschool\Models\Intake::orderBy('name', 'DESC')->get() as $intake)
                                            <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('intake') ? '<span class="text-danger">' . $errors->first('intake') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('program') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-static" for="program">Program</label>
                                    <select data-live-search="true" class="form-control selectpicker"
                                        data-style="btn btn-link" id="program"
                                        name="program"{!! old('program') ? ' value="' . old('program') . '"' : '' !!}>
                                        @foreach (\Ogilo\Eschool\Models\Program::all() as $program)
                                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('program') ? '<span class="text-danger">' . $errors->first('program') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('sponsor') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-static" for="sponsor">Sponsor</label>
                                    <select data-live-search="true" class="form-control selectpicker"
                                        data-style="btn btn-link" id="sponsor"
                                        name="sponsor"{!! old('sponsor') ? ' value="' . old('sponsor') . '"' : '' !!}>
                                        @foreach (\Ogilo\Eschool\Models\Sponsor::all() as $sponsor)
                                            <option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('sponsor') ? '<span class="text-danger">' . $errors->first('sponsor') . '</span>' : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('student_role') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-static" for="student_role">Student Role</label>
                                    <select data-live-search="true" class="form-control selectpicker"
                                        data-style="btn btn-link" id="student_role"
                                        name="student_role"{!! old('student_role') ? ' value="' . old('student_role') . '"' : '' !!}>
                                        @foreach (\Ogilo\Eschool\Models\StudentRole::all() as $student_role)
                                            <option value="{{ $student_role->id }}">{{ $student_role->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('student_role')
                                        ? '<span class="text-danger">' . $errors->first('student_role') . '</span>'
                                        : '' !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('status') ? ' has-error' : '' !!}">
                                    <label class="bmd-label-static" for="status">Status</label>
                                    <select data-live-search="true" class="form-control selectpicker"
                                        data-style="btn btn-link" id="status"
                                        name="status"{!! old('status') ? ' value="' . old('status') . '"' : '' !!}>
                                        @include('components.students.status')
                                    </select>
                                    {!! $errors->has('status') ? '<span class="text-danger">' . $errors->first('status') . '</span>' : '' !!}
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-round"><span
                                class="material-icons">save</span> Save</button>
                        <button type="reset" id="cancelStudedntButton" class="btn btn-danger btn-round"><i
                                class="material-icons">cancel</i> Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
