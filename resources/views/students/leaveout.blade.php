<!-- Student's leaveout Dialog -->
<div class="modal fade" data-backdrop="static" id="leaveOutDialog" tabindex="-1" role="dialog" aria-labelledby="StduentDetails">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="card">
				<form id="leaveOutForm" method="post" action="{{ route('admin-eschool-students-leaveout') }}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
					<div class="card-header card-header-primary">
						<h4>Leave Out Options</h4>
					</div>
					<div class="card-body">

                            <div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
                                <label class="bmd-label-static" for="name">Name</label>
                                <input type="text" class="form-control" data-style="btn btn-link" id="name" name="name"{!! ((old('name')) ? ' value="'.old('name').'"' : '') !!}>
                                {!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
                            </div>

                            <div class="form-group{!! $errors->has('valid_until') ? ' has-error':'' !!}">
                                <label class="bmd-label-static" for="valid_until">Valid Until</label>
                                <input type="text" class="form-control datetimepicker" data-style="btn btn-link" id="valid_until" name="valid_until"{!! ((old('valid_until')) ? ' value="'.old('valid_until').'"' : '') !!}>
                                {!! $errors->has('valid_until') ? '<span class="text-danger">'.$errors->first('valid_until').'</span>' : '' !!}
                            </div>

                            <div class="form-group{!! $errors->has('reasons') ? ' has-error':'' !!}">
                                <label class="bmd-label-static" for="reasons">Reasons</label>
                                <textarea class="form-control" data-style="btn btn-link" id="reasons" name="reasons">{!! ((old('reasons')) ? old('reasons') : '') !!}</textarea>
                                {!! $errors->has('reasons') ? '<span class="text-danger">'.$errors->first('reasons').'</span>' : '' !!}
                            </div>

                            <div class="form-group{!! $errors->has('remarks') ? ' has-error':'' !!}">
                                <label class="bmd-label-static" for="remarks">Remarks</label>
                                <textarea class="form-control" data-style="btn btn-link" id="remarks" name="remarks">{!! ((old('remarks')) ? old('remarks') : '') !!}</textarea>
                                {!! $errors->has('remarks') ? '<span class="text-danger">'.$errors->first('remarks').'</span>' : '' !!}
                            </div>

					</div>
					<div class="card-footer">
                        <input type="hidden" name="id" id="leaveOutId">
						{{ csrf_field() }}
						<button type="submit" class="btn btn-primary btn-round"><i class="material-icons">done_all</i> Issue</button>
						<a href="JavaScript:" id="filterResetButton" class="btn btn-round btn-warning"><i class="material-icons">refresh</i> Reset</a>
						<button type="reset" data-dismiss="modal" class="btn btn-danger btn-round"><i class="material-icons">cancel</i> Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
