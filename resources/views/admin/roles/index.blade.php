@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Manage roles and assigned permissions
        </p>
    </div>

    @can('role_create')
        <a href="{{ route('admin.roles.create') }}"
           class="inline-flex items-center px-4 py-2
                  bg-blue-600 text-white text-sm font-medium
                  rounded-md hover:bg-blue-700">
            + {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
        </a>
    @endcan
</div>

{{-- TABLE CARD --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm">

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm datatable-Role">

            {{-- HEAD --}}
            <thead class="bg-gray-50 border-b text-gray-600">
                <tr>
                    <th class="px-4 py-3 w-8"></th>
                    <th class="px-4 py-3 text-left">
                        {{ trans('cruds.role.fields.id') }}
                    </th>
                    <th class="px-4 py-3 text-left">
                        {{ trans('cruds.role.fields.title') }}
                    </th>
                    <th class="px-4 py-3 text-left">
                        {{ trans('cruds.role.fields.permissions') }}
                    </th>
                    <th class="px-4 py-3 text-center">
                        {{ trans('global.actions') }}
                    </th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody class="divide-y">
                @foreach($roles as $role)
                    <tr data-entry-id="{{ $role->id }}"
                        class="hover:bg-gray-50 transition">

                        <td class="px-4 py-2"></td>

                        <td class="px-4 py-2 font-medium text-gray-800">
                            {{ $role->id }}
                        </td>

                        <td class="px-4 py-2 text-gray-900 font-medium">
                            {{ $role->title }}
                        </td>

                        {{-- PERMISSIONS --}}
                        <td class="px-4 py-2">
                            <div class="flex flex-wrap gap-1 max-w-xl">
                                @foreach($role->permissions as $permission)
                                    <span
                                        class="px-2 py-0.5 text-xs rounded
                                               bg-gray-100 text-gray-700 border">
                                        {{ $permission->title }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-4 py-2 text-center space-x-3">

                            @can('role_show')
                                <a href="{{ route('admin.roles.show', $role->id) }}"
                                   class="text-blue-600 hover:underline text-xs">
                                    {{ trans('global.view') }}
                                </a>
                            @endcan

                            @can('role_edit')
                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                   class="text-indigo-600 hover:underline text-xs">
                                    {{ trans('global.edit') }}
                                </a>
                            @endcan

                            @can('role_delete')
                                <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit"
                                            class="text-red-600 hover:underline text-xs">
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

    @can('role_delete')
    let deleteButton = {
        text: '{{ trans('global.datatables.delete') }}',
        className: 'btn-danger',
        action: function (e, dt) {
            let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id');
            });

            if (ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}');
                return;
            }

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                    headers: {
                        'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    method: 'POST',
                    url: "{{ route('admin.roles.massDestroy') }}",
                    data: { ids: ids, _method: 'DELETE' }
                }).done(function () {
                    location.reload();
                });
            }
        }
    };
    dtButtons.push(deleteButton);
    @endcan

    $('.datatable-Role').DataTable({
        buttons: dtButtons,
        order: [[1, 'desc']],
        pageLength: 25,
        scrollX: true,
        select: {
            style: 'multi+shift',
            selector: 'td:first-child'
        }
    });

});
</script>
@endsection
