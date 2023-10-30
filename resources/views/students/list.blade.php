<!-- Students' List Filter Dialog -->
<div class="modal fade" data-backdrop="static" id="filterDownloadDialog" tabindex="-1" role="dialog" aria-labelledby="StduentDetails">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="card">
				<form id="filterDownloadPhotoForm" method="post" action="{{ route('admin-eschool-students-download') }}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
					<div class="card-header card-header-primary">
						<h4>Download Options</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group{!! $errors->has('department') ? ' has-error':'' !!}">
									<label class="bmd-label-static" for="department">Department</label>
									<select data-live-search="true" multiple class="form-control selectpicker" data-style="btn btn-link" id="department" name="department[]"{!! ((old('department')) ? ' value="'.old('department').'"' : '') !!}>
										@foreach (Ogilo\Eschool\Models\Department::all() as $department)
											<option value="{{ $department->id }}">{{ $department->name }}</option>
										@endforeach
									</select>
									{!! $errors->has('department') ? '<span class="text-danger">'.$errors->first('department').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group{!! $errors->has('course') ? ' has-error':'' !!}">
									<label class="bmd-label-static" for="course">Course</label>
									<select data-live-search="true" multiple class="form-control selectpicker" data-style="btn btn-link" id="course" name="course[]"{!! ((old('course')) ? ' value="'.old('course').'"' : '') !!}></select>
									{!! $errors->has('course') ? '<span class="text-danger">'.$errors->first('course').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group{!! $errors->has('intake') ? ' has-error':'' !!}">
									<label class="bmd-label-static" for="intake">Intake(Class)</label>
									<select data-live-search="true" multiple class="form-control selectpicker" data-style="btn btn-link" id="intake" name="intake[]"{!! ((old('intake')) ? ' value="'.old('intake').'"' : '') !!}></select>
									{!! $errors->has('intake') ? '<span class="text-danger">'.$errors->first('intake').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group{!! $errors->has('gender') ? ' has-error':'' !!}">
									<label class="bmd-label-static" for="gender">Gender</label>
									<select data-live-search="true" class="form-control selectpicker" data-style="btn btn-link" id="gender" name="gender"{!! ((old('gender')) ? ' value="'.old('gender').'"' : '') !!}>
										<option value="0">Male</option>
										<option value="1">Female</option>
									</select>
									{!! $errors->has('gender') ? '<span class="text-danger">'.$errors->first('gender').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group{!! $errors->has('sponsor') ? ' has-error':'' !!}">
									<label class="bmd-label-static" for="sponsor">Sponsor</label>
									<select data-live-search="true" multiple class="form-control selectpicker" data-style="btn btn-link" id="sponsor" name="sponsor[]"{!! ((old('sponsor')) ? ' value="'.old('sponsor').'"' : '') !!}>
										@foreach (Ogilo\Eschool\Models\Sponsor::all() as $sponsor)
											<option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
										@endforeach
									</select>
									{!! $errors->has('sponsor') ? '<span class="text-danger">'.$errors->first('sponsor').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group{!! $errors->has('program') ? ' has-error':'' !!}">
									<label class="bmd-label-static" for="program">Program</label>
									<select data-live-search="true" multiple class="form-control selectpicker" data-style="btn btn-link" id="program" name="program[]"{!! ((old('program')) ? ' value="'.old('program').'"' : '') !!}>
										@foreach (Ogilo\Eschool\Models\Program::all() as $program)
											<option value="{{ $program->id }}">{{ $program->name }}</option>
										@endforeach
									</select>
									{!! $errors->has('program') ? '<span class="text-danger">'.$errors->first('program').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group{!! $errors->has('role') ? ' has-error':'' !!}">
									<label class="bmd-label-static" for="role">Role</label>
									<select data-live-search="true" multiple class="form-control selectpicker" data-style="btn btn-link" id="role" name="role[]"{!! ((old('role')) ? ' value="'.old('role').'"' : '') !!}>
										@foreach (Ogilo\Eschool\Models\StudentRole::all() as $role)
											<option value="{{ $role->id }}">{{ $role->name }}</option>
										@endforeach
									</select>
									{!! $errors->has('role') ? '<span class="text-danger">'.$errors->first('role').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group{!! $errors->has('age') ? ' has-error':'' !!}">
									<label class="bmd-label-static" for="age">Age</label>
									<input type="text" class="form-control" data-style="btn btn-link" id="age" name="age"{!! ((old('age')) ? ' value="'.old('age').'"' : '') !!}>
									{!! $errors->has('age') ? '<span class="text-danger">'.$errors->first('age').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group{!! $errors->has('before_after') ? ' has-error':'' !!}">
									<label class="bmd-label-static" for="before_after">Admitted Before/After</label>
									<select data-live-search="true" class="form-control selectpicker" data-style="btn btn-link" id="before_after" name="before_after"{!! ((old('before_after')) ? ' value="'.old('before_after').'"' : '') !!}>
										<option value=""></option>
										<option value="before">Before</option>
										<option value="after">After</option>
									</select>
									{!! $errors->has('before_after') ? '<span class="text-danger">'.$errors->first('before_after').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-6">
								<br>
								<div class="form-group is-filed{!! $errors->has('date_of_admission') ? ' has-error':'' !!}">
									<label class="bmd-label-static" for="date_of_admission">Date of Admission</label>
									<input type="text" class="form-control datepicker" data-style="btn btn-link" id="date_of_admission" name="date_of_admission"{!! ((old('date_of_admission')) ? ' value="'.old('date_of_admission').'"' : '') !!}>
									{!! $errors->has('date_of_admission') ? '<span class="text-danger">'.$errors->first('date_of_admission').'</span>' : '' !!}
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						{{ csrf_field() }}
						<button type="submit" class="btn btn-primary btn-round"><i class="material-icons">cloud_download</i> Download</button>
						<a href="JavaScript:" id="filterResetButton" class="btn btn-round btn-warning"><i class="material-icons">refresh</i> Reset</a>
						<button type="reset" data-dismiss="modal" class="btn btn-danger btn-round"><i class="material-icons">cancel</i> Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
