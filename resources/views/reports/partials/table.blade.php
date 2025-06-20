<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b dark:border-gray-700">
                <th class="py-2">Trainee</th>
                <th class="py-2">Module</th>
                <th class="py-2">Date</th>
                <th class="py-2">Reason</th>
                <th class="py-2">Justified</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absences as $absence)
                <tr class="border-b dark:border-gray-700">
                    <td class="py-2">{{ $absence->trainee->name }}</td>
                    <td class="py-2">{{ $absence->module->name }}</td>
                    <td class="py-2">{{ \Carbon\Carbon::parse($absence->date)->format('d/m/Y') }}</td>
                    <td class="py-2">{{ $absence->reason }}</td>
                    <td class="py-2">
                        @if ($absence->justified)
                            <span class="text-green-600 font-medium">Yes</span>
                        @else
                            <span class="text-red-600 font-medium">No</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500 dark:text-gray-400">No absences found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $absences->withQueryString()->links() }}
</div>
