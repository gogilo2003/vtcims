@extends('admin::layout.main')

@section('title')
    E-School::Students
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Students
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::students.sidebar')
@endsection

@section('content')
    <div class="btn-group">
        <a href="javascript:" class="btn btn-round btn-primary addStudentButton"><i class="material-icons">person_add</i><span
                class="d-none d-md-inline"> Add Student</span></a>
        <a href="javascript:" id="showAttendanceButton" class="btn btn-round btn-success"><i
                class="material-icons">list</i><span class="d-none d-md-inline"> Class Attendance</span></a>
        <a href="javascript:" id="showEnrolmentButton" class="btn btn-round btn-warning"><i
                class="material-icons">cloud_download</i><span class="d-none d-md-inline"> Enrolment</span></a>
        <a href="javascript:" id="filterDownloadButton" class="btn btn-round btn-info"><i
                class="material-icons">cloud_download</i><span class="d-none d-md-inline"> Download Students List</span></a>
        <!-- <a href="javascript:" id="uploadStudentsButton" class="btn btn-round btn-primary"><i class="material-icons">cloud_upload</i> Class Attendance</a> -->
    </div>
    <hr>
    <div class="form-group{!! $errors->has('photo') ? ' has-error' : '' !!}">
        {!! $errors->has('photo') ? '<span class="text-danger">' . $errors->first('photo') . '</span>' : '' !!}
    </div>
    <div class="table-responsive">
        <table id="studentsTable" class="table table-striped table-hover">
            <thead class="thead-light">
                <tr>
                    <th></th>
                    <th class="d-block d-md-table-cell">Admission No</th>
                    <th class="d-block d-md-table-cell">Name</th>
                    <th class="d-none d-md-table-cell">Class</th>
                    <th class="d-none d-md-table-cell">Course</th>
                    <th class="d-none d-md-table-cell">Status</th>
                    <th class="d-block d-md-table-cell" h>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="d-block d-md-table-cell">{{ $student->admission_no }}</td>
                        <td class="d-block d-md-table-cell">{{ $student->name }}</td>
                        <td class="d-none d-md-table-cell">{{ $student->intake->name }}</td>
                        <td class="d-none d-md-table-cell">{{ $student->intake->course->name }}</td>
                        <td class="d-none d-md-table-cell">
                            <div class="form-group">
                                <select id="my-select" class="custom-select change-status"
                                    admission_no="{{ $student->id }}">
                                    @include('eschool::components.students.status', [
                                        'selected' => $student->status,
                                    ])
                                </select>
                            </div>
                        </td>
                        <td class="d-block d-md-table-cell">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle btn-round" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="material-icons">settings</i>
                                    Tools
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="JavaScript:" class="dropdown-item editStudentButton"
                                        data-student="{{ $student->toJson() }}">
                                        <i class="material-icons">edit</i>&nbsp;&nbsp;
                                        Edit </a>
                                    <a href="{{ route('admin-eschool-students-view', $student->id) }}"
                                        class="dropdown-item">
                                        <i class="material-icons">pageview</i>&nbsp;&nbsp;
                                        View Details
                                    </a>
                                    <a href="JavaScript:" data-id="{{ $student->id }}"
                                        class="dropdown-item uploadPhotoButton">
                                        <i class="material-icons">image</i>&nbsp;&nbsp;
                                        Upload Picture
                                    </a>
                                    <a href="{{ route('admin-eschool-students-download', $student->id) }}"
                                        class="dropdown-item">
                                        <i class="material-icons">cloud_download</i>&nbsp;&nbsp;
                                        Download Details
                                    </a>
                                    <a href="JavaScript:" data-id="{{ $student->id }}"
                                        data-name="{{ $student->name }}" class="dropdown-item leaveOutButton">
                                        <i class="material-icons">assignment</i>&nbsp;&nbsp;
                                        Leave Out
                                    </a>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('eschool::students.add')
    @include('eschool::students.attendance')
    @include('eschool::students.photo')
    @include('eschool::students.list')
    @include('eschool::students.enrolment')
    @include('eschool::students.leaveout')
@endsection

