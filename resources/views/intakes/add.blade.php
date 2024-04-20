@extends('admin::layout.main')

@section('title')
    E-School::Add Student
@endsection

@section('page_title')
    <i class="material-icons">person_add</i> E-School::Add Student
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('students.sidebar')
@endsection

@section('content')
    add student form
@endsection
