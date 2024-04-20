@extends('admin::layout.main')

@section('title')
    E-School::View ({{ $student->name }})
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> {{ $student->name }}
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('students.sidebar')
@endsection

@section('content')
    <table class="table table-no-border">
        <tr>
            <td width="20%" style="vertical-align: top;">
                <img src="{{ file_exists(public_path('images/students/' . $student->photo)) && $student->photo ? asset(config('admin.path_prefix') . '/images/students/' . $student->photo) : asset(config('admin.path_prefix') . '/vendor/admin/img/person_8x10.png') }}"
                    alt="" class="img-fluid">
            </td>
            <td width="80%">
                <table class="table text-uppercase table-bordered">
                    <tr>
                        <th>Name</th>
                        <th>Admission No</th>
                        <th>Class/Intake</th>
                        <th>Course</th>
                    </tr>
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->admission_no }}</td>
                        <td>{{ $student->intake->name }}</td>
                        <td>{{ $student->intake->course->name }}</td>
                    </tr>
                    <tr>
                        <th>Idno</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                    </tr>
                    <tr>
                        <td>{{ $student->idno }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ $student->gender ? 'Female' : 'Male' }}</td>
                    </tr>
                    <tr>
                        <th>Postal Address</th>
                        <th>Date of Birth</th>
                        <th>Birth Certificate Number</th>
                        <th>Date of Admission</th>
                    </tr>
                    <tr>
                        <td>{{ $student->address }}</td>
                        <td>{{ $student->date_of_birth }}</td>
                        <td>{{ $student->birth_cert_no }}</td>
                        <td>{{ $student->date_of_admission }}</td>
                    </tr>
                    <tr>
                        <th>Sponsor</th>
                        <th>Program</th>
                        <th colspan="2">Role</th>
                    </tr>
                    <tr>
                        <td>{{ $student->sponsor->name }}</td>
                        <td>{{ $student->program->name }}</td>
                        <td colspan="2">{{ $student->role->name }}</td>
                    </tr>
                    <tr>
                        <th colspan="4">Physical Address</th>
                    </tr>
                    <tr>
                        <td colspan="4">{!! $student->physical_address ? $student->physical_address : '&nbsp;' !!}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <h4 class="text-uppercase">Leaveouts issued to {{ $student->name }}</h4>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th></th>
                <th>Date Issued</th>
                <th>Issued By</th>
                <th>Valid Until</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($student->leaveouts as $leaveout)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $leaveout->created_at->format('j-M-Y') }}</td>
                    <td>{{ $leaveout->staff->min_name }}</td>
                    <td>{{ date_create($leaveout->valid_until)->format('j-M-Y') }}</td>
                    <td>
                        <a href="{{ route('admin-eschool-students-leaveout', $leaveout->id) }}"
                            class="btn btn-primary btn-fab btn-round btn-sm"><i
                                class="material-icons">cloud_download</i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        <a href="{{ route('admin-eschool-students-download', $student->id) }}" class="btn btn-round btn-primary"><i
                class="material-icons">cloud_download</i> Download</a>
        <a href="{{ route('admin-eschool-students') }}" class="btn btn-round btn-primary"><i
                class="material-icons">list</i> Back to students list</a>
    </div>
@endsection
