<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'EcoLocate' }}</title>

    {{-- Brand fonts: Plus Jakarta Sans for headings, Inter for body --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { font-family: 'Inter', sans-serif; }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-white">

    @php
        // Central nav config. Each entry lists the possible route names for
        // that page — the first one that actually exists in your app wins.
        $navResolve = function (array $routeNames) {
            foreach ($routeNames as $name) {
                if (\Illuminate\Support\Facades\Route::has($name)) {
                    return ['href' => route($name), 'active' => request()->routeIs($name . '*')];
                }
            }
            return ['href' => '#', 'active' => false];
        };

        $navLinks = [
            ['label' => 'Home',             'href' => url('/'), 'active' => request()->is('/')],
            ['label' => 'Facility Locator', ...$navResolve(['facilities.map'])],
            ['label' => 'Device Search',    ...$navResolve(['devices.search'])],
            ['label' => 'Awareness',        ...$navResolve(['ewaste.awareness', 'awareness', 'ewaste', 'awareness.index'])],
            ['label' => 'Contact Us',       ...$navResolve(['contact', 'contact.index', 'contact.show'])],
        ];
    @endphp

    {{-- ===================== NAVBAR (single source of truth for authenticated pages) ===================== --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl bg-white/80 border-b border-[#E2E8F0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Hidden checkbox that stores open/closed state for the mobile menu --}}
            <input type="checkbox" id="mobileMenuToggle" class="peer hidden">

            <div class="flex items-center justify-between h-16 gap-4">

                {{-- Brand --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <span class="w-8 h-8 rounded-full bg-[#10B981]/10 flex items-center justify-center text-[#10B981]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/>
                            <path d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12"/>
                            <path d="m14 16-3 3 3 3"/>
                            <path d="M8.293 13.596 7.196 9.5 3.1 10.598"/>
                            <path d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 12 3a1.784 1.784 0 0 1 1.545.888l3.943 6.843"/>
                            <path d="m13.378 9.633 4.096 1.098 1.097-4.096"/>
                        </svg>
                    </span>
                    <span class="font-display font-bold text-lg text-[#0B1720] whitespace-nowrap">Eco<span class="text-[#10B981]">Locate</span></span>
                </a>

                {{-- Desktop nav links --}}
                <nav class="hidden lg:flex items-center gap-1 text-sm font-medium flex-1 justify-center">
                    @foreach ($navLinks as $link)
                        <a href="{{ $link['href'] }}"
                           class="px-3 py-2 rounded-lg transition
                                  {{ $link['active']
                                        ? 'text-[#10B981] font-semibold bg-[#10B981]/10'
                                        : 'text-[#64748B] hover:text-[#10B981] hover:bg-[#10B981]/5' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </nav>

                {{-- Right: user + actions --}}
                <div class="flex items-center gap-2 shrink-0">

                    <a href="{{ route('profile.edit') }}"
                       title="My Profile"
                       class="flex items-center gap-2 pr-3 mr-1 border-r border-[#E2E8F0] hover:opacity-80 transition">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-[#10B981] to-[#34D399] text-white flex items-center justify-center font-bold text-sm font-display">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <span class="hidden lg:block text-sm font-semibold text-[#0B1720] whitespace-nowrap">{{ auth()->user()->name ?? 'User' }}</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                title="Logout"
                                class="group flex items-center gap-2 rounded-full border border-transparent
                                       px-2.5 py-2 lg:pl-4 lg:pr-3.5 lg:py-2
                                       bg-red-50 text-red-600
                                       hover:bg-red-600 hover:text-white hover:border-red-600 hover:shadow-md hover:shadow-red-600/20
                                       active:scale-[0.97] transition-all">
                            <span class="hidden lg:block text-sm font-semibold whitespace-nowrap">Logout</span>
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 5v1a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>

                    {{-- Mobile hamburger toggle --}}
                    <label for="mobileMenuToggle" class="lg:hidden relative w-9 h-9 flex items-center justify-center rounded-lg hover:bg-[#10B981]/5 cursor-pointer">
                        <svg id="mobileMenuIconOpen" class="w-6 h-6 text-[#0B1720]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="mobileMenuIconClose" class="hidden w-6 h-6 text-[#0B1720]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </label>
                </div>
            </div>

            {{-- Mobile menu panel --}}
            <div id="mobileMenuPanel" class="hidden peer-checked:flex lg:!hidden flex-col gap-1 pb-4 text-sm font-medium border-t border-[#E2E8F0] pt-3">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}"
                       class="px-3 py-2.5 rounded-lg border-l-4 transition
                              {{ $link['active']
                                    ? 'border-[#10B981] text-[#10B981] bg-[#10B981]/10 font-semibold'
                                    : 'border-transparent text-[#64748B] hover:bg-[#10B981]/5' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        (function () {
            const toggle = document.getElementById('mobileMenuToggle');
            const panel = document.getElementById('mobileMenuPanel');
            const iconOpen = document.getElementById('mobileMenuIconOpen');
            const iconClose = document.getElementById('mobileMenuIconClose');
            if (!toggle || !panel) return;

            function sync() {
                const isOpen = toggle.checked;
                panel.classList.toggle('hidden', !isOpen);
                panel.classList.toggle('flex', isOpen);
                if (iconOpen) iconOpen.classList.toggle('hidden', isOpen);
                if (iconClose) iconClose.classList.toggle('hidden', !isOpen);
            }

            toggle.addEventListener('change', sync);

            panel.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    toggle.checked = false;
                    sync();
                });
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024 && toggle.checked) {
                    toggle.checked = false;
                    sync();
                }
            });

            sync();
        })();
    </script>

    {{-- ===================== PAGE CONTENT ===================== --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    @stack('scripts')
</body>
</html>