@section('scripts_bottom')
    <script type="text/javascript">
        document.querySelectorAll('select.change-status').forEach(function(item) {
            item.addEventListener('change', function() {
                let data = {
                    'admission_no': item.getAttribute('admission_no'),
                    'status': item.value,
                    api_token: '{{ api_token() }}'
                }
                $.post('{{ route('api-eschool-students-status') }}', data)
            })
        })
        $(document).ready(function() {

            $('.addStudentButton').click(function() {
                $('#studentDialog').modal('show')

                $('#surname').focus()
            })

            $('#studentDialog').on('shown.bs.modal', function() {
                $('#studentDialog #surname').focus()
            })

            $('.editStudentButton').click(function() {
                url = '{{ route('admin-eschool-students-edit-post') }}'
                $('#studentDetailsForm').attr('action', url)

                $('#studentDialog #studentId').val($(this).data('student').id)
                $('#studentDialog #surname').val($(this).data('student').surname)
                $('#studentDialog #first_name').val($(this).data('student').first_name)
                $('#studentDialog #middle_name').val($(this).data('student').middle_name)
                $('#studentDialog #phone').val($(this).data('student').phone)
                $('#studentDialog #email').val($(this).data('student').email)
                $('#studentDialog #box_no').val($(this).data('student').box_no)
                $('#studentDialog #post_code').val($(this).data('student').post_code)
                $('#studentDialog #town').val($(this).data('student').town)
                $('#studentDialog #date_of_birth').val($(this).data('student').date_of_birth)
                $('#studentDialog #birth_certificate_no').val($(this).data('student').birth_cert_no)
                $('#studentDialog #idno').val($(this).data('student').idno)
                $('#studentDialog #gender').selectpicker('val', $(this).data('student').gender)
                $('#studentDialog #date_of_admission').val($(this).data('student').date_of_admission)
                $('#studentDialog #intake').selectpicker('val', $(this).data('student').intake_id)
                $('#studentDialog #program').selectpicker('val', $(this).data('student').program_id)
                $('#studentDialog #sponsor').selectpicker('val', $(this).data('student').sponsor_id)
                $('#studentDialog #student_role').selectpicker('val', $(this).data('student')
                    .student_role_id)

                $('#studentDialog').modal('show')
            })

            $('#cancelStudedntButton').click(function() {
                url = '{{ route('admin-eschool-students-add-post') }}'
                $('#studentDialog #studentDetailsForm').attr('action', url)

                $('#studentDialog #studentId').val(null)
                $('#studentDialog #surname').val(null)
                $('#studentDialog #first_name').val(null)
                $('#studentDialog #middle_name').val(null)
                $('#studentDialog #phone').val(null)
                $('#studentDialog #email').val(null)
                $('#studentDialog #box_no').val(null)
                $('#studentDialog #post_code').val(null)
                $('#studentDialog #town').val(null)
                $('#studentDialog #date_of_birth').val(null)
                $('#studentDialog #birth_certificate_no').val(null)
                $('#studentDialog #idno').val(null)
                $('#studentDialog #gender').selectpicker('val', null)
                $('#studentDialog #date_of_admission').val(null)
                $('#studentDialog #intake').selectpicker('val', null)
                $('#studentDialog #program').selectpicker('val', null)
                $('#studentDialog #sponsor').selectpicker('val', null)
                $('#studentDialog #student_role').selectpicker('val', null)

                $('#studentDialog').modal('hide')
            })

            $('#showAttendanceButton').click(function() {
                $('#attendanceDialog').modal('show')
            })
            $('#showEnrolmentButton').click(function() {
                $('#filterDownloadEnrolmentDialog').modal('show')
            })

            $('#attendanceDetailsForm #class').selectpicker('val', null)

            $('#attendanceDetailsForm #class').change(function() {
                let intake = $(this).val()
                $.post('/api/eschool/intakes/subjects', {
                    intake,
                    api_token: '{{ api_token() }}'
                }).then(function(response) {
                    // console.log(response)
                    $('#attendanceDetailsForm #subject').html("")
                    response.forEach(function(item, index) {
                        $('#attendanceDetailsForm #subject').append('<option value="' + item
                            .id + '">' + item.name + '</option>')
                    })

                    $('#attendanceDetailsForm #subject').selectpicker('refresh')
                    $('#attendanceDetailsForm #subject').selectpicker('val', null)
                }, function(error) {
                    console.log('Error')
                    console.log(error.responseText)
                })
            })

            $('#filterDownloadEnrolmentDialog').on('show.bs.modal', function() {
                $('#filterDownloadEnrolmentDialog #department').selectpicker('val', null)
                $('#filterDownloadEnrolmentDialog #gender').selectpicker('val', null)
                $('#filterDownloadEnrolmentDialog #sponsor').selectpicker('val', null)
                $('#filterDownloadEnrolmentDialog #program').selectpicker('val', null)
                $('#filterDownloadEnrolmentDialog #before_after').selectpicker('val', null)
                $('#filterDownloadEnrolmentDialog #year').selectpicker('val', null)
            })

            $('#filterDownloadDialog').on('show.bs.modal', function() {
                $('#filterDownloadDialog #department').selectpicker('val', null)
                $('#filterDownloadDialog #course').selectpicker('val', null)
                $('#filterDownloadDialog #class').selectpicker('val', null)
                $('#filterDownloadDialog #gender').selectpicker('val', null)
                $('#filterDownloadDialog #sponsor').selectpicker('val', null)
                $('#filterDownloadDialog #program').selectpicker('val', null)
                $('#filterDownloadDialog #role').selectpicker('val', null)
                $('#filterDownloadDialog #before_after').selectpicker('val', null)
            })

            $('#filterDownloadDialog #department').change(function() {
                let department = $(this).val()
                // console.log('Department: '+department)
                if (department != '') {
                    $.post('{{ route('api-eschool-departments-courses') }}', {
                        department,
                        api_token: '{{ api_token() }}'
                    }).then(function(response) {
                        // console.log(response)
                        $('#filterDownloadDialog #course').html('')
                        response.forEach(function(course, index) {
                            $('#filterDownloadDialog #course').append('<option value="' +
                                course.id + '">' + course.name + '</option>')
                        })
                        $('#filterDownloadDialog #course').selectpicker('refresh')
                        $('#filterDownloadDialog #course').selectpicker('val', null)
                    }, function(error) {
                        console.log(error.responseText)
                    })
                    $.post('{{ route('api-eschool-departments-intakes') }}', {
                        department,
                        api_token: '{{ api_token() }}'
                    }).then(function(response) {
                        $('#filterDownloadDialog #intake').html('')
                        response.forEach(function(intake, index) {
                            $('#filterDownloadDialog #intake').append('<option value="' +
                                intake.id + '">' + intake.name + '</option>')
                        })
                        $('#filterDownloadDialog #intake').selectpicker('refresh')
                        $('#filterDownloadDialog #intake').selectpicker('val', null)
                    })
                }
            })

            $('#filterDownloadDialog #course').change(function() {
                let course = $(this).val()
                if (course != '') {
                    $.post('{{ route('api-eschool-courses-intakes') }}', {
                        course,
                        api_token: '{{ api_token() }}'
                    }).then(function(response) {
                        // console.log(response)
                        $('#filterDownloadDialog #intake').html('')
                        response.forEach(function(intake, index) {
                            $('#filterDownloadDialog #intake').append('<option value="' +
                                intake.id + '">' + intake.name + '</option>')
                        })
                        $('#filterDownloadDialog #intake').selectpicker('refresh')
                        $('#filterDownloadDialog #intake').selectpicker('val', null)
                    }, function(error) {
                        console.log(error.responseText)
                    })
                }
            })

            $('#filterResetButton').click(function() {
                $('#filterDownloadDialog #department').selectpicker('val', null)
                $('#filterDownloadDialog #course').selectpicker('val', null)
                $('#filterDownloadDialog #intake').selectpicker('val', null)
                $('#filterDownloadDialog #gender').selectpicker('val', null)
                $('#filterDownloadDialog #sponsor').selectpicker('val', null)
                $('#filterDownloadDialog #program').selectpicker('val', null)
                $('#filterDownloadDialog #role').selectpicker('val', null)
                $('#filterDownloadDialog #before_after').selectpicker('val', null)
                $('#filterDownloadDialog #age').val(null)
                $('#filterDownloadDialog #date_of_birth').val(null)
            })

            $('#filterDownloadButton').click(function() {
                $('#filterDownloadDialog').modal('show')
            })

            $('#uploadStudentsButton').click(function() {
                $('#uploadStudentsDialog').modal('show')
            })

            $('.uploadPhotoButton').click(function() {
                $('#uploadPhotoDialog').modal('show')
                $('#uploadPhotoId').val($(this).data('id'))
            })

            $('.leaveOutButton').click(function() {
                $('#leaveOutDialog').modal('show')
                $('#leaveOutId').val($(this).data('id'))
                $('#leaveOutForm #name').val($(this).data('name'))
            })

            $('#leaveOutDialog').on('shown.bs.modal', function() {
                $('#leaveOutDialog #name').focus()
            })

            $('#studentsTable').dataTable()
        })
    </script>
@endsection
