<header class="bg-white/80 backdrop-blur-xl border-b border-[#E2E8F0] px-4 sm:px-6 py-4 flex items-center justify-between sticky top-0 z-10">
    <div class="flex items-center gap-3 min-w-0">
        {{-- Hamburger, mobile only --}}
        <button onclick="openSidebar()" class="lg:hidden text-[#0B1720] shrink-0">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="w-1 h-6 rounded-full bg-gradient-to-b from-[#10B981] to-[#34D399] hidden sm:block"></div>
        <h2 class="font-heading font-bold text-base sm:text-lg text-[#0B1720] tracking-tight truncate">
            @yield('page-title', 'Dashboard')
        </h2>
    </div>

    <div class="flex items-center gap-2 sm:gap-4 shrink-0">
        @if(isset($facility))
            <span class="hidden md:inline text-sm font-semibold text-[#0B1720] truncate max-w-[150px]">{{ $facility->facility_name }}</span>
        @endif

        <span class="hidden sm:inline text-sm text-[#64748B] truncate max-w-[120px]">{{ auth()->user()->name }}</span>

        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#10B981] to-[#22D3EE] text-white flex items-center justify-center font-semibold text-sm shadow-sm shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>
</header>