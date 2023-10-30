@extends('admin::layout.main')

@section('title')
    E-School::Terms
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Terms
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::terms.sidebar')
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				<div class="card-body">
					<form id="termForm" method="post" action="{{ isset($errors) && old('id') ? route('admin-eschool-terms-edit-post') :route('admin-eschool-terms-add-post')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
						<div class="form-group{!! $errors->has('id') ? ' has-error':'' !!}">
							<input type="hidden" id="termId" name="id" placeholder="Enter value"{!! ((old('id')) ? ' value="'.old('id').'"' : '') !!}>
							{!! $errors->has('id') ? '<span class="text-danger">'.$errors->first('id').'</span>' : '' !!}
						</div>
						<div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
							<label class="bmd-label-static" for="name">Name</label>
							<input type="text" class="form-control" id="name" name="name"{!! ((old('name')) ? ' value="'.old('name').'"' : '') !!}>
							{!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
						</div>

						<div class="form-group{!! $errors->has('year') ? ' has-error':'' !!}">
							<label class="bmd-label-static" for="year">Year</label>
							<input type="text" class="form-control" id="year" name="year"{!! ((old('year')) ? ' value="'.old('year').'"' : '') !!}>
							{!! $errors->has('year') ? '<span class="text-danger">'.$errors->first('year').'</span>' : '' !!}
						</div>

						<div class="form-group{!! $errors->has('start_date') ? ' has-error':'' !!}">
							<label class="bmd-label-static" for="start_date">Start Date</label>
							<input type="text" class="form-control datepicker" id="start_date" name="start_date"{!! ((old('start_date')) ? ' value="'.old('start_date').'"' : '') !!}>
							{!! $errors->has('start_date') ? '<span class="text-danger">'.$errors->first('start_date').'</span>' : '' !!}
						</div>

						<div class="form-group{!! $errors->has('end_date') ? ' has-error':'' !!}">
							<label class="bmd-label-static" for="end_date">End Date</label>
							<input type="text" class="form-control datepicker" id="end_date" name="end_date"{!! ((old('end_date')) ? ' value="'.old('end_date').'"' : '') !!}>
							{!! $errors->has('end_date') ? '<span class="text-danger">'.$errors->first('end_date').'</span>' : '' !!}
						</div>

						{{ csrf_field() }}
						<button type="submit" class="btn btn-primary btn-round"><span class="material-icons">save</span> Save</button>
						<a href="JavaScript:" class="btn btn-round btn-danger" id="cancelEditButton" style="display: none"><i class="material-icons">cancel</i> Cancel</a>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-body">
					<table class="table table-bordered" id="termsDataTable">
				    	<thead class="thead-light">
				    		<tr>
				    			<th></th>
				    			<th>Name</th>
				    			<th>Start</th>
				    			<th>End</th>
				    			<th></th>
				    		</tr>
				    	</thead>
				    	<tbody>
				    		@foreach ($terms as $term)
				    			<tr>
				    				<td>{{ $loop->iteration }}</td>
				    				<td>{{ $term->name }}</td>
				    				<td>{{ date_create($term->start_date)->format('j-M-Y') }}</td>
				    				<td>{{ date_create($term->end_date)->format('j-M-Y') }}</td>
				    				<td>
				    					<a href="JavaScript:" class="btn btn-fab btn-round btn-sm btn-primary editTermButton" data-term="{{ $term->toJson() }}"><i class="material-icons">edit</i></a>
				    				</td>
				    			</tr>
				    		@endforeach
				    	</tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts_bottom')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.editTermButton').click(function(){
				url = '{{ route('admin-eschool-terms-edit-post') }}'
				$('#termForm').attr('action',url)

				$('#termForm #name').val($(this).data('term').name)
				$('#termForm #year').val($(this).data('term').year)
				$('#termForm #start_date').val($(this).data('term').start_date)
				$('#termForm #end_date').val($(this).data('term').end_date)
				$('#termForm #termId').val($(this).data('term').id)

				$('#cancelEditButton').css('display','inline-block');
			})

			$('#cancelEditButton').click(function(){
				url = '{{ route('admin-eschool-terms-add-post') }}'
				$('#termForm').attr('action',url)

				$('#termForm #name').val(null)
				$('#termForm #year').val(null)
				$('#termForm #start_date').val(null)
				$('#termForm #end_date').val(null)
				$('#termForm #termId').val(null)

				$('#cancelEditButton').css('display','none');
			})

			$('#termsDataTable').dataTable()
		})
	</script>
@endsection