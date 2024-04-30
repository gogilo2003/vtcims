@extends('layout.pdf')

@section('title')
    E-School::View ({{ $student->first_name }}{{ $student->middle_name ? ' ' . $student->middle_name : '' }}
    {{ $student->surname }})
@endsection

@section('content')
    <table class="w-full mt-3">
        <tr>
            <td width="30%" style="vertical-align: top;" class="text-center pr-3">
                <img src="{{ $student->photo_url }}" alt="{{ $student->photo_url }}"
                    class="w-full border border-gray-800 rounded-lg overflow-hidden p-1">
                <div class="uppercase mt-4 font-light text-gray-800">Name:</div>
                <div class="uppercase font-medium text-lg">
                    {{ $student->first_name }}{{ $student->middle_name ? ' ' . $student->middle_name : '' }}
                    {{ $student->surname }}</div>
                <div class="uppercase mt-4 font-light text-gray-800">Admission Number:</div>
                <div class=" font-medium text-lg">{{ $student->admission_no }}</div>
            </td>
            <td width="70%">
                <table class="w-full">
                    <tr>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">
                            Class/Intake</th>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Course
                        </th>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->intake->name }}</td>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->intake->course }}</td>
                    </tr>
                    <tr>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Idno</th>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Email
                        </th>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->idno }}</td>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->email }}</td>
                    </tr>
                    <tr>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Phone
                        </th>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Gender
                        </th>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->phone }}</td>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->gender ? 'Female' : 'Male' }}</td>
                    </tr>
                    <tr>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Postal
                            Address</th>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Date of
                            Birth</th>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 border border-gray-800">P.O. Box
                            {{ $student->box_no }}{{ $student->post_code ? ' ' . $student->post_code : '' }}</td>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->date_of_birth }}</td>
                    </tr>
                    <tr>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Birth
                            Certificate
                            Number</th>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Date of
                            Admission
                        </th>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->birth_cert_no }}</td>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->date_of_admission }}</td>
                    </tr>
                    <tr>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Sponsor
                        </th>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase">Program
                        </th>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->sponsor->name }}</td>
                        <td class="px-2 py-2 border border-gray-800">{{ $student->program->name }}</td>
                    </tr>
                    <tr>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase"
                            colspan="2">Role
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" class="px-2 py-2 border border-gray-800">{{ $student->role->name }}</td>
                    </tr>
                    <tr>
                        <th class="px-2 py-2 border border-gray-800 text-left bg-gray-800 text-gray-100 uppercase"
                            colspan="4">
                            Physical Address
                        </th>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 border border-gray-800" colspan="4">{!! $student->physical_address ? $student->physical_address : '&nbsp;' !!}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="mt-6 pt-3 border-t">
        <div class="text-xl font-light mb-3 uppercase">Examination Summary</div>
        <table class="w-full border-collapse text-sm border-spacing-0 border-t border-b border-gray-800">
            <thead class="uppercase">
                <tr>
                    <th class="border-l border-r border-gray-800 bg-gray-800 px-3 py-2 text-gray-100">#</th>
                    <th class="border-r border-gray-800 bg-gray-800 px-3 py-2 text-gray-100 text-left">Subject</th>
                    <th class="border-r border-gray-800 bg-gray-800 px-3 py-2 text-gray-100">Mean</th>
                    <th class="border-r border-gray-800 bg-gray-800 px-3 py-2 text-gray-100">Min</th>
                    <th class="border-r border-gray-800 bg-gray-800 px-3 py-2 text-gray-100">Max</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($examination as $exam)
                    <tr class="even:bg-gray-200 font-light">
                        <td class="border-l border-r border-gray-800 px-3 py-2 text-center">{{ $loop->iteration }}</td>
                        <td class="border-r border-gray-800 px-3 py-2">{{ $exam->subject }}</td>
                        <td class="border-r border-gray-800 px-3 py-2 text-center">{{ $exam->average }}</td>
                        <td class="border-r border-gray-800 px-3 py-2 text-center">{{ $exam->min }}</td>
                        <td class="border-r border-gray-800 px-3 py-2 text-center">{{ $exam->max }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
