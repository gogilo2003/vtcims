<div class="modal fade" data-backdrop="static" id="attendanceDialog" tabindex="-1" role="dialog" aria-labelledby="StduentDetails">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="card">
				<form id="attendanceDetailsForm" method="post" action="{{ route('admin-eschool-staff-attendance') }}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
					<div class="card-header card-header-primary">
						<h4>Attendance Options</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group bmd-form-group is-filled is-focused{!! $errors->has('employer') ? ' has-error':'' !!}">
									<label for="employer" class="static-label-static">Select Employer</label>
									<select multiple data-live-search="true" class="form-control selectpicker" name="employer[]" id="employer" data-style="btn btn-link">
										@foreach (config('eschool.employer') as $employer)
											<option {!! old('employer') == $employer ? 'selected' : '' !!}>{{ $employer }}</option>
										@endforeach
									</select>
									{!! $errors->has('employer') ? '<span class="text-danger">'.$errors->first('employer').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group bmd-form-group{!! $errors->has('date') ? ' has-error':'' !!}">
									<label for="date">Date</label>
									<input type="text" class="form-control datepicker" id="date" name="date"{!! ((old('date')) ? ' value="'.old('date').'"' : '') !!}>
									{!! $errors->has('date') ? '<span class="text-danger">'.$errors->first('date').'</span>' : '' !!}
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						{{ csrf_field() }}
						<button type="submit" class="btn btn-primary btn-round"><i class="material-icons">cloud_download</i> Get Register</button>
						<button type="reset" data-dismiss="modal" class="btn btn-danger btn-round"><i class="material-icons">cancel</i> Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
