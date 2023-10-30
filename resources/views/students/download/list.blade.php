@extends('eschool::layout.pdf')

@section('title')
	Students' Register
@endsection

@section('content')
	<table class="table table-striped text-uppercase">
		<thead class="thead-light">
			<tr>
				<th></th>
				<th>Admission No</th>
				<th>Name</th>
				<th>Gender</th>
				<th>Age</th>
				<th>Date of Admission</th>
				<th>Class</th>
				<th>Course</th>
				<th>Sponsor</th>
				<th>Program</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($students as $student)
				@php
                    $date = new DateTime($student->date_of_birth);
                    $now = new DateTime();
                    $interval = $now->diff($date);
                    $age = $interval->y;
                @endphp
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $student->admission_no }}</td>
					<td>{{ $student->name }}</td>
					<td>{{ $student->gender ? 'Female' : 'Male' }}</td>
					<td>{{ $age }}</td>
					<td>{{ date_create($student->date_of_admission)->format('j, M Y') }}</td>
					<td>{{ $student->intake->name }}</td>
					<td>{{ $student->intake->course->name }}</td>
					<td>{{ $student->sponsor->name }}</td>
					<td>{{ $student->program->name }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection