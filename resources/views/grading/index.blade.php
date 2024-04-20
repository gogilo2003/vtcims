@extends('admin::layout.main')

@section('title')
    E-School::Grading Setup
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> E-School::Grading Setup
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('examinations.sidebar')
@endsection

@section('content')
    <div id="app">
        <grading />
    </div>
@endsection

@push('scripts_bottom')
    <script>
        localStorage.setItem('token', '{{ api_token() }}')
    </script>
    <script src="{{ asset('vendor/eschool/js/eschool.js') }}"></script>
@endpush
