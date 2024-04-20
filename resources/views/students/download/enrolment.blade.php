@extends('layout.pdf')

@section('title')
    ENROLMENT
@endsection

@section('content')
    @php

        $totals = (object) $years->mapWithKeys(function ($year, $key) {
            return [
                $year => new class {
                    public $male = 0;
                    public $female = 0;
                    public $total = 0;
                },
            ];
        });
    @endphp
    <table class="table table-striped table-bordered text-uppercase">
        <thead class="thead-light">
            <tr>
                <th rowspan="2"></th>
                <th rowspan="2">COURSE</th>
                @foreach ($years as $year)
                    <th colspan="3">{{ $year }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($years as $year)
                    <th>MALE</th>
                    <th>FEMALE</th>
                    <th>TOTAL</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($enrolments as $enrolment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $enrolment->name }}</td>
                    @foreach ($years as $year)
                        <td style="text-align: center">{{ $enrolment->{$year}->male }}</td>
                        <td style="text-align: center">{{ $enrolment->{$year}->female }}</td>
                        <td style="text-align: center; font-weight:700">{{ $enrolment->{$year}->total }}</td>
                        @php
                            $totals[$year]->male += $enrolment->{$year}->male;
                            $totals[$year]->female += $enrolment->{$year}->female;
                            $totals[$year]->total += $enrolment->{$year}->total;
                        @endphp
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot class="thead-light" style="font-weight: 700;">
            <tr>
                <th colspan="2" rowspan="2" style="text-align: right">TOTAL</th>
                @foreach ($years as $year)
                    <th style="text-align: center">{{ $totals[$year]->male }}</th>
                    <th style="text-align: center">{{ $totals[$year]->female }}</th>
                    <th style="text-align: center">{{ $totals[$year]->total }}</th>
                @endforeach
            </tr>
        </tfoot>
    </table>
@endsection
