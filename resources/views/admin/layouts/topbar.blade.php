<header class="glass sticky top-0 z-20 px-6 py-4 flex items-center justify-between shadow-sm">
    <div class="text-sm font-medium text-gray-500">
        @yield('page-title', 'Dashboard')
    </div>

    <div class="flex items-center gap-4">
        <span class="text-sm font-medium text-gray-700">Welcome, {{ auth()->user()->name }}</span>

        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-500 to-cyan-500 text-white flex items-center justify-center font-semibold text-sm shadow-sm">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>
</header>