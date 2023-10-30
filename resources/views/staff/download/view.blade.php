@extends('eschool::layout.pdf')

@section('content')
<table class="table table-sm table-striped table-bordered">
    <tr>
        <td rowspan="14" style="width: 200px; position: relative; vertical-align: top;">
            <img src="{{ $staff->photo ? asset(config('admin.path_prefix').'/images/staff/'.$staff->photo) : asset(config('admin.path_prefix').'/vendor/admin/img/person_8x10.png') }}" alt="" style="width:100%">
        </td>
    </tr>
    <tr>
        <td class="text-right" style="max-width: 20%"><b>Name:</b></td>
        <td><em>{{ $staff->name }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Position:</b></td>
        <td><em>{{ $staff->role->name }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Idno:</b></td>
        <td><em>{{ $staff->idno }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>PF/No:</b></td>
        <td><em>{{ $staff->pfno }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Manno:</b></td>
        <td><em>{{ $staff->manno }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Box No:</b></td>
        <td><em>{{ $staff->box_no }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Post Code:</b></td>
        <td colspan="2"><em>{{ $staff->post_code }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Town:</b></td>
        <td colspan="2"><em>{{ $staff->town }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Email:</b></td>
        <td colspan="2"><em>{{ $staff->email }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Phone No:</b></td>
        <td colspan="2"><em>{{ $staff->phone }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Employer:</b></td>
        <td colspan="2"><em>{{ $staff->employer }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Gender:</b></td>
        <td colspan="2"><em>{{ $staff->gender }}</em></td>
    </tr>
    <tr>
        <td class="text-right"><b>Last Updated:</b></td>
        <td colspan="2"><em>{!! $staff->updated_at->format('j\<\s\u\p\>S\<\/\s\u\p\>, F Y h:i:s A') !!}</em></td>
    </tr>
    <tr>
        <td colspan="3">
            <table class="table table-sm table-striped text-uppercase">
                <thead class="thead-light">
                    <tr>
                        <th colspan="4"><h3>Lessons</h3></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Course</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($intake_subjects as $key => $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->subject->name }}</td>
                            <td>{{ $value->intake->name }}</td>
                            <td>{{ $value->intake->course->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </td>
    </tr>
</table>
@endsection