@extends('admin::layout.main')

@section('title')
    E-School::Student Roles
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Student Roles
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::student_roles.sidebar')
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-4">
    		<div class="card">
    			<div class="card-body">
    				<form id="student_roleForm" method="post" action="{{ ($errors->count() && old('id')) ? route('admin-eschool-student_roles-edit-post') : route('admin-eschool-student_roles-add-post') }}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
    					<div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
    						<label class="bmd-label-floating" for="name">Student Role name</label>
    						<input type="text" class="form-control" id="name" name="name"{!! ((old('name')) ? ' value="'.old('name').'"' : '') !!}>
    						{!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
    					</div>
                        <div class="form-group{!! $errors->has('description') ? ' has-error':'' !!}">
                            <label class="bmd-label-floating" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description"> {!! ((old('description')) ? ' value="'.old('description').'"' : '') !!}</textarea>
                            {!! $errors->has('description') ? '<span class="text-danger">'.$errors->first('description').'</span>' : '' !!}
                        </div>
    					<input type="hidden" name="id" value="" id="student_roleId">
    					<input type="hidden" name="_token" value="{{ csrf_token() }}">
    					<button type="submit" class="btn btn-primary btn-round"><span class="material-icons">save</span> Save</button>
    					<a href="JavaScript:" id="cancelStudentRoleEdit" class="btn btn-danger btn-round" style="display: none"><i class="material-icons">cancel</i> Cancel</a>
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
    								<th>Student Role Name</th>
    								<th></th>
    							</tr>
    						</thead>
    						<tbody>
    							@foreach ($student_roles as $student_role)
    								<tr>
    									<td>{{ $loop->iteration }}</td>
    									<td>{{ $student_role->name }}</td>
    									<td>
    										<a href="JavaScript:" class="btn btn-primary btn-fab btn-round btn-sm editStudentRoleButton" data-student_role="{{ $student_role->toJson() }}"><i class="material-icons">edit</i></a>
    										<a href="JavaScript:" class="btn btn-fab btn-round btn-danger btn-sm deleteStudentRoleButton" data-id="{{ $student_role->id }}" data-name="{{ $student_role->name }}"><i class="material-icons">delete</i></a>
    									</td>
    								</tr>
    							@endforeach
    						</tbody>
    					</table>
    					<form id="deleteStudentRoleForm" method="post" action="{{route('admin-eschool-student_roles-delete')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
    						<input type="hidden" name="id" id="deleteStudentRoleId">
    						<input type="hidden" name="_token" value="{{csrf_token()}}">
    					</form>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
@endsection

@section('scripts_bottom')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.editStudentRoleButton').click(function(){
				url = '{{ route('admin-eschool-student_roles-edit-post') }}'
				$('#student_roleForm').attr('action',url)

				$('#name').val($(this).data('student_role').name)
                $('#description').val($(this).data('student_role').description)
				$('#student_roleId').val($(this).data('student_role').id)

				$('#cancelStudentRoleEdit').attr('style','display: inline-block')

				$('#name').focus()
			})

			$('#cancelStudentRoleEdit').click(function(){
				url = '{{ route('admin-eschool-student_roles-add-post') }}'
				$('#student_roleForm').attr('action',url)

				$('#name').val(null)
                $('#description').val(null)
				$('#student_roleId').val(null)

				$('#cancelStudentRoleEdit').attr('style','display: none')
				$('#name').focus()
			})

			$('.deleteStudentRoleButton').click(function(){
				$('#deleteStudentRoleId').val($(this).data('id'))
				name = $(this).data('name')
				if (confirm("Do you want delete "+name)){
					$('#deleteStudentRoleForm').submit()
				}
				
			})
            
		})
	</script>
@endsection