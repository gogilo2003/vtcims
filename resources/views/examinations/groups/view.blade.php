@extends('admin::layout.main')

@section('title')
    E-School::{{ $examination_group->title }}
@endsection

@section('page_title')
    {{ implode(', ', $examination_group->intakes()->pluck('name')->toArray()) }}
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('examinations.sidebar')
@endsection

@section('content')
    <table class="table table-bordered text-uppercase">
        <thead class="thead-light">
            <tr>
                <th></th>
                <th>Examination</th>
                <th>Tests</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($examination_group->examinations as $examination)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $examination->title }}</td>
                    <td>{{ $examination->tests->count() }}</td>
                    <td>
                        <a href="{{ route('admin-eschool-examinations-view', $examination->id) }}"
                            class="btn btn-primary btn-round btn-sm btn-fab">
                            <span class="material-icons">list</span>
                        </a>
                        <a href="{{ route('admin-eschool-examinations-marks', $examination->id) }}"
                            class="btn btn-primary btn-round btn-sm btn-fab">
                            <span class="material-icons">assignment</span>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts_bottom')
@endsection
