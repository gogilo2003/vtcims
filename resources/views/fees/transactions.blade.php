@extends('admin::layout.main')

@section('title')
    E-School::Fee Transactions
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> E-School::Fee Transactions
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('fees.sidebar')
@endsection

@section('content')
    <fee-transactions />
@endsection

@push('scripts_bottom')
    <script>
        localStorage.setItem('token', '{{ api_token() }}')
    </script>
    <script src="{{ asset('vendor/eschool/js/eschool.js') }}"></script>
@endpush
