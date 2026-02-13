<aside
    class="w-64 bg-slate-900 text-slate-100 min-h-screen hidden lg:flex flex-col
           transition-all duration-300 ease-in-out">

    {{-- BRAND --}}
    <div class="px-6 py-4 text-xl font-semibold border-b border-slate-700
                tracking-wide">
        {{ trans('panel.site_title') }}
    </div>

    {{-- NAV --}}
    <nav class="flex-1 px-3 py-4 space-y-1 text-sm">

        {{-- DASHBOARD --}}
        <a href="{{ route('admin.home') }}"
           class="group flex items-center gap-3 px-3 py-2 rounded transition
           {{ request()->routeIs('admin.home')
                ? 'bg-slate-800 text-white'
                : 'hover:bg-slate-800 hover:pl-4' }}">
            <i class="fas fa-tachometer-alt text-slate-400 group-hover:text-white transition"></i>
            {{ trans('global.dashboard') }}
        </a>

        {{-- USER MANAGEMENT --}}
        @can('user_management_access')
            <div x-data="{ open:
                {{ request()->is('admin/permissions*')
                || request()->is('admin/roles*')
                || request()->is('admin/users*')
                || request()->is('admin/audit-logs*') ? 'true' : 'false' }}
            }">

                <button @click="open = !open"
                        class="group w-full flex items-center justify-between px-3 py-2 rounded
                               hover:bg-slate-800 transition">
                    <span class="flex items-center gap-3">
                        <i class="fas fa-users text-slate-400 group-hover:text-white transition"></i>
                        {{ trans('cruds.userManagement.title') }}
                    </span>

                    <i class="fas fa-chevron-down text-xs transition-transform duration-300"
                       :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- DROPDOWN --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="ml-6 mt-1 space-y-1">

                    @can('permission_access')
                        <a href="{{ route('admin.permissions.index') }}"
                           class="block px-3 py-2 rounded transition
                           {{ request()->is('admin/permissions*')
                                ? 'bg-slate-800 text-white'
                                : 'hover:bg-slate-800 hover:pl-4' }}">
                            {{ trans('cruds.permission.title') }}
                        </a>
                    @endcan

                    @can('role_access')
                        <a href="{{ route('admin.roles.index') }}"
                           class="block px-3 py-2 rounded transition
                           {{ request()->is('admin/roles*')
                                ? 'bg-slate-800 text-white'
                                : 'hover:bg-slate-800 hover:pl-4' }}">
                            {{ trans('cruds.role.title') }}
                        </a>
                    @endcan

                    @can('user_access')
                        <a href="{{ route('admin.users.index') }}"
                           class="block px-3 py-2 rounded transition
                           {{ request()->is('admin/users*')
                                ? 'bg-slate-800 text-white'
                                : 'hover:bg-slate-800 hover:pl-4' }}">
                            {{ trans('cruds.user.title') }}
                        </a>
                    @endcan

                    @can('audit_log_access')
                        <a href="{{ route('admin.audit-logs.index') }}"
                           class="block px-3 py-2 rounded transition
                           {{ request()->is('admin/audit-logs*')
                                ? 'bg-slate-800 text-white'
                                : 'hover:bg-slate-800 hover:pl-4' }}">
                            {{ trans('cruds.auditLog.title') }}
                        </a>
                    @endcan

                </div>
            </div>
        @endcan

      @can('manage_rides')

    {{-- ALL RIDES --}}
    <a href="{{ route('admin.rides.index') }}"
       class="group flex items-center gap-3 px-3 py-2 rounded transition
       {{ request()->is('admin/rides*')
            ? 'bg-slate-800 text-white'
            : 'hover:bg-slate-800 hover:pl-4' }}">
        <i class="fas fa-taxi text-slate-400 group-hover:text-white transition"></i>
        <span>All Rides</span>
    </a>

    {{-- COMMISSION SETTINGS --}}
    <a href="{{ route('admin.settings.commission') }}"
       class="group flex items-center gap-3 px-3 py-2 rounded transition
       {{ request()->is('admin/settings/commission')
            ? 'bg-slate-800 text-white'
            : 'hover:bg-slate-800 hover:pl-4' }}">
        <i class="fas fa-percentage text-slate-400 group-hover:text-white transition"></i>
        <span>Commission Settings</span>
    </a>

    <a href="{{ route('admin.payouts.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded transition
   {{ request()->is('admin/payouts*')
        ? 'bg-slate-800 text-white'
        : 'hover:bg-slate-800 hover:pl-4' }}">
    <i class="fas fa-hand-holding-usd text-slate-400 group-hover:text-white transition"></i>
    <span>Payout Requests</span>
</a>

@endcan



@can('book_ride')
<a href="{{ route('rides.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded transition
   {{ request()->is('rides*')
        ? 'bg-slate-800 text-white'
        : 'hover:bg-slate-800 hover:pl-4' }}">
    <i class="fas fa-map-marker-alt text-slate-400"></i>
    My Rides
</a>
@endcan

@can('accept_ride')
<a href="{{ route('driver.rides.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded transition
   {{ request()->is('driver/rides*')
        ? 'bg-slate-800 text-white'
        : 'hover:bg-slate-800 hover:pl-4' }}">
    <i class="fas fa-motorcycle text-slate-400 group-hover:text-white transition"></i>
    Driver Rides
</a>

<a href="{{ route('driver.payouts.index') }}"
   class="group flex items-center gap-3 px-3 py-2 rounded transition
   {{ request()->is('driver/payouts*')
        ? 'bg-slate-800 text-white'
        : 'hover:bg-slate-800 hover:pl-4' }}">
    <i class="fas fa-wallet text-slate-400 group-hover:text-white transition"></i>
    <span>My Payouts</span>
</a>
@endcan


        {{-- CHANGE PASSWORD --}}
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <a href="{{ route('profile.password.edit') }}"
                   class="group flex items-center gap-3 px-3 py-2 rounded transition
                   {{ request()->is('profile/password*')
                        ? 'bg-slate-800 text-white'
                        : 'hover:bg-slate-800 hover:pl-4' }}">
                    <i class="fas fa-key text-slate-400 group-hover:text-white transition"></i>
                    {{ trans('global.change_password') }}
                </a>
            @endcan
        @endif

    </nav>

    {{-- LOGOUT --}}
    <div class="border-t border-slate-700 p-3">
        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logoutform').submit();"
           class="group flex items-center gap-3 px-3 py-2 rounded transition
                  hover:bg-red-600 hover:text-white">
            <i class="fas fa-sign-out-alt transition group-hover:translate-x-1"></i>
            {{ trans('global.logout') }}
        </a>
    </div>

</aside>
