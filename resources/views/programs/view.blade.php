@extends('admin::layout.main')

@section('title')
    E-School::View ({{ $student->name }})
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> E-School::View ({{ $student->name }})
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::students.sidebar')
@endsection

@section('content')
    
@endsection