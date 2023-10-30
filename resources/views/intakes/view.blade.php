@extends('admin::layout.main')

@section('title')
    E-School::View ({{ $intake->name }})
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> {{ $intake->name }}
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::intakes.sidebar')
@endsection

@section('content')
<table class="table table-striped">
    <tr>
    	<td class="text-uppercase">
    		Class Name:
    	</td>
    	<td class="text-uppercase">
    		{{ $intake->name }}
    	</td>
    </tr>
    <tr>
    	<td class="text-uppercase">
    		Incharge:
    	</td>
    	<td class="text-uppercase">
    		<a href="{{ route('admin-eschool-staff-view',$intake->staff->id) }}">{{ $intake->staff->name }}</a>
    	</td>
    </tr>
    <tr>
    	<td class="text-uppercase">
    		Subjects:
    	</td>
    	<td>
    		@foreach ($intake->staff_subjects as $staff_subject)
    			<a href="{{ route('admin-eschool-subjects-view',$staff_subject->subject->id) }}" class="btn btn-round btn-sm btn-outline-info">{{ $staff_subject->subject->name }}-{{ $staff_subject->staff->name }}</a>
    		@endforeach
    	</td>
    </tr>
</table>
@endsection