@extends('layout.pdf')

@section('content')
    <h3 class="uppercase text-center font-light text-2xl py-4">staff list</h3>
    @if ($subTitle)
        <div class="font-normal text-xl text-center capitalize">{{ $subTitle }}</div>
    @endif
    <table class="w-full uppercase">
        <thead class="thead-light">
            <tr class="bg-gray-100">
                <th class="p-2 font-medium uppercase"></th>
                <th class="p-2 font-medium uppercase">IDNO</th>
                <th class="p-2 font-medium uppercase">PF/NO</th>
                <th class="p-2 font-medium uppercase">MANNO</th>
                <th class="p-2 font-medium uppercase">NAME</th>
                <th class="p-2 font-medium uppercase">Gender</th>
                <th class="p-2 font-medium uppercase">Phone</th>
                <th class="p-2 font-medium uppercase">Email</th>
                <th class="p-2 font-medium uppercase">Position</th>
                <th class="p-2 font-medium uppercase">Employer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff as $item)
                <tr class="odd:bg-gray-50">
                    <td class="text-sm border px-3 py-1 font-extralight">{{ $loop->iteration }}</td>
                    <td class="text-sm border px-3 py-1 font-extralight">{{ $item->idno }}</td>
                    <td class="text-sm border px-3 py-1 font-extralight">{{ $item->pfno }}</td>
                    <td class="text-sm border px-3 py-1 font-extralight">{{ $item->manno }}</td>
                    <td class="text-sm border px-3 py-1 font-extralight">{{ $item->name }}</td>
                    <td class="text-sm border px-3 py-1 font-extralight">{{ $item->gender }}</td>
                    <td class="text-sm border px-3 py-1 font-extralight">{{ $item->phone }}</td>
                    <td class="text-sm border px-3 py-1 font-extralight">{{ $item->email }}</td>
                    <td class="text-sm border px-3 py-1 font-extralight">{{ $item->role->name }}</td>
                    <td class="text-sm border px-3 py-1 font-extralight">
                        {{ $item->employer ? $item->employer->name : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('styles')
@endpush
