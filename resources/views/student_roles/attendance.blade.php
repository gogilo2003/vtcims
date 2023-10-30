@extends('eschool::layout.main')

@section('title')
    E-School::Edit ({{ $student->name }})
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> E-School::Edit ({{ $student->name }})
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::students.sidebar')
@endsection

@section('content')
    <h3>Attendance for {{ $date->format('j-M-Y') }}</h3>
    <h3>Class: {{ $intake->name }}</h3>
    <table class="table table-bordered">
    	<tr>
    		<th rowspan="2"></th>
    		<th rowspan="2">Admission No</th>
    		<th rowspan="2">Name</th>
    		<th>Signature</th>
    	</tr>
    	<tr>
    		<th>8:00 Am - 10:00 Am</th>
    		<th>10:30 Am - 12:30 Pm</th>
    		<th>2:00 Pm - 4:00 Pm</th>
    	</tr>
    </table>

    <p>Teacher Name:</p>
	<p>Date:</p>
@endsection