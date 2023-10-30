<div class="modal fade" id="studentTranscriptModal" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<form method="post" action="{{ route('admin-eschool-examinations-transcripts-post') }}" class="modal-content">
			<div class="card">
				<div class="card-header card-header-primary rounded-pill">
					<button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					<h4 class="card-title">Transcript</h4>
				</div>
				<div class="card-body">
                    <p>Enter the admission number to get Transcript for a single student or select a class/Intake to get multiple transcripts</p>
					<div class="form-group{!! $errors->has('student_role') ? ' has-error':'' !!}">
						<label for="admission_no" class="bmd-label-floating">Admission Number</label>
                        <input type="text" class="form-control" name="admission_no" id="admission_no" value="{{ old('admission_no') ?? (isset($student) ? $student->id : '') }}">

                        @if ($errors->has('admission_no'))
                            <span class="text-danger">{{ $errors->first('admission_no') }}</span>
                        @endif
					</div>
					<div class="form-group">
						<label for="intake" class="bmd-label-static">Intake</label>
						<select class="w-100 selectpicker" name="intake" id="intake" data-style="btn btn-outline-primary rounded-pill" data-live-search="true">
							@foreach (\Ogilo\Eschool\Models\Intake::orderBy('start_date','DESC')->get() as $item)
								<option value="{{ $item->id }}">{{ $item->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="term" class="bmd-label-static">Term</label>
						<select class="w-100 selectpicker" name="term" id="term" data-style="btn btn-outline-primary rounded-pill" data-live-search="true">
							@foreach (\Ogilo\Eschool\Models\Term::orderBy('created_at','DESC')->get() as $item)
								<option value="{{ $item->id }}">{{ $item->year_name }}</option>
							@endforeach
						</select>
					</div>
					@csrf
				</div>
				<div class="card-footer">
					<button type="button" class="btn btn-outline-danger rounded-pill" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-outline-primary rounded-pill">Fetch</button>
				</div>
			</div>
		</form><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@push('scripts_bottom')
    <script type="text/javascript">
        $('#intake').selectpicker('val',null)
        $('#term').selectpicker('val',null)

        @if(old('intake'))
            $('#intake').selectpicker('val',{{old('intake')}})
        @else
            @if(isset($intake))
                $('#intake').selectpicker('val',{{$intake->id}})
            @endif
        @endif

        @if(old('term'))
            $('#term').selectpicker('val',{{old('term')}})
        @else
            @if(isset($term))
                $('#term').selectpicker('val',{{$term->id}})
            @endif
        @endif
	</script>
@endpush

