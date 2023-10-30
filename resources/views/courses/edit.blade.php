@extends('admin::layout.main')

@section('title')
    E-School::Edit ({{ $student->name }})
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> E-School::Edit ({{ $student->name }})
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::courses.sidebar')
@endsection

@section('content')
    Students list
@endsection