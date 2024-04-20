@extends('pdf.layout.pdf')

@section('content')
    <h3 class="text-uppercase text-center">attendance for {{ $employer }} staff</h3>
    <table class="table table-borderless">
        <tr>
            <td>FROM: <span class="underline">{!! $mon->format('j\<\s\u\p\>S\<\/\s\u\p\> M, Y') !!}</span></td>
            <td>TO: <span class="underline">{!! $fri->format('j\<\s\u\p\>S\<\/\s\u\p\> M, Y') !!}</span></td>
            <td>WEEK: <span class="underline">{{ week() }}</span></td>
        </tr>
    </table>
    <table class="table black table-striped table-bordered table-sm">
        @foreach ([$mon, $tue, $wed, $thu, $fri] as $day)
            <thead class="thead-light">
                <tr>
                    <th colspan="9">
                        <h4>{!! $day->format('l j\<\s\u\p\>S\<\/\s\u\p\> M, Y') !!}</h4>
                    </th>
                </tr>
                <tr>
                    <th></th>
                    <th class="text-uppercase">IDNO</th>
                    <th class="text-uppercase">PF/NO</th>
                    <th class="text-uppercase">NAME</th>
                    <th class="text-uppercase" style="width: 100px">Time In</th>
                    <th class="text-uppercase" style="width: 180px">Sign</th>
                    <th class="text-uppercase" style="width: 100px">Time Out</th>
                    <th class="text-uppercase" style="width: 180px">Sign</th>
                    <th class="text-uppercase" style="width: 200px">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($staff as $item)
                    <tr>
                        <td class="text-uppercase">{{ $loop->iteration }}</td>
                        <td class="text-uppercase">{{ $item->idno }}</td>
                        <td class="text-uppercase">{{ $item->pfno }}</td>
                        <td class="text-uppercase">{{ $item->min_name }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        @endforeach
    </table>
@endsection

@section('styles')
    <style type="text/css">
        .table.black>thead>tr>th,
        .table.black>tbody>tr>td,
        .table.black .thead-light tr th {
            border-color: #222;
        }

        span.underline {
            padding: 5px 8px;
            border-bottom: 1px dashed #000;
        }
    </style>
@endsection
