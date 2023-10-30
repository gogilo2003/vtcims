@extends('admin::layout.main')

@section('title')
    E-School::Subjects
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Subjects
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::subjects.sidebar')
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-4">
    		<div class="card">
    			<div class="card-body">
    				<form id="subjectForm" method="post" action="{{ ($errors->count() && old('id')) ? route('admin-eschool-subjects-edit-post') : route('admin-eschool-subjects-add-post') }}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
                        <div class="form-group bmd-form-group{!! $errors->has('subject_code') ? ' has-error':'' !!}">
                            <label class="bmd-label-floating" for="subject_code">Subject Code</label>
                            <input type="text" class="form-control" id="subject_code" name="subject_code" {!! ((old('subject_code')) ? ' value="'.old('subject_code').'"' : '') !!}>
                            {!! $errors->has('subject_code') ? '<span class="text-danger">'.$errors->first('subject_code').'</span>' : '' !!}
                        </div>

    					<div class="form-group bmd-form-group{!! $errors->has('subject_name') ? ' has-error':'' !!}">
    						<label class="bmd-label-floating" for="subject_name">Subject name</label>
    						<input type="text" class="form-control" id="subject_name" name="subject_name"{!! ((old('subject_name')) ? ' value="'.old('subject_name').'"' : '') !!}>
    						{!! $errors->has('subject_name') ? '<span class="text-danger">'.$errors->first('subject_name').'</span>' : '' !!}
    					</div>

                        <div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('courses') ? ' has-error':'' !!}">
                            <label class="bmd-label-floating" for="courses">Courses</label>
                            <select multiple="multiple" data-style="btn btn-link" class="form-control bmd-form-control selectpicker" id="courses" name="courses[]">
                                @foreach (\Ogilo\Eschool\Models\Course::all() as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->has('courses') ? '<span class="text-danger">'.$errors->first('courses').'</span>' : '' !!}
                        </div>
                        
    					<input type="hidden" name="id" value="" id="subjectId">
    					<input type="hidden" name="_token" value="{{ csrf_token() }}">
    					<button type="submit" class="btn btn-primary btn-round"><span class="material-icons">save</span> Save</button>
    					<a href="JavaScript:" id="cancelSubjectEdit" class="btn btn-danger btn-round" style="display: none"><i class="material-icons">cancel</i> Cancel</a>
    				</form>
    			</div>
    		</div>
    	</div>
    	<div class="col-md-8">
    		<div class="card">
    			<div class="card-body">
    				<div class="table-responsive">
    					<table class="table table-striped" id="subjectsDataTable">
    						<thead class="thead-light">
    							<tr>
    								<th></th>
                                    <th>Code</th>
    								<th>Subject Name</th>
                                    <th>Courses</th>
    								<th></th>
    							</tr>
    						</thead>
    						<tbody>
    							@foreach ($subjects as $subject)
    								<tr>
    									<td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->code }}</td>
    									<td>{{ $subject->name }}</td>
                                        <td>
                                            @foreach($subject->courses as $course)
                                                <a href="{{ route('admin-eschool-courses-view',$course->id) }}" class="btn btn-sm btn-round btn-outline-primary" title="{{ $course->name }}">{{ $course->code }}</a>
                                            @endforeach
                                        </td>
    									<td>
    										<a href="JavaScript:" class="btn btn-primary btn-fab btn-round btn-sm editSubjectButton" data-courses="{{ $subject->course_ids }}" data-subject="{{ $subject->toJson() }}"><i class="material-icons">edit</i></a>
                                            <a href="{{ route('admin-eschool-subjects-view',$subject->id) }}" class="btn btn-sm btn-fab btn-round btn-info"><i class="material-icons">zoom_in</i></a>
    										<a href="JavaScript:" class="btn btn-fab btn-round btn-danger btn-sm deleteSubjectButton" data-id="{{ $subject->id }}" data-name="{{ $subject->name }}"><i class="material-icons">delete</i></a>
    									</td>
    								</tr>
    							@endforeach
    						</tbody>
    					</table>
    					<form id="deleteSubjectForm" method="post" action="{{route('admin-eschool-subjects-delete')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
    						<input type="hidden" name="id" id="deleteSubjectId">
    						<input type="hidden" name="_token" value="{{csrf_token()}}">
    					</form>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="showCourseDialog" data-align="middle" tabindex="-1" role="dialog" aria-labelledby="ShowCourse">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="card-title" id="ShowCourse">Course</h4>
                    </div>
                    <div class="card-body">
                    
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts_bottom')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.editSubjectButton').click(function(){
				url = '{{ route('admin-eschool-subjects-edit-post') }}'
				$('#subjectForm').attr('action',url)

				$('#subject_name').val($(this).data('subject').name)
                $('#subject_code').val($(this).data('subject').code)
                $('#courses').selectpicker('val', $(this).data('courses'))
				$('#subjectId').val($(this).data('subject').id)

				$('#cancelSubjectEdit').attr('style','display: inline-block')
				$('#subject_code').focus()
			})

			$('#cancelSubjectEdit').click(function(){
				url = '{{ route('admin-eschool-subjects-add-post') }}'
				$('#subjectForm').attr('action',url)

				$('#subject_name').val(null)
                $('#subject_code').val(null)
                $('#courses').selectpicker('val', null)
				$('#subjectId').val(null)

				$('#cancelSubjectEdit').attr('style','display: none')
				$('#subject_code').focus()
			})

			$('.deleteSubjectButton').click(function(){
				$('#deleteSubjectId').val($(this).data('id'))
				name = $(this).data('name')
				if (confirm("Do you want delete "+name)){
					$('#deleteSubjectForm').submit()
				}
				
			})

			$('table#subjectsDataTable').dataTable()
		})
	</script>
@endsection