@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto bg-white shadow rounded-lg">

    <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">
            Commission Settings
        </h2>
        <p class="text-sm text-gray-500">
            Set admin commission percentage for each ride
        </p>
    </div>

    <form method="POST"
          action="{{ route('admin.settings.commission') }}"
          class="p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Commission Percentage (%)
            </label>
            <input type="number"
                   name="commission"
                   min="0"
                   max="100"
                   step="0.01"
                   value="{{ $commission }}"
                   class="mt-1 w-full border rounded px-4 py-2 focus:ring focus:ring-slate-200"
                   required>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit"
                    class="px-6 py-2 text-sm font-semibold
                           bg-slate-900 text-white rounded
                           hover:bg-slate-800 transition">
                Save Settings
            </button>
        </div>
    </form>

</div>
@endsection
