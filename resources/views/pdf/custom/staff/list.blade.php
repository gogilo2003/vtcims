@extends('layout.pdf')

@section('content')
    @if ($title = request()->input('t'))
        <h3 class="uppercase text-center font-light text-2xl py-4">{{ $title }}</h3>
    @endif
    @if ($subTitle = request()->input('st'))
        <div class="font-normal text-xl text-center capitalize">{{ $subTitle }}</div>
    @endif
    <table class="w-full text-sm">
        <thead class="bg-gray-800">
            <tr class="text-gray-100 uppercase font-medium">
                <th class="py-2 px-3 border-l border-r border-gray-200"></th>
                <th class="py-2 px-3 border-r border-gray-200">IDNO</th>
                <th class="py-2 px-3 border-r border-gray-200">PF/NO</th>
                <th class="py-2 px-3 border-r border-gray-200">MANNO</th>
                <th class="py-2 px-3 border-r border-gray-200">NAME</th>
                <th class="py-2 px-3 border-r border-gray-200">Gender</th>
                <th class="py-2 px-3 border-r border-gray-200">Phone</th>
                <th class="py-2 px-3 border-r border-gray-200">Email</th>
                <th class="py-2 px-3 border-r border-gray-200">Position</th>
                <th class="py-2 px-3 border-r border-gray-200">Employer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff as $item)
                <tr class="odd:bg-gray-100">
                    <td class="border-l border-r border-gray-200 px-3 py-2 font-light">{{ $loop->iteration }}</td>
                    <td class="border-r border-gray-200 px-3 py-2 font-light">{{ $item->idno }}</td>
                    <td class="border-r border-gray-200 px-3 py-2 font-light">{{ $item->pfno }}</td>
                    <td class="border-r border-gray-200 px-3 py-2 font-light">{{ $item->manno }}</td>
                    <td class="border-r border-gray-200 px-3 py-2 font-light uppercase">{{ $item->name }}</td>
                    <td class="border-r border-gray-200 px-3 py-2 font-light uppercase">{{ $item->gender }}</td>
                    <td class="border-r border-gray-200 px-3 py-2 font-light">{{ $item->phone }}</td>
                    <td class="border-r border-gray-200 px-3 py-2 font-light">{{ $item->email }}</td>
                    <td class="border-r border-gray-200 px-3 py-2 font-light uppercase">{{ $item->role->name }}
                    </td>
                    <td class="border-r border-gray-200 px-3 py-2 font-light uppercase">
                        {{ $item->employer ? $item->employer->name : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('styles')
@endpush
