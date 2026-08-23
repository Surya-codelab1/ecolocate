{{--
    Home page.
    Assumes the shared layout lives at resources/views/layouts/app.blade.php
    and exposes @yield('content') plus @stack('styles') / @stack('scripts').
--}}
@extends('components.app')

@section('title', 'EcoLocate — Find E-Waste Recycling Centers Near You')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Display face for headings — rounded letterforms, softer and friendlier
           than a geometric/sharp face, matching the pill-shaped CTAs and badges. */
        .font-display { font-family: 'Nunito', sans-serif; }

        /* Utility font for stat numbers, eyebrows and step markers. */
        .font-mono-tech { font-family: 'Space Mono', monospace; }

        /*
            Hero background: ONE flat color across the whole section — the same
            color the ambient video sits on — so there is no seam where the
            video meets the page. If your actual hero-loop.mp4 background isn't
            exactly #E8E8EB, update this single variable to match it and both
            the section and the video overlay below will follow.
        */
        :root { --hero-bg: #E8E8EB; }
        .hero-shell { background: var(--hero-bg); }

        /* Keyword highlight — a filled chip instead of an underline, so it
           reads clearly as an accent against the flat gray hero background. */
        .kw-chip {
            display: inline-flex;
            border-radius: 0.75rem;
            padding: 0.05em 0.5em;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(11, 23, 32, 0.06);
        }

        /* Ambient "not-quite-a-video" treatment: desaturate + tint so the loop
           reads as a soft animated texture rather than raw camera footage. */
        .ambient-video {
            filter: grayscale(35%) brightness(1.05) contrast(0.95);
            mix-blend-mode: luminosity;
            opacity: 0.9;
        }

        @keyframes count-fade-in {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .stat-visible { animation: count-fade-in 0.5s ease-out both; }

        @keyframes live-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }
        .live-dot { animation: live-pulse 1.6s ease-in-out infinite; }

        @media (prefers-reduced-motion: reduce) {
            .stat-visible, .live-dot { animation: none; }
        }
    </style>
@endpush

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="hero-shell relative overflow-hidden">
        {{-- Signature background motif: circuit traces resolving into leaf veins —
             the visual thesis of the page (electronics becoming eco-responsible). --}}
        <svg class="pointer-events-none absolute inset-0 w-full h-full opacity-[0.05]" viewBox="0 0 1200 700" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M0 120 H180 V60 H360 V180 H520" stroke="#0B1720" stroke-width="2"/>
            <circle cx="180" cy="120" r="5" fill="#0B1720"/>
            <circle cx="360" cy="60" r="5" fill="#0B1720"/>
            <path d="M520 180 C 560 200, 570 240, 540 260 C 510 280, 520 320, 560 330" stroke="#0EA5E9" stroke-width="2" fill="none"/>
        </svg>

        <div class="max-w-6xl mx-auto px-6 pt-10 pb-14 sm:pt-12 sm:pb-16 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center relative">

            {{-- Left: heading, description, CTAs --}}
            <div class="relative z-10">
                <h1 class="font-display text-4xl sm:text-5xl font-extrabold text-[#0B1720] leading-[1.2] tracking-tight">
                    Locate, and
                    <span class="kw-chip text-[#10B981]">recycle</span>
                    your E-Waste
                    <span class="kw-chip text-[#0EA5E9]">and Earn Credits</span>.
                </h1>

                <p class="mt-6 text-base sm:text-lg text-[#334155] max-w-lg leading-relaxed border-l-2 border-[#10B981]/40 pl-4">
                    EcoLocate connects you with verified e-waste recycling facilities near you,
                    shows you how to safely dispose of any device, and lets you schedule a
                    pickup in minutes — no guesswork, no landfill.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('facilities.map') }}"
                       class="inline-flex items-center gap-1.5 bg-gradient-to-r from-[#10B981] to-[#34D399] text-white text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-full shadow-md shadow-[#10B981]/25 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Find a Recycling Center
                    </a>
                    <a href="{{ route('devices.search') }}"
                       class="inline-flex items-center gap-1.5 bg-white text-[#0B1720] text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-full border border-[#0B1720]/10 hover:border-[#10B981]/40 hover:text-[#10B981] transition-all">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="9" y1="18" x2="15" y2="18"/></svg>
                        Search Your Device
                    </a>
                </div>
            </div>

            {{-- Right: ambient looping video. The section background and the
                 video sit on the exact same color (--hero-bg), so the video's
                 edges disappear into the page instead of showing a seam. --}}
            <div class="relative z-10">
                <div class="relative h-[220px] sm:h-[280px] lg:h-[340px]">
                    <video
                        class="ambient-video absolute inset-0 w-full h-full object-cover"
                        src="{{ asset('videos/Ecolocate.mp4') }}"
                        poster="{{ asset('images/hero-poster.jpg') }}"
                        autoplay muted loop playsinline
                        aria-hidden="true">
                    </video>
                    <div class="absolute inset-0" style="background: radial-gradient(circle at center, transparent 45%, var(--hero-bg) 100%);"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== LIVE STATS ===================== --}}
    <section class="border-y border-[#0B1720]/5 bg-white/60">
        <div class="max-w-6xl mx-auto px-6 py-14">
            <div class="max-w-xl mx-auto text-center mb-10">
                <span class="inline-flex items-center gap-1.5 font-mono-tech text-xs tracking-[0.2em] uppercase text-[#10B981] font-bold">
                    <span class="live-dot w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                    Live platform data
                </span>
                <h2 class="font-display mt-2 text-2xl sm:text-3xl font-extrabold text-[#0B1720] tracking-tight">Real numbers, updated as they happen</h2>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6" id="statGrid">
                <div class="rounded-2xl bg-white shadow-sm border border-[#0B1720]/5 px-5 py-6 text-center">
                    <p class="font-mono-tech text-3xl sm:text-4xl font-bold text-[#0B1720]"><span class="js-counter" data-stat="users" data-target="{{ $stats['users'] ?? 1200 }}">0</span>+</p>
                    <p class="mt-2 text-xs sm:text-sm text-[#64748B] font-medium">Users online right now</p>
                </div>
                <div class="rounded-2xl bg-white shadow-sm border border-[#0B1720]/5 px-5 py-6 text-center">
                    <p class="font-mono-tech text-3xl sm:text-4xl font-bold text-[#10B981]"><span class="js-counter" data-stat="facilities" data-target="{{ $stats['facilities'] ?? 150 }}">0</span>+</p>
                    <p class="mt-2 text-xs sm:text-sm text-[#64748B] font-medium">Verified recycling centers</p>
                </div>
                <div class="rounded-2xl bg-white shadow-sm border border-[#0B1720]/5 px-5 py-6 text-center">
                    <p class="font-mono-tech text-3xl sm:text-4xl font-bold text-[#0EA5E9]"><span class="js-counter" data-stat="pickups" data-target="{{ $stats['pickups'] ?? 3400 }}">0</span>+</p>
                    <p class="mt-2 text-xs sm:text-sm text-[#64748B] font-medium">Pickup &amp; drop-off requests completed</p>
                </div>
                <div class="rounded-2xl bg-white shadow-sm border border-[#0B1720]/5 px-5 py-6 text-center">
                    <p class="font-mono-tech text-3xl sm:text-4xl font-bold text-[#0B1720]"><span class="js-counter" data-stat="cities" data-target="{{ $stats['cities'] ?? 42 }}">0</span>+</p>
                    <p class="mt-2 text-xs sm:text-sm text-[#64748B] font-medium">Cities covered</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== HOW IT WORKS ===================== --}}
    <section class="max-w-6xl mx-auto px-6 py-20">
        <div class="max-w-xl mb-12">
            <span class="font-mono-tech text-xs tracking-[0.2em] uppercase text-[#0EA5E9] font-bold">How it works</span>
            <h2 class="font-display mt-2 text-2xl sm:text-3xl font-extrabold text-[#0B1720] tracking-tight">From an old device to a responsibly recycled one, in three steps</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="relative pl-1">
                <span class="font-mono-tech text-sm text-[#10B981]/60 font-bold">01</span>
                <div class="mt-3 w-10 h-10 rounded-lg bg-[#10B981]/10 flex items-center justify-center text-[#10B981]">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </div>
                <h3 class="font-display mt-4 font-bold text-[#0B1720]">Search your device or location</h3>
                <p class="mt-2 text-sm text-[#64748B] leading-relaxed">Look up disposal guidelines for a specific device, or search facilities near your address.</p>
            </div>
            <div class="relative pl-1">
                <span class="font-mono-tech text-sm text-[#10B981]/60 font-bold">02</span>
                <div class="mt-3 w-10 h-10 rounded-lg bg-[#10B981]/10 flex items-center justify-center text-[#10B981]">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <h3 class="font-display mt-4 font-bold text-[#0B1720]">Compare and choose a facility</h3>
                <p class="mt-2 text-sm text-[#64748B] leading-relaxed">Check distance, accepted materials, and operating hours before you decide where to go.</p>
            </div>
            <div class="relative pl-1">
                <span class="font-mono-tech text-sm text-[#10B981]/60 font-bold">03</span>
                <div class="mt-3 w-10 h-10 rounded-lg bg-[#10B981]/10 flex items-center justify-center text-[#10B981]">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                </div>
                <h3 class="font-display mt-4 font-bold text-[#0B1720]">Drop off or schedule a pickup</h3>
                <p class="mt-2 text-sm text-[#64748B] leading-relaxed">Complete the recycling process on your terms, and track the status of your request.</p>
            </div>
        </div>
    </section>

    {{-- ===================== IMPACT ===================== --}}
    <section class="bg-[#0B1720]">
        <div class="max-w-6xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
            <div>
                <span class="font-mono-tech text-xs tracking-[0.2em] uppercase text-[#34D399] font-bold">Platform impact</span>
                <h2 class="font-display mt-2 text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Every device recycled here stays out of a landfill</h2>
                <p class="mt-4 text-[#94A3B8] leading-relaxed max-w-md">
                    Electronics contain recoverable metals and hazardous materials that need
                    proper handling. By routing devices to verified facilities, EcoLocate
                    helps reduce landfill waste, supports responsible material recovery, and
                    makes safe disposal accessible in more communities.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-xl bg-white/5 border border-white/10 px-5 py-6">
                    <div class="w-9 h-9 rounded-lg bg-[#10B981]/15 flex items-center justify-center text-[#34D399] mb-3">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6l3 18h12l3-18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </div>
                    <p class="font-display text-sm font-bold text-white">Landfill diversion</p>
                    <p class="mt-1 text-xs text-[#94A3B8] leading-relaxed">Fewer devices ending up in general waste streams.</p>
                </div>
                <div class="rounded-xl bg-white/5 border border-white/10 px-5 py-6">
                    <div class="w-9 h-9 rounded-lg bg-[#10B981]/15 flex items-center justify-center text-[#34D399] mb-3">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                    </div>
                    <p class="font-display text-sm font-bold text-white">Resource recovery</p>
                    <p class="mt-1 text-xs text-[#94A3B8] leading-relaxed">Reusable metals and parts recovered by verified partners.</p>
                </div>
                <div class="rounded-xl bg-white/5 border border-white/10 px-5 py-6">
                    <div class="w-9 h-9 rounded-lg bg-[#10B981]/15 flex items-center justify-center text-[#34D399] mb-3">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <p class="font-display text-sm font-bold text-white">Community reach</p>
                    <p class="mt-1 text-xs text-[#94A3B8] leading-relaxed">Verified facilities growing across more cities every month.</p>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        (function () {
            const counters = document.querySelectorAll('.js-counter');
            if (!counters.length) return;

            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const currentValues = {};

            function animateTo(el, fromValue, toValue) {
                if (prefersReducedMotion || fromValue === toValue) {
                    el.textContent = toValue.toLocaleString();
                    currentValues[el.dataset.stat] = toValue;
                    return;
                }

                const duration = fromValue === 0 ? 1400 : 800;
                const start = performance.now();

                function tick(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    const value = Math.floor(fromValue + (toValue - fromValue) * eased);
                    el.textContent = value.toLocaleString();
                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    } else {
                        el.textContent = toValue.toLocaleString();
                        currentValues[el.dataset.stat] = toValue;
                    }
                }
                requestAnimationFrame(tick);
            }

            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        el.closest('div').parentElement.classList.add('stat-visible');
                        currentValues[el.dataset.stat] = 0;
                        animateTo(el, 0, parseInt(el.dataset.target, 10) || 0);
                        obs.unobserve(el);
                    }
                });
            }, { threshold: 0.4 });

            counters.forEach((el) => observer.observe(el));

            const POLL_URL = "{{ route('stats.live') }}";
            const POLL_INTERVAL_MS = 20000;

            async function refreshLiveStats() {
                try {
                    const res = await fetch(POLL_URL, { headers: { Accept: 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();

                    counters.forEach((el) => {
                        const key = el.dataset.stat;
                        if (!(key in data)) return;
                        const newValue = parseInt(data[key], 10) || 0;
                        const from = currentValues[key] ?? 0;
                        if (from === 0 && !el.closest('section').classList.contains('stat-visible')) {
                            el.dataset.target = newValue;
                            return;
                        }
                        if (newValue !== from) {
                            el.dataset.target = newValue;
                            animateTo(el, from, newValue);
                        }
                    });
                } catch (e) {
                    // Silently skip this cycle — next interval will retry.
                }
            }

            setInterval(refreshLiveStats, POLL_INTERVAL_MS);
        })();
    </script>
@endpush