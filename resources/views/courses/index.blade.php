@extends('admin::layout.main')

@section('title')
    E-School::Courses
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Courses
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::courses.sidebar')
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-4">
    		<div class="card">
    			<div class="card-body">
    				<form id="courseForm" method="post" action="{{ ($errors->count() && old('id')) ? route('admin-eschool-courses-edit-post') : route('admin-eschool-courses-add-post') }}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
		    			
		    			<div class="form-group{!! $errors->has('department') ? ' has-error':'' !!}">
		    				<label for="department">Department</label>
		    				<select class="form-control" id="department" name="department" {!! ((old('department')) ? ' value="'.old('department').'"' : '') !!}>
		    					@foreach (\Ogilo\Eschool\Models\Department::all() as $department)
		    						<option value="{{ $department->id }}">{{ $department->name }}</option>
		    					@endforeach
		    				</select>
		    				{!! $errors->has('department') ? '<span class="text-danger">'.$errors->first('department').'</span>' : '' !!}
		    			</div>
		    			<div class="form-group{!! $errors->has('staff') ? ' has-error':'' !!}">
		    				<label for="staff">Staff</label>
		    				<select class="form-control" id="staff" name="staff" {!! ((old('staff')) ? ' value="'.old('staff').'"' : '') !!}>
		    					@foreach (\Ogilo\Eschool\Models\Staff::all() as $staff)
		    						<option value="{{ $staff->id }}">{{ $staff->name }}</option>
		    					@endforeach
		    				</select>
		    				{!! $errors->has('staff') ? '<span class="text-danger">'.$errors->first('staff').'</span>' : '' !!}
		    			</div>
		    			<br>
						<div class="form-group{!! $errors->has('code') ? ' has-error':'' !!}">
							<label class="bmd-label-floating" for="code">Code</label>
							<input type="text" class="form-control" id="code" name="code" {!! ((old('code')) ? ' value="'.old('code').'"' : '') !!}>
							{!! $errors->has('code') ? '<span class="text-danger">'.$errors->first('code').'</span>' : '' !!}
						</div>
						<div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
							<label class="bmd-label-floating" for="name">Name</label>
							<input type="text" class="form-control" id="name" name="name" {!! ((old('name')) ? ' value="'.old('name').'"' : '') !!}>
							{!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
						</div>
						<div class="form-group{!! $errors->has('id') ? ' has-error':'' !!}">
						{!! $errors->has('id') ? '<span class="text-danger">'.$errors->first('id').'</span>' : '' !!}
						</div>
		    			<input type="hidden" name="id" value="" id="courseId">
		    			<input type="hidden" name="_token" value="{{csrf_token()}}">
		    			<button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Save</button>
		    		</form>
    			</div>
    		</div>
    	</div>
    	<div class="col-md-8">
    		<div class="card">
    			<div class="card-body">
    				<div class="table-responsive">
    					<table class="table table-striped">
			    			<thead>
			    				<tr>
			    					<th></th>
			    					<th>Code</th>
			    					<th>Name</th>
			    					<th>Department</th>
			    					<th>Course Head</th>
			    					<th></th>
			    				</tr>
			    			</thead>
			    			<tbody>
			    				@foreach ($courses as $course)
			    					<tr>
			    						<td>{{ $loop->iteration }}</td>
			    						<td>{{ $course->code }}</td>
			    						<td>{{ $course->name }}</td>
			    						<td>{{ $course->department->name }}</td>
			    						<td>{{ $course->staff->name }}</td>
			    						<td>
			    							<a href="JavaScript:" class="btn btn-fab btn-primary btn-sm btn-round editCourseButton" data-course="{{ $course->toJson() }}"><i class="material-icons">edit</i></a>
			    						</td>
			    					</tr>
			    				@endforeach
			    			</tbody>
			    		</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
@endsection


@section('scripts_bottom')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.editCourseButton').click(function(){
				url = '{{ route('admin-eschool-courses-edit-post') }}'
				$('#courseForm').attr('action',url)
				$('#department').val($(this).data('course').department_id)
				$('#staff').val($(this).data('course').staff_id)
				$('#code').val($(this).data('course').code)
				$('#name').val($(this).data('course').name)
				$('#courseId').val($(this).data('course').id)
				$('#code').focus()
			});
		})
	</script>
@endsection