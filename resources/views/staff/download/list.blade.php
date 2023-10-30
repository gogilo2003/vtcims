@extends('eschool::layout.pdf')

@section('content')
<h3 class="text-uppercase text-center">staff list</h3>
    <table class="table table-striped table-bordered table-sm text-uppercase">
        <thead class="thead-light">
            <tr>
                <th></th>
                <th>IDNO</th>
                <th>PF/NO</th>
                <th>MANNO</th>
                <th>NAME</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Position</th>
                <th>Employer</th>
                <th>Postal Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staff as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->idno }}</td>
                <td>{{ $item->pfno }}</td>
                <td>{{ $item->manno }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->gender }}</td>
                <td>{{ $item->phone }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->role->name }}</td>
                <td>{{ $item->employer }}</td>
                <td>{{ $item->address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
