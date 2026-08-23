<aside id="facilitySidebar" class="sidebar-panel text-white flex-shrink-0 min-h-screen w-56 flex flex-col">
    <div class="p-5 border-b border-white/10 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
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
            <div class="min-w-0">
                <div class="text-lg font-bold leading-tight truncate">Eco<span class="text-emerald-400">Locate</span></div>
                <div class="text-xs text-emerald-200/70">Facility Panel</div>
            </div>
        </div>

        {{-- Close button, mobile only --}}
        <button onclick="closeSidebar()" class="lg:hidden text-gray-300 hover:text-white shrink-0">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
            </svg>
        </button>
    </div>

    <nav class="flex-1 mt-4 space-y-1 px-3 overflow-y-auto">

        @php
            $links = [
                ['route' => 'facility.dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                ['route' => 'facility.pickups.index', 'label' => 'Pickups', 'icon' => 'package'],
                ['route' => 'facility.profile.edit', 'label' => 'Facility Profile', 'icon' => 'building'],
            ];
        @endphp

        @foreach($links as $link)
            @php
                $active = request()->routeIs($link['route']) ||
                    request()->routeIs(str_replace('.index', '', $link['route']) . '.*') ||
                    request()->routeIs(str_replace('.edit', '', $link['route']) . '.*');
            @endphp

            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-white/10 transition {{ $active ? 'bg-white/10 text-emerald-300' : 'text-gray-200' }}">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    @if($link['icon'] === 'dashboard')
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    @elseif($link['icon'] === 'package')
                        <path d="m21 8-9-5-9 5 9 5 9-5Z"/><path d="M3 8v9l9 5 9-5V8"/><path d="M12 13v9"/>
                    @elseif($link['icon'] === 'building')
                        <path d="M3 21h18"/><path d="M5 21V5l7-2v18"/><path d="M12 7h7v14"/><path d="M8 8h1"/><path d="M8 12h1"/><path d="M8 16h1"/><path d="M15 10h1"/><path d="M15 14h1"/><path d="M15 18h1"/>
                    @endif
                </svg>

                <span class="font-medium truncate">{{ $link['label'] }}</span>
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
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>

</aside>