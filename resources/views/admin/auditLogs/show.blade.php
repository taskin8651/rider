@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-xl font-semibold text-gray-800">
        {{ trans('global.show') }} {{ trans('cruds.auditLog.title') }}
    </h1>

    <a href="{{ route('admin.audit-logs.index') }}"
       class="text-sm text-blue-600 hover:underline">
        ← {{ trans('global.back_to_list') }}
    </a>
</div>

{{-- CARD --}}
<div class="bg-white border border-gray-200 rounded-md shadow-sm">

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

            {{-- ID --}}
            <div>
                <p class="text-gray-500">{{ trans('cruds.auditLog.fields.id') }}</p>
                <p class="font-medium text-gray-800">{{ $auditLog->id }}</p>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <p class="text-gray-500">{{ trans('cruds.auditLog.fields.description') }}</p>
                <p class="font-medium text-gray-800">
                    {{ $auditLog->description }}
                </p>
            </div>

            {{-- SUBJECT ID --}}
            <div>
                <p class="text-gray-500">{{ trans('cruds.auditLog.fields.subject_id') }}</p>
                <p class="font-medium text-gray-800">
                    {{ $auditLog->subject_id }}
                </p>
            </div>

            {{-- SUBJECT TYPE --}}
            <div>
                <p class="text-gray-500">{{ trans('cruds.auditLog.fields.subject_type') }}</p>
                <p class="font-medium text-gray-800">
                    {{ $auditLog->subject_type }}
                </p>
            </div>

            {{-- USER --}}
            <div>
                <p class="text-gray-500">{{ trans('cruds.auditLog.fields.user_id') }}</p>
                <p class="font-medium text-gray-800">
                    {{ $auditLog->user?->name ?? 'System' }}
                    <span class="text-xs text-gray-500">
                        (ID: {{ $auditLog->user_id }})
                    </span>
                </p>
            </div>

            {{-- HOST --}}
            <div>
                <p class="text-gray-500">{{ trans('cruds.auditLog.fields.host') }}</p>
                <p class="font-mono text-xs bg-gray-50 inline-block px-2 py-1 rounded">
                    {{ $auditLog->host }}
                </p>
            </div>

            {{-- CREATED AT --}}
            <div>
                <p class="text-gray-500">{{ trans('cruds.auditLog.fields.created_at') }}</p>
                <p class="font-medium text-gray-800">
                    {{ $auditLog->created_at->format('d M Y, H:i:s') }}
                </p>
            </div>

            {{-- PROPERTIES --}}
            <div class="md:col-span-2">
                <p class="text-gray-500 mb-1">
                    {{ trans('cruds.auditLog.fields.properties') }}
                </p>
                <pre class="bg-gray-50 border rounded p-4 text-xs overflow-x-auto">
{{ json_encode($auditLog->properties, JSON_PRETTY_PRINT) }}
                </pre>
            </div>

        </div>

    </div>

    {{-- FOOTER --}}
    <div class="px-6 py-4 border-t bg-gray-50 flex justify-end">
        <a href="{{ route('admin.audit-logs.index') }}"
           class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-100">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
</div>

@endsection
