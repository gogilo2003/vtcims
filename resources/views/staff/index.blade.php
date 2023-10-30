@extends('admin::layout.main')

@section('title')
    E-School::Staff
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> E-School::Staff
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::staff.sidebar')
@endsection

@section('content')
    <div class="btn-group">
        <a href="{{ route('admin-eschool-staff-add') }}" class="btn btn-primary btn-round"><i class="material-icons">add</i>
            Add Staff</a>
        <a href="{{ route('admin-eschool-staff-download') }}" class="btn btn-info btn-round"><i
                class="material-icons">cloud_download</i> Download Staff List</a>
        @php
            $date = week();
        @endphp
        <a href="JavaScript:" class="btn btn-success btn-round" id="attendanceRegister"><i
                class="material-icons">cloud_download</i> Attendance Register</a>
    </div>
    <table class="table" id="staffDataTable">
        <thead>
            <tr>
                <th></th>
                <th>Photo</th>
                <th>IdNo</th>
                <th>Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Employer</th>
                <th>Gender</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff as $staf)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if ($staf->photo)
                            <img class="staff-avatar"
                                src="{{ asset(config('admin.path_prefix') . '/images/staff/' . $staf->photo) }}"
                                style="max-height: 64px; border-radius: 32px">
                        @else
                            <div class="staff-avatar"
                                style="overflow: hidden; color:linear-gradient(60deg, #9ccc65, #7cb342);; line-height: 64px; height: 64px; width:64px; background-color:#ccc; border-radius: 32px; justify-content: center; align-items: center; display: flex; font-size: 20pt">
                                @php
                                    print strtoupper(substr($staf->surname, 1, 1) . substr($staf->first_name, 1, 1));
                                @endphp
                            </div>
                        @endif
                    </td>
                    <td>{{ $staf->idno }}</td>
                    <td>{{ $staf->name }}</td>
                    <td>{{ $staf->role->name }}</td>
                    <td>{{ $staf->email }}</td>
                    <td>{{ $staf->phone }}</td>
                    <td>{{ $staf->employer }}</td>
                    <td>{{ $staf->gender }}</td>
                    <td>
                        <a href="{{ route('admin-eschool-staff-edit', $staf->id) }}"
                            class="btn btn-fab btn-round btn-primary btn-sm"><i class="material-icons">edit</i></a>
                        <a href="{{ route('admin-eschool-staff-view', $staf->id) }}"
                            class="btn btn-fab btn-info btn-sm btn-round"><i class="material-icons">art_track</i></a>
                        <a href="{{ route('admin-eschool-staff-download', $staf->id) }}"
                            class="btn btn-blue btn-round btn-fab btn-sm">
                            <i class="material-icons">cloud_download</i>
                        </a>
                        <a href="{{ route('admin-eschool-staff-delete', $staf->id) }}"
                            class="btn btn-fab btn-danger btn-sm btn-round">
                            <i class="material-icons">delete</i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('eschool::staff.attendance')
@endsection

@section('scripts_bottom')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#staffDataTable').dataTable()
            $('#attendanceRegister').click(function() {
                $('#attendanceDialog').modal('show')
            })
            $('#attendanceDialog').on('bs.model.close', function() {
                console.log('closed')
            })
        })
    </script>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/eschool/css/eschool.css') }}">
@endpush
