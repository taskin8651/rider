@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ trans('global.show') }} {{ trans('cruds.user.title_singular') }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            View user details and assigned roles
        </p>
    </div>

    <a href="{{ route('admin.users.index') }}"
       class="text-sm text-blue-600 hover:underline">
        ← {{ trans('global.back_to_list') }}
    </a>
</div>

{{-- MAIN CARD --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm max-w-4xl">

    <div class="p-8 space-y-8">

        {{-- USER HEADER --}}
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-full bg-blue-600 text-white
                        flex items-center justify-center text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ $user->name }}
                </h2>
                <p class="text-gray-500 text-sm">
                    {{ $user->email }}
                </p>
            </div>
        </div>

        {{-- USER DETAILS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            {{-- USER ID --}}
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    {{ trans('cruds.user.fields.id') }}
                </p>
                <p class="mt-1 font-medium text-gray-900">
                    #{{ $user->id }}
                </p>
            </div>

            {{-- EMAIL VERIFIED --}}
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    {{ trans('cruds.user.fields.email_verified_at') }}
                </p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $user->email_verified_at
                        ? $user->email_verified_at->format('d M Y, H:i')
                        : 'Not verified' }}
                </p>
            </div>

        </div>

        {{-- ROLES --}}
        <div>
            <p class="text-xs uppercase tracking-wide text-gray-500 mb-3">
                {{ trans('cruds.user.fields.roles') }}
            </p>

            @if($user->roles->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($user->roles as $role)
                        <span class="px-3 py-1 text-xs rounded-md
                                     bg-gray-100 text-gray-700
                                     border border-gray-200">
                            {{ $role->title }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">
                    No roles assigned
                </p>
            @endif
        </div>

    </div>

    {{-- FOOTER ACTIONS --}}
    <div class="px-8 py-4 border-t bg-gray-50 flex justify-end gap-3">

        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2 text-sm rounded-md
                  border border-gray-300 text-gray-700
                  hover:bg-gray-100">
            {{ trans('global.back_to_list') }}
        </a>

        @can('user_edit')
            <a href="{{ route('admin.users.edit', $user->id) }}"
               class="px-4 py-2 text-sm rounded-md
                      bg-blue-600 text-white
                      hover:bg-blue-700">
                {{ trans('global.edit') }}
            </a>
        @endcan

    </div>
</div>

@endsection
