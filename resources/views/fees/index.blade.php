@extends('admin::layout.main')

@section('title')
    E-School::School Fees
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> E-School::School Fees
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::fees.sidebar')
@endsection

@section('content')
    <div id="app">
        <fees />
    </div>
@endsection

@push('scripts_bottom')
    <script>
        localStorage.setItem('token', '{{ api_token() }}')
    </script>
    <script src="{{ asset('vendor/eschool/js/eschool.js') }}"></script>
@endpush
