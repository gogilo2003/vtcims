@extends('admin::layout.main')

@section('title')
    E-School::View ({{ $course->name }})
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> View ({{ $course->name }})
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('courses.sidebar')
@endsection

@section('content')
    <table class="table table-striped">
        <tr>
            <td class="text-uppercase">
                Code:
            </td>
            <td>
                {{ $course->code }}
            </td>
        </tr>
        <tr>
            <td class="text-uppercase">
                Name:
            </td>
            <td>
                {{ $course->name }}
            </td>
        </tr>
        <tr>
            <td class="text-uppercase">
                Department:
            </td>
            <td>
                <a href="{{ route('admin-eschool-departments-view', $course->department->id) }}">
                    {{ $course->department->name }}</a>
            </td>
        </tr>
        <tr>
            <td class="text-uppercase">
                Incharge:
            </td>
            <td>
                <a href="{{ route('admin-eschool-staff-view', $course->staff->id) }}">{{ $course->staff->name }}</a>
            </td>
        </tr>
        <tr>
            <td class="text-uppercase">
                Subjects:
            </td>
            <td>
                @foreach ($course->subjects as $subject)
                    <a href="{{ route('admin-eschool-subjects-view', $subject->id) }}"
                        class="btn btn-round btn-sm btn-outline-primary">{{ $subject->name }}</a>
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="text-uppercase">
                Classes/Intakes:
            </td>
            <td>
                @foreach ($course->intakes as $intake)
                    <a href="{{ route('admin-eschool-intakes-view', $intake->id) }}"
                        class="btn btn-round btn-sm btn-outline-info">{{ $intake->name }}</a>
                @endforeach
            </td>
        </tr>
    </table>
@endsection
