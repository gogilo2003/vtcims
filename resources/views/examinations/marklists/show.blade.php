@extends('admin::layout.main')

@section('title')
    E-School::{{ $intake->name }}{{ isset($term) ? ', ' . $term->year_name : '' }}
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Marklist for {{ $intake->name }}{{ isset($term) ? ', ' . $term->year_name : '' }}
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('sidebar')
    @include('examinations.sidebar')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <form action="{{ route('admin-eschool-examinations-marklists') }}" method="post">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="bmd-label-static" for="intake">Intake</label>
                                <select name="intake" id="intake" class="form-control selectpicker"
                                    data-style="btn btn-link" data-live-search="true">
                                    @foreach (\Ogilo\Eschool\Models\Intake::orderBy('start_date', 'DESC')->get() as $item)
                                        <option {{ $intake->id === $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="bmd-label-static" for="term">Term</label>
                                <select name="term" id="term" class="form-control selectpicker"
                                    data-style="btn btn-link" data-live-search="true">
                                    @foreach (\Ogilo\Eschool\Models\Term::orderBy('year', 'DESC')->orderBy('name', 'DESC')->get() as $item)
                                        <option {{ $term->id === $item->id ? 'selected' : '' }} value="{{ $item->id }}">
                                            {{ $item->year_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary btn-round btn-block"><span
                                        class="material-icons">assignment</span> Get Marklist</button>
                            </div>
                        </div>
                    </form>
                </div>
                @isset($intake)
                    <div class="col-md-3">
                        <form action="{{ route('admin-eschool-examinations-marklists-download') }}" method="post">
                            <input type="hidden" name="intake" value="{{ $intake->id }}">
                            <input type="hidden" name="term" value="{{ $term->id }}">
                            <input type="hidden" name="download" value="true">
                            {{ csrf_field() }}
                            <button class="btn btn-primary btn-round btn-block"><span
                                    class="material-icons">cloud_download</span>
                                Download Marklist</button>
                        </form>
                    </div>
                @endisset
            </div>
        </div>
    </div>
    @include('examinations.marklists.marklist', compact('intake', 'term'))
@endsection
