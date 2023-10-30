@extends('admin::layout.main')

@section('title')
    E-School::Vote Heads
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> E-School::Vote Heads
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::fees.sidebar')
@endsection

@section('content')
    <vote-heads />
@endsection

@push('scripts_bottom')
    <script>
        localStorage.setItem('token', '{{ api_token() }}')
    </script>
    <script src="{{ asset('vendor/eschool/js/eschool.js') }}"></script>
@endpush
