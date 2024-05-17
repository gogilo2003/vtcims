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
                    public $plwd = 0;
                },
            ];
        });
    @endphp
    @if ($title = request()->input('t'))
        <div class="text-2xl font-light py-4 text-center uppercase">{{ $title }}</div>
    @endif
    <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-800 text-gray-100 font-medium">
            <tr>
                <th rowspan="2" class="px-3 py-2 border-l border-r border-gray-200"></th>
                <th rowspan="2" class="text-left px-3 py-2 border-r border-gray-200">TRADE AREA/DEPARTMENT/COURSE</th>
                @foreach ($years as $year)
                    <th colspan="4" class="px-3 py-2 border-b border-r border-gray-200">{{ $year }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($years as $year)
                    <th class="px-3 py-2 border-r border-gray-200">MALE</th>
                    <th class="px-3 py-2 border-r border-gray-200">FEMALE</th>
                    <th class="px-3 py-2 border-r border-gray-200">TOTAL</th>
                    <th class="px-3 py-2 border-r border-gray-200">PLWD</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="font-normal">
            @foreach ($enrollments as $enrollment)
                <tr class="even:bg-gray-100">
                    <td class="px-3 py-2 border-l border-r border-gray-200 ">{{ $loop->iteration }}</td>
                    <td class="px-3 py-2 border-r border-gray-200 ">{{ $enrollment->name }}</td>
                    @foreach ($years as $year)
                        <td class="text-center px-3 py-2 border-r border-gray-200">
                            {{ $enrollment->{$year}->male }}</td>
                        <td class="text-center px-3 py-2 border-r border-gray-200 ">{{ $enrollment->{$year}->female }}</td>
                        <td class="text-center px-3 py-2 border-r border-gray-200 font-semibold">
                            {{ $enrollment->{$year}->total }}</td>
                        <td class="text-center px-3 py-2 border-r border-gray-200 font-semibold">
                            {{ $enrollment->{$year}->plwd }}
                        </td>
                        @php
                            $totals[$year]->male += $enrollment->{$year}->male;
                            $totals[$year]->female += $enrollment->{$year}->female;
                            $totals[$year]->total += $enrollment->{$year}->total;
                            $totals[$year]->plwd += $enrollment->{$year}->plwd;
                        @endphp
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-800 text-gray-100 font-semibold">
            <tr>
                <th colspan="2" rowspan="2" class="px-3 py-2 text-right border-l border-r border-gray-200">TOTAL</th>
                @foreach ($years as $year)
                    <th class="text-center px-3 py-2 border-r border-gray-200">{{ $totals[$year]->male }}</th>
                    <th class="text-center px-3 py-2 border-r border-gray-200">{{ $totals[$year]->female }}</th>
                    <th class="text-center px-3 py-2 border-r border-gray-200">{{ $totals[$year]->total }}</th>
                    <th class="text-center px-3 py-2 border-r border-gray-200">{{ $totals[$year]->plwd }}</th>
                @endforeach
            </tr>
        </tfoot>
    </table>
@endsection
