@extends('admin::layout.main')

@section('title')
    E-School::View ({{ $department->name }})
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> {{ $department->name }}
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::departments.sidebar')
@endsection

@section('content')
<table class="table table-striped">
    <tr>
    	<td class="text-uppercase">
    		Department Code:
    	</td>
    	<td class="text-uppercase">
    		{{ $department->code }}
    	</td>
    </tr>
    <tr>
    	<td class="text-uppercase">
    		Department Name:
    	</td>
    	<td class="text-uppercase">
    		{{ $department->name }}
    	</td>
    </tr>
    <tr>
    	<td class="text-uppercase">
    		Head of Department:
    	</td>
    	<td class="text-uppercase">
    		<a href="{{ route('admin-eschool-staff-view',$department->hod->id) }}">{{ $department->hod->name }}</a>
    	</td>
    </tr>
    <tr>
    	<td class="text-uppercase">
    		Courses:
    	</td>
    	<td>
    		@foreach ($department->courses as $course)
    			<a href="{{ route('admin-eschool-courses-view',$course->id) }}" class="btn btn-round btn-sm btn-outline-info">{{ $course->name }}</a>
    		@endforeach
    	</td>
    </tr>
    <tr>
    	<td class="text-uppercase">
    		Classes/Intakes:
    	</td>
    	<td>
    		@foreach ($department->courses as $course)
    			@foreach($course->intakes as $intake)
    				<a href="{{ route('admin-eschool-intakes-view',$intake->id) }}" class="btn btn-round btn-sm btn-outline-info">{{ $intake->name }}</a>
    			@endforeach
    		@endforeach
    	</td>
    </tr>
</table>
@endsection