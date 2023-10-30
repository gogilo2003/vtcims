@extends('eschool::layout.pdf')

@section('title')
    {{ $leaveout->student->name }}'s Leaveout
@endsection

@section('content')
    <table class="table table-borderless">
        <tr>
            <td><b class="text-uppercase">Admission No:</b></td>
            <td>
                <div class="underline">{{ $leaveout->student->admission_no }}</div>
            </td>
            <td>
                <b class="text-uppercase">Student Name:</b>
            </td>
            <td>
                <span class="underline">{{ $leaveout->student->name }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4"><b class="text-uppercase">Reason</b></td>
        </tr>
        <tr>
            <td colspan="4"><div class="underline">{{ $leaveout->reasons }}</div></td>
        </tr>
        <tr>
            <td colspan="4"><b class="text-uppercase">Remarks</b></td>
        </tr>
        <tr>
            <td colspan="4"><div class="underline">{{ $leaveout->remarks }}</div></td>
        </tr>
    </table>
    <table class="table table-borderless">
        <tr>
            <td><b class="text-uppercase">Issued By:</b></td>
            <td><b class="text-uppercase">Signature:</b></td>
            <td><b class="text-uppercase">Date:</b></td>
        </tr>
        <tr>
            <td width="40%"><div class="underline">{{ $leaveout->staff->min_name }}</div></td>
            <td width="30%"><div class="underline">&nbsp;</div></td>
            <td width="30%"><div class="underline">{!! $leaveout->created_at->format('l, j\<\s\u\p\>S\<\/\s\u\p\> F Y') !!}</div></td>
        </tr>
    </table>
@endsection

@section('styles')
<style type="text/css" rel="stylesheet">
    .underline{
        border-bottom: 1px dashed #222;
    }
    .table-borderless tr td{
        border: 0;
    }
</style>
@endsection
