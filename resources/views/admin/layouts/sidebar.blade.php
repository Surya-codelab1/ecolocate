<aside id="adminSidebar" class="sidebar-panel text-white flex-shrink-0 min-h-screen w-64 transition-all duration-300 relative flex flex-col">

    <button onclick="toggleSidebar()"
            class="absolute -right-3 top-6 bg-emerald-500 hover:bg-emerald-600 rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-lg z-10">
        &lt;
    </button>

    <div class="p-5 border-b border-white/10 flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <path d="m7 19-2-3.5 2.5-4.3"/>
                <path d="M5 15.5h5"/>
                <path d="m17 5 2 3.5-2.5 4.3"/>
                <path d="M19 8.5h-5"/>
                <path d="m8.5 5.5 4-2.3 2.5 4.3"/>
                <path d="m15 18.5-4 2.3-2.5-4.3"/>
                <path d="M12.5 3.2 15 7.5"/>
                <path d="M11.5 20.8 9 16.5"/>
            </svg>
        </div>
        <div class="sidebar-logo-text">
            <div class="text-lg font-bold leading-tight">Eco<span class="text-emerald-400">Locate</span></div>
            <div class="text-xs text-emerald-200/70">Admin Console</div>
        </div>
    </div>

    <nav class="flex-1 mt-4 space-y-1 px-3 overflow-y-auto">

        @php
            $links = [
                ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                ['route' => 'admin.facility-requests.index', 'label' => 'Facility Requests', 'icon' => 'inbox'],
                ['route' => 'admin.facilities.index', 'label' => 'Facilities', 'icon' => 'building'],
                ['route' => 'admin.devices.index', 'label' => 'Devices', 'icon' => 'device'],
             //   ['route' => 'admin.device-requests.index', 'label' => 'Device Requests', 'icon' => 'clipboard'],
                ['route' => 'admin.pickups.index', 'label' => 'Pickups', 'icon' => 'package'],
                ['route' => 'admin.cities.index', 'label' => 'Cities', 'icon' => 'city'],
                ['route' => 'admin.messages.index', 'label' => 'Messages', 'icon' => 'message'],
                ['route' => 'admin.analytics.index', 'label' => 'Analytics', 'icon' => 'analytics'],
            ];
        @endphp

        @foreach($links as $link)
            @php
                $active = request()->routeIs($link['route']) ||
                    request()->routeIs(str_replace('.index', '', $link['route']) . '.*');
            @endphp

            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-white/10 transition {{ $active ? 'bg-white/10 text-emerald-300' : 'text-gray-200' }}">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    @if($link['icon'] === 'dashboard')
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    @elseif($link['icon'] === 'inbox')
                        <path d="M4 5h16v14H4z"/><path d="M4 13h4l2 3h4l2-3h4"/><path d="M8 9h8"/>
                    @elseif($link['icon'] === 'building')
                        <path d="M3 21h18"/><path d="M5 21V5l7-2v18"/><path d="M12 7h7v14"/><path d="M8 8h1"/><path d="M8 12h1"/><path d="M8 16h1"/><path d="M15 10h1"/><path d="M15 14h1"/><path d="M15 18h1"/>
                    @elseif($link['icon'] === 'device')
                        <rect x="7" y="2" width="10" height="20" rx="2.5"/><path d="M11 18h2"/>
                    @elseif($link['icon'] === 'clipboard')
                        <rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V2h6v2"/><path d="M9 10h6"/><path d="M9 14h4"/>
                    @elseif($link['icon'] === 'package')
                        <path d="m21 8-9-5-9 5 9 5 9-5Z"/><path d="M3 8v9l9 5 9-5V8"/><path d="M12 13v9"/>
                    @elseif($link['icon'] === 'city')
                        <path d="M3 21h18"/><path d="M5 21V7l7-4v18"/><path d="M12 10h7v11"/><path d="M8 9h1"/><path d="M8 13h1"/><path d="M8 17h1"/><path d="M15 13h1"/><path d="M15 17h1"/>
                    @elseif($link['icon'] === 'message')
                        <path d="M4 5h16v11H8l-4 4V5Z"/><path d="M8 9h8"/><path d="M8 12h5"/>
                    @elseif($link['icon'] === 'analytics')
                        <path d="M4 19V5"/><path d="M4 19h17"/><path d="m7 15 4-4 3 2 5-7"/><circle cx="7" cy="15" r="1"/><circle cx="11" cy="11" r="1"/><circle cx="14" cy="13" r="1"/><circle cx="19" cy="6" r="1"/>
                    @endif
                </svg>

                <span class="sidebar-label font-medium">{{ $link['label'] }}</span>
            </a>
        @endforeach

    </nav>

    <div class="mt-auto w-full px-3 py-4 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-gray-300 hover:text-red-300 hover:bg-white/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 3v18"/>
                </svg>
                <span class="sidebar-label font-medium">Logout</span>
            </button>
        </form>
    </div>

</aside>