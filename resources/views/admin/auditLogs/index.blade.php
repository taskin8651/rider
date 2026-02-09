@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                {{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }}
            </h1>
            <p class="text-gray-600 text-sm mt-1">
                Track and monitor system activities
            </p>
        </div>

        {{-- SEARCH --}}
        <div class="relative">
            <input type="text"
                   id="globalSearch"
                   placeholder="Search logs..."
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                          w-64 text-sm">
            <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400"
                 fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817
                      4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border rounded-lg p-4">
        <p class="text-sm text-gray-500">Total Logs</p>
        <p class="text-2xl font-bold text-gray-800">{{ $auditLogs->total() }}</p>
    </div>

    <div class="bg-white border rounded-lg p-4">
        <p class="text-sm text-gray-500">Today</p>
        <p class="text-2xl font-bold text-gray-800">
            {{ $auditLogs->where('created_at', '>=', today())->count() }}
        </p>
    </div>

    <div class="bg-white border rounded-lg p-4">
        <p class="text-sm text-gray-500">Users</p>
        <p class="text-2xl font-bold text-gray-800">
            {{ $auditLogs->pluck('user_id')->unique()->count() }}
        </p>
    </div>

    <div class="bg-white border rounded-lg p-4">
        <p class="text-sm text-gray-500">Models</p>
        <p class="text-2xl font-bold text-gray-800">
            {{ $auditLogs->pluck('subject_type')->unique()->count() }}
        </p>
    </div>
</div>

{{-- TABLE CARD --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm datatable-AuditLog">

            <thead class="bg-gray-50 border-b text-gray-700">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Action</th>
                    <th class="px-6 py-3">Subject</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">IP</th>
                    <th class="px-6 py-3">Time</th>
                    <th class="px-6 py-3 text-center">View</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($auditLogs as $auditLog)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-3 font-medium">
                            {{ $auditLog->id }}
                        </td>

                        <td class="px-6 py-3 text-gray-700">
                            {{ ucfirst($auditLog->description) }}
                        </td>

                        <td class="px-6 py-3">
                            {{ $auditLog->subject_id }}
                        </td>

                        <td class="px-6 py-3 text-xs text-gray-600">
                            {{ class_basename($auditLog->subject_type) }}
                        </td>

                        <td class="px-6 py-3">
                            {{ $auditLog->user?->name ?? 'System' }}
                        </td>

                        <td class="px-6 py-3 text-xs text-gray-600">
                            {{ $auditLog->host }}
                        </td>

                        <td class="px-6 py-3 text-xs text-gray-500">
                            {{ $auditLog->created_at->format('d M Y, H:i') }}
                        </td>

                        <td class="px-6 py-3 text-center">
                            @can('audit_log_show')
                                <a href="{{ route('admin.audit-logs.show', $auditLog->id) }}"
                                   class="text-blue-600 hover:underline text-xs">
                                    View
                                </a>
                            @endcan
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    {{-- PAGINATION (SAFE & ERROR FREE) --}}
    <div class="px-6 py-4 border-t bg-gray-50 flex justify-between items-center text-sm">
        <div class="text-gray-600">
            Showing {{ $auditLogs->firstItem() }} to {{ $auditLogs->lastItem() }}
            of {{ $auditLogs->total() }} entries
        </div>

        <div>
            {{ $auditLogs->links() }}
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(function () {

    let table = $('.datatable-AuditLog').DataTable({
        paging: false, // Laravel pagination use ho rahi hai
        searching: true,
        ordering: true,
        order: [[0, 'desc']],
        info: false
    });

    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

});
</script>
@endsection
