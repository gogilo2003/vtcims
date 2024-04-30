@if (isset($transcript))
    <div class="px-16">
        <div class="text-center font-light uppercase text-xl my-6">
            Academic Transcript
        </div>
        <div class="border p-3 rounded-lg">
            <div class="text-sm uppercase">
                <span class="text-sm font-medium inline-block w-28">Name: </span>
                <span class="border-b border-gray-800">{{ $transcript->name }}</span>
            </div>
            <div class="">
                <span class="text-sm font-mediun inline-block w-28">Admission No: </span>
                <span class="border-b border-gray-800">{{ $transcript->admission_no }}</span>
            </div>
            <div class="">
                <span class="text-sm font-mediun inline-block w-28">Course: </span>
                <span class="border-b border-gray-800">{{ $transcript->course }}</span>
            </div>
            <div class="">
                <span class="text-sm font-mediun inline-block w-28">Program/Exam: </span>
                <span class="border-b border-gray-800">{{ $transcript->program }}</span>
            </div>
            <div class="">
                <span class="text-sm font-mediun inline-block w-28">Class: </span>
                <span class="border-b border-gray-800">{{ $transcript->intake }}</span>
            </div>
            <div class="">
                <span class="text-sm font-mediun inline-block w-28">Session: </span>
                <span class="border-b border-gray-800">{{ $term->year }}-{{ $term->name }}</span>
            </div>
        </div>
        <div class="py-6">
            <table class="w-full mt-6 border-b border-t border-gray-800 text-sm">
                <thead>
                    <tr class="bg-gray-800 text-gray-50 font-normal uppercase">
                        <th class="px-3 py-2 border-l border-r border-gray-800 w-6">Code</th>
                        <th class="px-3 py-2 border-r border-gray-800 text-left">Subject</th>
                        <th class="px-3 py-2 border-r border-gray-800">Score</th>
                        <th class="px-3 py-2 border-r border-gray-800">Grade</th>
                        <th class="px-3 py-2 border-r border-gray-800">Remarks</th>
                        <th class="px-3 py-2 border-r border-gray-800">Average</th>
                        <th class="px-3 py-2 border-r border-gray-800">Min</th>
                        <th class="px-3 py-2 border-r border-gray-800">Max</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transcript->marks as $mark)
                        <tr class="even:bg-gray-100 font-extralight">
                            <td class="px-3 py-2 border-l border-r border-gray-800 uppercase">{{ $mark->code }}</td>
                            <td class="px-3 py-2 border-r border-gray-800">{{ $mark->subject }}</td>
                            <td class="px-3 py-2 border-r border-gray-800">{{ $mark->score }}</td>
                            <td class="px-3 py-2 border-r border-gray-800">{{ $mark->grade }}</td>
                            <td class="px-3 py-2 border-r border-gray-800">{{ $mark->remark }}</td>
                            <td class="px-3 py-2 border-r border-gray-800">{{ $mark->average }}</td>
                            <td class="px-3 py-2 border-r border-gray-800">{{ $mark->min }}</td>
                            <td class="px-3 py-2 border-r border-gray-800">{{ $mark->max }}</td>
                        </tr>
                    @endforeach
                <tbody>
            </table>
        </div>
        <div>
            <p class="total-score" colspan="5"><span class="caption">TOTAL SCORE: </span><span
                    class="value">{{ $transcript->total }}</span></p>
            <p class="mean-grade" colspan="5"><span class="caption">MEAN GRADE: </span><span
                    class="value">{{ $transcript->grade }}</span></p>
            <p class="mean-grade" colspan="5"><span class="caption">REMARKS: </span><span
                    class="value">{{ $transcript->remark }}</span></p>
            <p>Date of Issue: {{ date('j-F-Y') }}</p>
        </div>

        <div class="mt-6">
            <table class="text-sm font-medium uppercase text-center mt-24 w-full">
                <tr>
                    <td>
                        <div class="border-t border-dashed border-gray-800 py-5 mx-4 w-56">HOD's Signature</div>
                    </td>
                    <td>
                        <div class="border-t border-dashed border-gray-800 py-5 mx-4 w-56">Principal's Signature</div>
                    </td>
                    <td>
                        <div class="border-t border-dashed border-gray-800 py-5 mx-4 w-56">Parent/Guardian's Signature
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@else
    <p>No transcript provided</p>
@endif
