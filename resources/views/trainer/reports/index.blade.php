@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex items-center gap-3 mb-8">
            <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600 dark:text-indigo-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6h13v6m-4-6v6m-5-6v6M3 10h1l2-3h10l2 3h1" />
                </svg>
            </span>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Trainer Reports</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Complete overview of all absences with export options.</p>
            </div>
        </div>

        <!-- Filter Form -->
        <form id="filterForm" method="GET" class="flex flex-wrap gap-4 mb-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
            <select id="moduleFilter" name="module_id" class="border p-2 rounded w-48 dark:bg-gray-900 dark:border-gray-700">
                <option value="">All Modules</option>
                @foreach ($modules as $module)
                    <option value="{{ $module->id }}" {{ request('module_id') == $module->id ? 'selected' : '' }}>
                        {{ $module->name }}
                    </option>
                @endforeach
            </select>

            <input id="startDateFilter" type="date" name="start_date" value="{{ request('start_date') }}"
                class="border p-2 rounded dark:bg-gray-900 dark:border-gray-700">
            <input id="endDateFilter" type="date" name="end_date" value="{{ request('end_date') }}"
                class="border p-2 rounded dark:bg-gray-900 dark:border-gray-700">

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Filter
            </button>

            <a href="{{ route('trainer.export.absences') }}"
                class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm font-medium">
                Export to Excel
            </a>

            <a href="{{ route('trainer.reports.export.csv', request()->query()) }}"
                class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm font-medium">
                Export to CSV
            </a>

            <a href="{{ route('trainer.reports.export.pdf', request()->query()) }}"
                class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm font-medium">
                Export to PDF
            </a>

            <a href="{{ route('trainer.absence.email.summary') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm font-medium">
                Export Email Summary
            </a>
        </form>

        <!-- Live Search -->
        <div class="mb-4">
            <input id="liveSearchInput" type="text" placeholder="Search by trainee, module or reason..."
                class="w-full p-2 border rounded dark:bg-gray-900 dark:border-gray-700" />
        </div>

        <!-- Table + Pagination Container -->
        <div id="reportTableContainer">
            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b dark:border-gray-700 text-xs uppercase text-gray-600 dark:text-gray-300">
                            <th class="py-2">Trainee</th>
                            <th class="py-2">Module</th>
                            <th class="py-2">Date</th>
                            <th class="py-2">Reason</th>
                            <th class="py-2">Justified</th>
                            <th class="py-2">Logged By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($absences as $absence)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
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
                                <td class="py-2">{{ $absence->loggedBy->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                    No absences found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $absences->withQueryString()->links() }}
            </div>
        </div>
    </div>


    @section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('liveSearchInput');
        const tableRows = document.querySelectorAll('table tbody tr');

        searchInput.addEventListener('keyup', function () {
            const searchValue = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let match = false;

                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        match = true;
                    }
                });

                row.style.display = match ? '' : 'none';
            });
        });
    });
</script>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filterForm');
    const container = document.getElementById('reportTableContainer');

    form.addEventListener('change', function () {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        fetch("{{ route('trainer.reports') }}?" + params, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.text())
        .then(html => {
            container.innerHTML = html;
        });
    });
});
</script>
@endsection

@endsection
