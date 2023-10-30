@extends('admin::layout.main')

@section('title')
    E-School::View ({{ $subject->name }})
@endsection

@section('page_title')
    <i class="fa fa-th-list"></i> {{ $subject->name }}
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::subjects.sidebar')
@endsection

@section('content')
    <table class="table">
    	<tr>
    		<td>Subject Code:</td>
    		<td>{{ $subject->code }}</td>
    	</tr>
    	<tr>
    		<td>Subject Name:</td>
    		<td>{{ $subject->name }}</td>
    	</tr>
    	<tr>
    		<td>Classes & Teachers:</td>
    		<td>
    			@foreach ($subject->intake_staff as $intake_staff)
    				<a href="{{ route('admin-eschool-staff-view',$intake_staff->staff->id) }}" class="btn btn-sm btn-outline-info btn-round">{{ $intake_staff->staff->surname }} ({{ $intake_staff->intake->name }})</a>
    			@endforeach
    		</td>
    	</tr>
    	<tr>
    		<td>Courses:</td>
    		<td>
    			@foreach ($subject->courses as $course)
    				<a href="{{ route('admin-eschool-courses-view',$course->id) }}" class="btn btn-sm btn-outline-warning btn-round">{{ $course->name }}</a>
    			@endforeach
    		</td>
    	</tr>
    </table>
@endsection