<div class=" break-before-avoid">
    <table class="w-full uppercase">
        <tr>
            <th style="width: 20%" class="px-2 py-1 border border-gray-800 bg-gray-50 text-left">
                Teacher's Name
            </th>
            <th style="width: 15%" class="px-2 py-1 border border-gray-800 bg-gray-50 text-left">
                Date
            </th>
            <th style="width: 15%" class="px-2 py-1 border border-gray-800 bg-gray-50 text-left">
                Signature
            </th>
            <th style="width: 20%" class="px-2 py-1 border border-gray-800 bg-gray-50 text-left">
                Principal's Name
            </th>
            <th style="width: 15%" class="px-2 py-1 border border-gray-800 bg-gray-50 text-left">
                Date
            </th>
            <th style="width: 15%" class="px-2 py-1 border border-gray-800 bg-gray-50 text-left">
                Signature
            </th>
        </tr>
        <tr>
            <td class="p-2 border border-gray-800">
                {{ sprintf('%s %s', $instructor->first_name, $instructor->surname ?? $instructor->middle_name) }}
            </td>
            <td class="p-2 border border-gray-800"></td>
            <td class="p-2 border border-gray-800"></td>
            <td class="p-2 border border-gray-800">
                {{ sprintf('%s %s', $principal->first_name, $principal->surname ?? $principal->middle_name) }}</td>
            <td class="p-2 border border-gray-800"></td>
            <td class="p-2 border border-gray-800"></td>
        </tr>
    </table>
</div>
