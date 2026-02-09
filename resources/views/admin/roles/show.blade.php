@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ trans('global.show') }} {{ trans('cruds.role.title') }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            View role details and assigned permissions
        </p>
    </div>

    <a href="{{ route('admin.roles.index') }}"
       class="text-sm text-blue-600 hover:underline">
        ← {{ trans('global.back_to_list') }}
    </a>
</div>

{{-- CONTENT CARD --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm max-w-3xl">

    <div class="p-8 space-y-6">

        {{-- BASIC INFO --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            {{-- ROLE ID --}}
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    {{ trans('cruds.role.fields.id') }}
                </p>
                <p class="mt-1 text-base font-semibold text-gray-900">
                    #{{ $role->id }}
                </p>
            </div>

            {{-- ROLE TITLE --}}
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    {{ trans('cruds.role.fields.title') }}
                </p>
                <p class="mt-1 text-base font-medium text-gray-900">
                    {{ $role->title }}
                </p>
            </div>

        </div>

        {{-- PERMISSIONS --}}
        <div>
            <p class="text-xs uppercase tracking-wide text-gray-500 mb-3">
                {{ trans('cruds.role.fields.permissions') }}
            </p>

            @if($role->permissions->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($role->permissions as $permission)
                        <span
                            class="px-3 py-1 text-xs rounded-md
                                   bg-gray-100 text-gray-700
                                   border border-gray-200">
                            {{ $permission->title }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">
                    No permissions assigned
                </p>
            @endif
        </div>

    </div>

    {{-- FOOTER ACTIONS --}}
    <div class="px-8 py-4 border-t bg-gray-50 flex justify-end gap-3">

        <a href="{{ route('admin.roles.index') }}"
           class="px-4 py-2 text-sm rounded-md
                  border border-gray-300 text-gray-700
                  hover:bg-gray-100">
            {{ trans('global.back_to_list') }}
        </a>

        @can('role_edit')
            <a href="{{ route('admin.roles.edit', $role->id) }}"
               class="px-4 py-2 text-sm rounded-md
                      bg-blue-600 text-white
                      hover:bg-blue-700">
                {{ trans('global.edit') }}
            </a>
        @endcan

    </div>
</div>

@endsection
