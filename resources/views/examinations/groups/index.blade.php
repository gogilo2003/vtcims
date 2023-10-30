@extends('admin::layout.main')

@section('title')
    E-School::Examination Groups
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Examination Groups
@endsection

@section('sidebar')
    @parent
    <hr>
    @include('eschool::sidebar')
    @include('eschool::examinations.sidebar')
@endsection

@section('content')
    <a href="{{ route('admin-eschool-examinations') }}" class="btn btn-primary btn-round"><span class="material-icons">assignment</span> Examinations</a>
    <div class="row">
        <div class="col-md-4">
            @include('eschool::examinations.groups.add')
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="examination_groupsDataTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Title</th>
                                    <th>Examinations</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($examination_groups as $examination_group)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $examination_group->title }}</td>
                                        <td><a href="{{ route('admin-eschool-examinations-groups-view',$examination_group->id) }}" class="btn btn-sm btn-round btn-primary">{{ $examination_group->examinations->count() }} Examinations</a></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle btn-round btn-sm" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="material-icons">settings</i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                    <a href="{{ route('admin-eschool-examinations-groups-download',$examination_group->id) }}"
                                                        class="dropdown-item">
                                                        <i class="material-icons">cloud_download</i>&nbsp;&nbsp;
                                                        Download Details
                                                    </a>
                                                    <a href="{{ route('admin-eschool-examinations-groups-edit',$examination_group->id) }}" class="dropdown-item"><i class="material-icons">edit</i> Edit</a>
                                                    <a href="{{ route('admin-eschool-examinations-groups-view',$examination_group->id) }}" class="dropdown-item"><i class="material-icons">view_list</i> Examination Details</a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
            
@endsection

@section('scripts_bottom')
    <script type="text/javascript">

	</script>
@endsection
