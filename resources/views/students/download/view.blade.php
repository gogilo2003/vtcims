@extends('eschool::layout.pdf')

@section('title')
    E-School::View ({{ $student->name }})
@endsection

@section('content')
    <table class="table table-no-border">
    	<tr>
    		<td width="15%" style="vertical-align: top;">
    			<img src="{{ file_exists('images/students/'.$student->photo) && $student->photo ? asset(config('admin.path_prefix').'/images/students/'.$student->photo) : asset(config('admin.path_prefix').'/vendor/admin/img/person_8x10.png') }}" alt="" class="img-fluid">
    		</td>
    		<td width="85%">
    			<table class="table text-uppercase table-bordered table-striped">
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
@endsection