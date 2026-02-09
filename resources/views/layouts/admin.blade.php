<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js (dropdown, sidebar toggle etc.) --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    {{-- Datatables --}}
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">

    {{-- Select2 / Dropzone --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css">

    {{-- Custom CSS (optional) --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @yield('styles')
</head>

<body class="bg-gray-100 text-gray-800">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    @include('partials.menu')

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col">

        {{-- HEADER --}}
        <header class="bg-white shadow px-6 py-3 flex justify-between items-center">

    {{-- LEFT --}}
    <div class="flex items-center gap-4">
        <button class="lg:hidden">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <h1 class="font-semibold text-lg">
            {{ trans('panel.site_title') }}
        </h1>
    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-6">

        {{-- LANGUAGE --}}
        @if(count(config('panel.available_languages', [])) > 1)
            <div class="relative" x-data="{open:false}">
                <button @click="open = !open"
                        class="uppercase text-sm font-medium flex items-center gap-2">
                    {{ app()->getLocale() }}
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <div x-show="open"
                     x-transition
                     @click.outside="open=false"
                     class="absolute right-0 mt-2 bg-white border rounded shadow min-w-[140px]">
                    @foreach(config('panel.available_languages') as $langLocale => $langName)
                        <a class="block px-4 py-2 text-sm hover:bg-gray-100"
                           href="{{ url()->current() }}?change_language={{ $langLocale }}">
                            {{ strtoupper($langLocale) }} ({{ $langName }})
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- USER DROPDOWN --}}
        <div class="relative" x-data="{open:false}">
            <button @click="open = !open"
                    class="flex items-center gap-3 focus:outline-none">

                {{-- Avatar --}}
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                {{-- Name --}}
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-medium leading-none">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
            </button>

            {{-- DROPDOWN --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.outside="open=false"
                 class="absolute right-0 mt-2 w-56 bg-white rounded shadow border">

                <div class="px-4 py-3 border-b">
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>

                <a href="{{ route('profile.password.edit') }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                    <i class="fas fa-key text-gray-500"></i>
                    Change Password
                </a>

                <a href="#"
                   onclick="event.preventDefault(); document.getElementById('logoutform').submit();"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>


        {{-- CONTENT --}}
        <main class="flex-1 p-6">

            {{-- SUCCESS --}}
            @if(session('message'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">
                    {{ session('message') }}
                </div>
            @endif

            {{-- ERRORS --}}
            @if($errors->count() > 0)
                <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>

        {{-- LOGOUT --}}
        <form id="logoutform" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

    </div>
</div>

{{-- JS --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="//cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>

<script src="//cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>

<script>
$(function () {
    $.extend(true, $.fn.dataTable.defaults, {
        scrollX: true,
        pageLength: 25
    });
});
</script>

@yield('scripts')
</body>
</html>
