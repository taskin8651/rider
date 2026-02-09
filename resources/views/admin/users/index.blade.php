@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ trans('cruds.user.title') }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Manage application users and their roles
        </p>
    </div>

    @can('user_create')
        <a href="{{ route('admin.users.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium
                  rounded-md hover:bg-blue-700">
            + {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
        </a>
    @endcan
</div>

{{-- TABLE CARD --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm datatable datatable-User">
            <thead class="bg-gray-50 border-b">
                <tr class="text-left text-gray-600 font-medium">
                    <th class="px-6 py-3 w-10"></th>
                    <th class="px-6 py-3">
                        {{ trans('cruds.user.fields.id') }}
                    </th>
                    <th class="px-6 py-3">
                        {{ trans('cruds.user.fields.name') }}
                    </th>
                    <th class="px-6 py-3">
                        {{ trans('cruds.user.fields.email') }}
                    </th>
                    <th class="px-6 py-3">
                        {{ trans('cruds.user.fields.email_verified_at') }}
                    </th>
                    <th class="px-6 py-3">
                        {{ trans('cruds.user.fields.roles') }}
                    </th>
                    <th class="px-6 py-3 text-right">
                        {{ trans('global.actions') }}
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition"
                        data-entry-id="{{ $user->id }}">

                        <td class="px-6 py-4"></td>

                        <td class="px-6 py-4 font-medium text-gray-900">
                            #{{ $user->id }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-600 text-white
                                            flex items-center justify-center font-semibold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $user->name }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ $user->email }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $user->email_verified_at
                                ? $user->email_verified_at->format('d M Y')
                                : '—' }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-1 text-xs rounded-md
                                                 bg-gray-100 text-gray-700
                                                 border border-gray-200">
                                        {{ $role->title }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">

                            @can('user_show')
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="inline-flex items-center px-3 py-1.5
                                          text-xs rounded-md border
                                          border-gray-300 text-gray-700
                                          hover:bg-gray-100">
                                    {{ trans('global.view') }}
                                </a>
                            @endcan

                            @can('user_edit')
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="inline-flex items-center px-3 py-1.5
                                          text-xs rounded-md border
                                          border-blue-600 text-blue-600
                                          hover:bg-blue-50">
                                    {{ trans('global.edit') }}
                                </a>
                            @endcan

                            @can('user_delete')
                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5
                                                   text-xs rounded-md border
                                                   border-red-600 text-red-600
                                                   hover:bg-red-50">
                                        {{ trans('global.delete') }}
                                    </button>
                                </form>
                            @endcan

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(function () {
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

    @can('user_delete')
    let deleteButton = {
        text: '{{ trans('global.datatables.delete') }}',
        url: "{{ route('admin.users.massDestroy') }}",
        className: 'btn-danger',
        action: function (e, dt, node, config) {
            let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id')
            });

            if (ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}');
                return;
            }

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                    headers: { 'x-csrf-token': _token },
                    method: 'POST',
                    url: config.url,
                    data: { ids: ids, _method: 'DELETE' }
                }).done(() => location.reload());
            }
        }
    };
    dtButtons.push(deleteButton);
    @endcan

    $('.datatable-User:not(.ajaxTable)').DataTable({
        buttons: dtButtons,
        order: [[1, 'desc']],
        pageLength: 25,
        scrollX: true
    });
});
</script>
@endsection
