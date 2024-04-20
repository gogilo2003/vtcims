@extends('layout.pdf')

@section('title')
    E-School::{{ $intake->name }}
@endsection

@section('content')
    <h3 class="text-center text-uppercase">Marklist for {{ $intake->name }}{{ isset($term) ? ', ' . $term->year_name : '' }}
    </h3>
    @include('examinations.marklists.marklist', compact('intake', 'term'))
@endsection
