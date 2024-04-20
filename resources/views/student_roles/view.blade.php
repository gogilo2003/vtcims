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
    @include('sidebar')
    @include('students.sidebar')
@endsection

@section('content')
    Students list
@endsection
