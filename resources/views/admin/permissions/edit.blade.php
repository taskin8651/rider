@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-semibold text-gray-800">
            {{ trans('global.edit') }} {{ trans('cruds.permission.title_singular') }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Update existing permission details
        </p>
    </div>

    <a href="{{ route('admin.permissions.index') }}"
       class="text-sm text-blue-600 hover:underline">
        ← {{ trans('global.back_to_list') }}
    </a>
</div>

{{-- FORM CARD --}}
<div class="bg-white border border-gray-200 rounded-md shadow-sm max-w-xl">

    <form method="POST"
          action="{{ route('admin.permissions.update', $permission->id) }}"
          class="p-6 space-y-6">
        @method('PUT')
        @csrf

        {{-- TITLE --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                {{ trans('cruds.permission.fields.title') }}
                <span class="text-red-500">*</span>
            </label>

            <input type="text"
                   name="title"
                   id="title"
                   value="{{ old('title', $permission->title) }}"
                   required
                   class="w-full px-3 py-2 border rounded-md text-sm
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                          {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }}">

            @if($errors->has('title'))
                <p class="mt-1 text-xs text-red-600">
                    {{ $errors->first('title') }}
                </p>
            @endif

            @if(trans('cruds.permission.fields.title_helper'))
                <p class="mt-1 text-xs text-gray-500">
                    {{ trans('cruds.permission.fields.title_helper') }}
                </p>
            @endif
        </div>

        {{-- ACTIONS --}}
        <div class="flex items-center gap-3 pt-4">
            <button type="submit"
                    class="inline-flex items-center px-4 py-2
                           bg-blue-600 text-white text-sm font-medium
                           rounded-md hover:bg-blue-700">
                {{ trans('global.save') }}
            </button>

            <a href="{{ route('admin.permissions.index') }}"
               class="text-sm text-gray-600 hover:underline">
                {{ trans('global.cancel') }}
            </a>
        </div>

    </form>
</div>

@endsection
