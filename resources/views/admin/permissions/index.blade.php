@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-semibold text-gray-800">
            {{ trans('cruds.permission.title_singular') }} {{ trans('global.list') }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Manage system permissions
        </p>
    </div>

    @can('permission_create')
        <a href="{{ route('admin.permissions.create') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium
                  bg-blue-600 text-white rounded-md hover:bg-blue-700">
            + {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
        </a>
    @endcan
</div>

{{-- TABLE CARD --}}
<div class="bg-white border border-gray-200 rounded-md shadow-sm">

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm datatable-Permission">

            <thead class="bg-gray-50 border-b text-gray-600">
                <tr>
                    <th class="px-4 py-3 w-8"></th>
                    <th class="px-4 py-3 text-left">
                        {{ trans('cruds.permission.fields.id') }}
                    </th>
                    <th class="px-4 py-3 text-left">
                        {{ trans('cruds.permission.fields.title') }}
                    </th>
                    <th class="px-4 py-3 text-center">
                        {{ trans('global.actions') }}
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($permissions as $permission)
                    <tr data-entry-id="{{ $permission->id }}"
                        class="hover:bg-gray-50 transition">

                        <td class="px-4 py-2"></td>

                        <td class="px-4 py-2 font-medium text-gray-800">
                            {{ $permission->id }}
                        </td>

                        <td class="px-4 py-2 text-gray-700">
                            {{ $permission->title }}
                        </td>

                        <td class="px-4 py-2 text-center space-x-2">

                            @can('permission_show')
                                <a href="{{ route('admin.permissions.show', $permission->id) }}"
                                   class="text-blue-600 hover:underline text-xs">
                                    {{ trans('global.view') }}
                                </a>
                            @endcan

                            @can('permission_edit')
                                <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                                   class="text-indigo-600 hover:underline text-xs">
                                    {{ trans('global.edit') }}
                                </a>
                            @endcan

                            @can('permission_delete')
                                <form action="{{ route('admin.permissions.destroy', $permission->id) }}"
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

    @can('permission_delete')
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
                    headers: { 'x-csrf-token': $('meta[name="csrf-token"]').attr('content') },
                    method: 'POST',
                    url: "{{ route('admin.permissions.massDestroy') }}",
                    data: { ids: ids, _method: 'DELETE' }
                }).done(function () {
                    location.reload();
                });
            }
        }
    };
    dtButtons.push(deleteButton);
    @endcan

    $('.datatable-Permission').DataTable({
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
