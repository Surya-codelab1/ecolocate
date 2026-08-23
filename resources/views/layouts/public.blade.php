<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EcoLocate')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Lottie player, used to render the animated logo (public/animations/logo.json) --}}
    <script src="https://unpkg.com/@lottiefiles/lottie-player@1.5.7/dist/lottie-player.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }

        .app-bg {
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdfa 45%, #e0f2fe 100%);
        }

        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>

    @stack('styles')
</head>
<body class="app-bg min-h-screen flex flex-col">

    {{-- ===================== NAVBAR ===================== --}}
    <header class="glass sticky top-0 z-50 shadow-sm">
        <div class="px-4 sm:px-6 py-3.5 sm:py-4 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0">
                <lottie-player
                    src="{{ asset('animations/logo.json') }}"
                    background="transparent"
                    speed="1"
                    style="width: 34px; height: 34px;"
                    loop
                    autoplay>
                </lottie-player>
                <span class="font-bold text-base sm:text-lg text-[#0B1720]">Eco<span class="text-[#10B981]">Locate</span></span>
            </a>

            <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-[#334155]">
                <a href="{{ url('/') }}" class="hover:text-[#10B981] transition {{ request()->is('/') ? 'text-[#10B981] font-semibold' : '' }}">Home</a>
                <a href="{{ route('facilities.map') }}" class="hover:text-[#10B981] transition {{ request()->routeIs('facilities.map') ? 'text-[#10B981] font-semibold' : '' }}">Facility Locator</a>
                <a href="{{ route('devices.search') }}" class="hover:text-[#10B981] transition {{ request()->routeIs('devices.search') ? 'text-[#10B981] font-semibold' : '' }}">Device Search</a>
                <a href="{{ route('awareness.index') }}" class="hover:text-[#10B981] transition {{ request()->routeIs('awareness.index') ? 'text-[#10B981] font-semibold' : '' }}">Awareness</a>
                <a href="{{ route('contact.index') }}" class="hover:text-[#10B981] transition {{ request()->routeIs('contact.index') ? 'text-[#10B981] font-semibold' : '' }}">Contact Us</a>
            </nav>

            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-emerald-600">My Account</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-gray-500 hover:text-red-500 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-emerald-600">Login</a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-[#10B981] to-[#34D399] text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md shadow-[#10B981]/25 hover:shadow-lg hover:-translate-y-0.5 transition-all">Get Started</a>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <button id="mobileMenuBtn" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg text-[#334155] hover:bg-black/5 transition" aria-label="Toggle menu">
                <svg id="mobileMenuIconOpen" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/>
                </svg>
                <svg id="mobileMenuIconClose" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Mobile dropdown panel --}}
        <div id="mobileMenuPanel" class="lg:hidden hidden border-t border-white/60 bg-white/90 backdrop-blur px-4 py-3 space-y-1">
            <a href="{{ url('/') }}" class="block px-2 py-2.5 rounded-lg text-sm font-medium text-[#334155] hover:bg-emerald-50 hover:text-[#10B981] transition">Home</a>
            <a href="{{ route('facilities.map') }}" class="block px-2 py-2.5 rounded-lg text-sm font-medium text-[#334155] hover:bg-emerald-50 hover:text-[#10B981] transition">Facility Locator</a>
            <a href="{{ route('devices.search') }}" class="block px-2 py-2.5 rounded-lg text-sm font-medium text-[#334155] hover:bg-emerald-50 hover:text-[#10B981] transition">Device Search</a>
            <a href="{{ route('awareness.index') }}" class="block px-2 py-2.5 rounded-lg text-sm font-medium text-[#334155] hover:bg-emerald-50 hover:text-[#10B981] transition">Awareness</a>
            <a href="{{ route('contact.index') }}" class="block px-2 py-2.5 rounded-lg text-sm font-medium text-[#334155] hover:bg-emerald-50 hover:text-[#10B981] transition">Contact Us</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="block px-2 py-2.5 rounded-lg text-sm font-medium text-[#334155] hover:bg-emerald-50 hover:text-[#10B981] transition">My Requests</a>
                <form method="POST" action="{{ route('logout') }}" class="px-2 pt-1">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm font-semibold text-gray-500 hover:text-red-500 transition py-1.5">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-2 py-2.5 rounded-lg text-sm font-medium text-[#334155] hover:bg-emerald-50 hover:text-[#10B981] transition">Login</a>
                <a href="{{ route('register') }}" class="block mx-2 mt-2 text-center bg-gradient-to-r from-[#10B981] to-[#34D399] text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-md shadow-[#10B981]/25 transition-all">Get Started</a>
            @endauth
        </div>
    </header>

    <script>
        (function () {
            const btn = document.getElementById('mobileMenuBtn');
            const panel = document.getElementById('mobileMenuPanel');
            const iconOpen = document.getElementById('mobileMenuIconOpen');
            const iconClose = document.getElementById('mobileMenuIconClose');
            if (!btn || !panel) return;

            btn.addEventListener('click', () => {
                panel.classList.toggle('hidden');
                iconOpen.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
            });
        })();
    </script>

    {{-- ===================== PAGE CONTENT ===================== --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="mt-10 bg-[#05090C] text-[#CBD5E1] relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-[#10B981] via-[#34D399] to-[#0EA5E9]"></div>

        <div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 sm:grid-cols-2 gap-10">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <lottie-player
                        src="{{ asset('animations/logo.json') }}"
                        background="transparent"
                        speed="1"
                        style="width: 34px; height: 34px;"
                        loop
                        autoplay>
                    </lottie-player>
                    <span class="font-bold text-white text-lg">Eco<span class="text-[#34D399]">Locate</span></span>
                </div>
                <p class="text-sm text-[#94A3B8] max-w-sm leading-relaxed">Find the nearest verified e-waste recycling facility, get instant directions, and schedule a pickup — all in one place.</p>
            </div>

            <div>
                <p class="text-sm font-semibold text-white mb-4 tracking-wide uppercase">Quick Links</p>
                <ul class="space-y-2.5 text-sm text-[#94A3B8]">
                    <li><a href="{{ url('/') }}" class="hover:text-[#34D399] transition">Home</a></li>
                    <li><a href="{{ route('facilities.map') }}" class="hover:text-[#34D399] transition">Facility Locator</a></li>
                    <li><a href="{{ route('devices.search') }}" class="hover:text-[#34D399] transition">Device Search</a></li>
                    <li><a href="{{ route('awareness.index') }}" class="hover:text-[#34D399] transition">Awareness</a></li>
                    <li><a href="{{ route('contact.index') }}" class="hover:text-[#34D399] transition">Contact Us</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 py-5">
            <p class="text-center text-xs text-[#64748B]">&copy; {{ date('Y') }} EcoLocate. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>