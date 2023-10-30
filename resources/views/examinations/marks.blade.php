@extends('admin::layout.main')

@section('title')
    E-School::{{ $examination->title }}
@endsection

@section('page_title')
    {{ implode(", ",$examination->intakes()->pluck('name')->toArray()) }}
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::examinations.sidebar')
@endsection

@section('content')
    <form action="{{ route('admin-eschool-examinations-marks-post') }}" method="post">
        <table class="table table-bordered text-uppercase">
            <thead class="thead-light">
                <tr>
                    <th style="width:50px; text-align:center"></th>
                    <th style="width:150px; text-align:center">Admission No</th>
                    <th>Student Name</th>
                    @foreach ($examination->tests as $test)
                    <th style="width:120px; text-align:center">{{ $test->title }}</th>
                    @endforeach
                </tr>
            </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($examination->intakes as $intake)
                        @foreach ($intake->students->where('status','In session') as $student)
                            <tr>
                                <td style="text-align:center">{{ ++$i }}</td>
                                <td style="text-align:center">{{ $student->admission_no }}</td>
                                <td>{{ $student->name }}</td>
                                @foreach ($examination->tests as $test)
                                    @forelse ($student->results->where('test_id',$test->id) as $result)
                                        @php
                                            $score = $result->score;//round($result->score / $test->outof * 100)
                                        @endphp
                                        <td><input name="edited_marks[{{ $result->id }}]" type="number" max="{{ $test->outof }}" class="form-control text-center" value="{{ $score }}"></td>
                                    @empty
                                        <td>
                                            <input name="marks[{{ $student->id }}][{{ $test->id }}]" type="number" max="{{ $test->outof }}" class="form-control text-center">
                                        </td>
                                    @endforelse
                                @php
                                @endphp
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">
                            <input type="hidden" name="examination" value="{{ $examination->id }}">
                            {{ csrf_field() }}
                            <a href="{{ route('admin-eschool-examinations-view',$examination->id) }}" class="btn btn-danger btn-round"><span class="material-icons">cancel</span> Cancel</a>
                            <button class="btn btn-primary btn-round"><span class="material-icons">save</span> Save</button>
                        </td>
                    </tr>
                </tfoot>

        </table>
    </form>
@endsection

@section('scripts_bottom')

@endsection
