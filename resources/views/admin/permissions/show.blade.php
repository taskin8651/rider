@extends('layouts.admin')
@section('content')

{{-- HEADER --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 leading-tight">
                {{ trans('global.show') }} {{ trans('cruds.permission.title') }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Permission details and configuration
            </p>
        </div>

        <a href="{{ route('admin.permissions.index') }}"
           class="inline-flex items-center text-sm text-blue-600 hover:underline">
            ← {{ trans('global.back_to_list') }}
        </a>
    </div>
</div>

{{-- CONTENT --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm max-w-2xl">

    {{-- BODY --}}
    <div class="px-8 py-6">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            {{-- ID --}}
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    {{ trans('cruds.permission.fields.id') }}
                </p>
                <p class="mt-1 text-base font-semibold text-gray-900">
                    #{{ $permission->id }}
                </p>
            </div>

            {{-- TITLE --}}
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    {{ trans('cruds.permission.fields.title') }}
                </p>
                <p class="mt-1 text-base font-medium text-gray-900">
                    {{ $permission->title }}
                </p>
            </div>

        </div>

    </div>

    {{-- FOOTER --}}
    <div class="px-8 py-4 border-t bg-gray-50 flex justify-end gap-3">

        <a href="{{ route('admin.permissions.index') }}"
           class="px-4 py-2 text-sm rounded-md
                  border border-gray-300 text-gray-700
                  hover:bg-gray-100">
            {{ trans('global.back_to_list') }}
        </a>

        @can('permission_edit')
            <a href="{{ route('admin.permissions.edit', $permission->id) }}"
               class="px-4 py-2 text-sm rounded-md
                      bg-blue-600 text-white
                      hover:bg-blue-700">
                {{ trans('global.edit') }}
            </a>
        @endcan

    </div>
</div>

@endsection
