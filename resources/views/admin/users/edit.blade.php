@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">
            {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Update user information and assigned roles
        </p>
    </div>

    <a href="{{ route('admin.users.index') }}"
       class="text-sm text-blue-600 hover:underline">
        ← {{ trans('global.back_to_list') }}
    </a>
</div>

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">
@method('PUT')
@csrf

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- USER INFO CARD --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">
            User Information
        </h2>

        {{-- NAME --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                {{ trans('cruds.user.fields.name') }}
                <span class="text-red-500">*</span>
            </label>

            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name', $user->name) }}"
                   required
                   class="w-full px-3 py-2 border rounded-md text-sm
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                          {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">

            @if($errors->has('name'))
                <p class="mt-1 text-xs text-red-600">
                    {{ $errors->first('name') }}
                </p>
            @endif
        </div>

        {{-- EMAIL --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                {{ trans('cruds.user.fields.email') }}
                <span class="text-red-500">*</span>
            </label>

            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email', $user->email) }}"
                   required
                   class="w-full px-3 py-2 border rounded-md text-sm
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                          {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">

            @if($errors->has('email'))
                <p class="mt-1 text-xs text-red-600">
                    {{ $errors->first('email') }}
                </p>
            @endif
        </div>

        {{-- PASSWORD (OPTIONAL) --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                {{ trans('cruds.user.fields.password') }}
            </label>

            <input type="password"
                   name="password"
                   id="password"
                   placeholder="Leave blank to keep current password"
                   class="w-full px-3 py-2 border rounded-md text-sm
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                          {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}">

            @if($errors->has('password'))
                <p class="mt-1 text-xs text-red-600">
                    {{ $errors->first('password') }}
                </p>
            @endif

            <p class="mt-1 text-xs text-gray-500">
                {{ trans('cruds.user.fields.password_helper') }}
            </p>
        </div>
    </div>

    {{-- ROLES CARD --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                {{ trans('cruds.user.fields.roles') }}
            </h2>

            <div class="flex gap-3">
                <button type="button" id="select-all"
                        class="text-xs text-blue-600 hover:underline">
                    {{ trans('global.select_all') }}
                </button>

                <button type="button" id="deselect-all"
                        class="text-xs text-blue-600 hover:underline">
                    {{ trans('global.deselect_all') }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[260px] overflow-y-auto pr-2">

            @foreach($roles as $id => $role)
                <label class="flex items-start gap-2 text-sm cursor-pointer">
                    <input type="checkbox"
                           name="roles[]"
                           value="{{ $id }}"
                           class="role-checkbox mt-1 rounded border-gray-300
                                  text-blue-600 focus:ring-blue-500"
                           {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'checked' : '' }}>

                    <span class="text-gray-700">
                        {{ $role }}
                    </span>
                </label>
            @endforeach

        </div>

        @if($errors->has('roles'))
            <p class="mt-2 text-xs text-red-600">
                {{ $errors->first('roles') }}
            </p>
        @endif

        <p class="mt-2 text-xs text-gray-500">
            {{ trans('cruds.user.fields.roles_helper') }}
        </p>
    </div>

</div>

{{-- ACTIONS --}}
<div class="mt-8 flex items-center gap-4">
    <button type="submit"
            class="px-6 py-2 bg-blue-600 text-white text-sm font-medium
                   rounded-md hover:bg-blue-700">
        {{ trans('global.save') }}
    </button>

    <a href="{{ route('admin.users.index') }}"
       class="text-sm text-gray-600 hover:underline">
        {{ trans('global.cancel') }}
    </a>
</div>

</form>

@endsection

@section('scripts')
@parent
<script>
document.getElementById('select-all').addEventListener('click', function () {
    document.querySelectorAll('.role-checkbox').forEach(cb => cb.checked = true);
});

document.getElementById('deselect-all').addEventListener('click', function () {
    document.querySelectorAll('.role-checkbox').forEach(cb => cb.checked = false);
});
</script>
@endsection
