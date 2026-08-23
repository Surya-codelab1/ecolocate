@extends('layouts.public')

@section('title', 'E-Waste Awareness - EcoLocate')

@push('styles')
<style>
    .harm-card { transition: box-shadow .25s ease, transform .25s ease; }
    .harm-card:hover { transform: translateY(-2px); }
    .harm-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height .35s ease;
    }
    .harm-panel.open { max-height: 400px; }

    .severity-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .icon-badge svg { width: 20px; height: 20px; }
</style>
@endpush

@section('content')
@php
    // Static reference data — edit freely, no DB needed.
    $stats = [
        ['value' => '60M+', 'label' => 'Tonnes of e-waste generated globally every year'],
        ['value' => '< 20%', 'label' => 'Formally collected and recycled worldwide'],
        ['value' => '1000+', 'label' => 'Toxic substances can leach from unmanaged e-waste'],
        ['value' => '17', 'label' => 'Critical raw materials lost when devices aren\'t recycled'],
    ];

    // Reusable stroke-based icon markup (no emojis)
    $icons = [
        'battery'  => '<path d="M17 7H3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Z"/><line x1="22" y1="11" x2="22" y2="13"/>',
        'tv'       => '<path d="m17 3-5 5-5-5"/><rect x="2" y="8" width="20" height="14" rx="2"/>',
        'cpu'      => '<rect x="5" y="5" width="14" height="14" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>',
        'wind'     => '<path d="M9.6 4.6a2 2 0 1 1 1.4 3.4H2"/><path d="M12.6 19.4a2 2 0 1 0 1.4-3.4H2"/><path d="M17.7 7.7a2.5 2.5 0 1 1 1.8 4.3H2"/>',
        'monitor'  => '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
        'printer'  => '<path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>',
        'plug'     => '<path d="M12 22v-5"/><path d="M9 8V2"/><path d="M15 8V2"/><path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8Z"/>',
        'keyboard' => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M6 9h.01M10 9h.01M14 9h.01M18 9h.01M8 13h.01M12 13h.01M16 13h.01M7 17h10"/>',
        'flask'    => '<path d="M9 2h6"/><path d="M10 2v6.4a2 2 0 0 1-.3 1L5 18.5A2 2 0 0 0 6.7 21h10.6a2 2 0 0 0 1.7-2.5L14.3 9.4a2 2 0 0 1-.3-1V2"/>',
        'gem'      => '<path d="M6 3h12l4 6-10 12L2 9Z"/><path d="M11 3 8 9l4 12 4-12-3-6"/><path d="M2 9h20"/>',
        'ban'      => '<circle cx="12" cy="12" r="10"/><line x1="4.9" y1="4.9" x2="19.1" y2="19.1"/>',
        'lock'     => '<rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'gift'     => '<rect x="3" y="8" width="18" height="4"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8s1-5 4.5-5a2.5 2.5 0 0 1 0 5"/>',
        'check'    => '<path d="M22 11.1V12a10 10 0 1 1-5.9-9.1"/><polyline points="22 4 12 14.1 9 11.1"/>',
        'recycle'  => '<path d="M3 2v6h6"/><path d="M21 12A9 9 0 0 0 6 5.3L3 8"/><path d="M21 22v-6h-6"/><path d="M3 12a9 9 0 0 0 15 6.7l3-2.7"/>',
    ];

    $harmfulDevices = [
        [
            'name' => 'Smartphones & Laptop Batteries',
            'icon' => 'battery',
            'severity' => 'High',
            'color' => '#EF4444',
            'materials' => 'Lithium, Cobalt, Cadmium, Nickel',
            'impact' => 'Damaged lithium-ion batteries can catch fire or explode. Cobalt and cadmium exposure is linked to lung damage, kidney disease, and long-term neurological harm, especially for informal recyclers handling them without protection.',
        ],
        [
            'name' => 'CRT TVs & Old Monitors',
            'icon' => 'tv',
            'severity' => 'High',
            'color' => '#EF4444',
            'materials' => 'Lead, Barium, Phosphor compounds',
            'impact' => 'A single CRT screen can contain several kilograms of lead. Lead exposure affects the nervous system and is especially harmful to children, causing developmental and cognitive issues.',
        ],
        [
            'name' => 'Circuit Boards (PCBs)',
            'icon' => 'cpu',
            'severity' => 'High',
            'color' => '#EF4444',
            'materials' => 'Lead, Mercury, Beryllium, Brominated flame retardants',
            'impact' => 'Burning circuit boards to extract metals (a common informal recycling practice) releases carcinogenic dioxins and heavy-metal fumes that contaminate air, soil, and water.',
        ],
        [
            'name' => 'Refrigerators & Air Conditioners',
            'icon' => 'wind',
            'severity' => 'Medium',
            'color' => '#F59E0B',
            'materials' => 'CFCs, HFCs, Compressor oils',
            'impact' => 'Refrigerant gases deplete the ozone layer and are potent greenhouse gases if released improperly instead of being safely extracted during recycling.',
        ],
        [
            'name' => 'LED / LCD Screens',
            'icon' => 'monitor',
            'severity' => 'Medium',
            'color' => '#F59E0B',
            'materials' => 'Mercury (in older backlights), Indium, Gallium',
            'impact' => 'Mercury vapor from broken backlights can cause kidney and neurological damage with prolonged exposure. Rare-earth elements are also lost if not properly recovered.',
        ],
        [
            'name' => 'Printers & Toner Cartridges',
            'icon' => 'printer',
            'severity' => 'Medium',
            'color' => '#F59E0B',
            'materials' => 'Carbon black, Heavy-metal pigments, Plastic housings',
            'impact' => 'Inhaling fine toner particles over time can irritate the respiratory system. Cartridges also take centuries to decompose in landfills.',
        ],
        [
            'name' => 'Cables & Wires',
            'icon' => 'plug',
            'severity' => 'Low',
            'color' => '#10B981',
            'materials' => 'PVC insulation, Copper',
            'impact' => 'Burning cables to recover copper (a common informal practice) releases dioxins and furans — highly toxic, carcinogenic compounds that spread easily through smoke.',
        ],
        [
            'name' => 'Keyboards, Mice & Small Peripherals',
            'icon' => 'keyboard',
            'severity' => 'Low',
            'color' => '#10B981',
            'materials' => 'ABS plastics, Trace heavy metals',
            'impact' => 'Lower individual risk, but the sheer volume discarded adds significantly to landfill plastic that takes hundreds of years to break down.',
        ],
    ];

    $disposalTips = [
        [
            'title' => 'Never bin it or burn it',
            'desc' => 'Regular trash and open burning release toxins into landfills, soil, and air. E-waste needs specialised handling.',
            'icon' => 'ban',
        ],
        [
            'title' => 'Wipe your data first',
            'desc' => 'Factory-reset phones and laptops, and remove SIM/SD cards before handing devices over for recycling or donation.',
            'icon' => 'lock',
        ],
        [
            'title' => 'Donate what still works',
            'desc' => 'A working device that no longer suits you could still be useful to someone else — donate before you discard.',
            'icon' => 'gift',
        ],
        [
            'title' => 'Use a certified facility',
            'desc' => 'Certified recyclers safely extract hazardous materials and recover valuable metals instead of dumping or burning them.',
            'icon' => 'check',
        ],
        [
            'title' => 'Separate batteries',
            'desc' => 'Batteries need separate handling from the rest of a device — never crush, puncture, or expose them to heat.',
            'icon' => 'battery',
        ],
        [
            'title' => 'Buy for longevity',
            'desc' => 'Choosing repairable, durable devices — and repairing instead of replacing — is the simplest way to reduce e-waste at the source.',
            'icon' => 'recycle',
        ],
    ];

    $svgOpen = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">';
@endphp

{{-- ===== Hero ===== --}}
<div class="bg-gradient-to-br from-[#0B1720] via-[#0F2027] to-[#0B1720] text-white">
    <div class="max-w-6xl mx-auto px-3 sm:px-4 py-10 sm:py-16 text-center">
        <span class="inline-block text-xs font-semibold tracking-wide uppercase text-[#34D399] bg-[#10B981]/15 px-3 py-1 rounded-full mb-4">E-Waste Awareness</span>
        <h1 class="text-2xl sm:text-4xl font-bold mb-3 leading-tight">Every Device You Throw Away<br class="hidden sm:block"> Has a Hidden Cost</h1>
        <p class="text-sm sm:text-base text-[#94A3B8] max-w-2xl mx-auto">
            E-waste is the fastest-growing waste stream in the world. Understanding what's inside your old
            electronics — and how to dispose of them safely — protects your health, your community, and the planet.
        </p>
    </div>
</div>

{{-- ===== Stats ===== --}}
<div class="max-w-6xl mx-auto px-3 sm:px-4 -mt-8 sm:-mt-10">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        @foreach ($stats as $stat)
            <div class="glass rounded-2xl p-4 sm:p-5 shadow-md text-center">
                <p class="text-xl sm:text-3xl font-bold text-[#10B981] mb-1">{{ $stat['value'] }}</p>
                <p class="text-[11px] sm:text-xs text-[#64748B] leading-snug">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</div>

{{-- ===== Why it's harmful ===== --}}
<div class="max-w-6xl mx-auto px-3 sm:px-4 py-10 sm:py-14">
    <div class="max-w-2xl mb-8 sm:mb-10">
        <h2 class="text-xl sm:text-2xl font-bold text-[#0B1720] mb-2">Why E-Waste Is So Harmful</h2>
        <p class="text-sm sm:text-base text-[#64748B]">
            Electronics pack powerful materials into a small footprint — that's exactly what makes them dangerous
            once discarded carelessly. When devices sit in landfills or are burned to recover metals, hazardous
            substances leach into soil and groundwater or become airborne, affecting entire communities, not just
            the person who threw the device away.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <div class="glass rounded-2xl p-5 sm:p-6 shadow-sm">
            <span class="icon-badge w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center mb-3">
                {!! $svgOpen . $icons['flask'] . '</svg>' !!}
            </span>
            <p class="font-semibold text-[#0B1720] mb-1.5 text-sm sm:text-base">Toxic Leaching</p>
            <p class="text-xs sm:text-sm text-[#64748B]">Lead, mercury, and cadmium seep into soil and groundwater from landfilled devices, contaminating drinking water sources over time.</p>
        </div>
        <div class="glass rounded-2xl p-5 sm:p-6 shadow-sm">
            <span class="icon-badge w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center mb-3">
                {!! $svgOpen . $icons['wind'] . '</svg>' !!}
            </span>
            <p class="font-semibold text-[#0B1720] mb-1.5 text-sm sm:text-base">Air Pollution</p>
            <p class="text-xs sm:text-sm text-[#64748B]">Informal burning to recover copper or gold releases dioxins and heavy-metal fumes — a major health risk for nearby communities.</p>
        </div>
        <div class="glass rounded-2xl p-5 sm:p-6 shadow-sm">
            <span class="icon-badge w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-3">
                {!! $svgOpen . $icons['gem'] . '</svg>' !!}
            </span>
            <p class="font-semibold text-[#0B1720] mb-1.5 text-sm sm:text-base">Resource Loss</p>
            <p class="text-xs sm:text-sm text-[#64748B]">Gold, silver, copper, and rare-earth elements inside devices are lost forever when e-waste isn't properly recycled and recovered.</p>
        </div>
    </div>
</div>

{{-- ===== Harmful materials by device ===== --}}
<div class="bg-[#F8FAFC] py-10 sm:py-14">
    <div class="max-w-6xl mx-auto px-3 sm:px-4">
        <div class="max-w-2xl mb-8 sm:mb-10">
            <h2 class="text-xl sm:text-2xl font-bold text-[#0B1720] mb-2">What's Inside Your Devices</h2>
            <p class="text-sm sm:text-base text-[#64748B]">Tap a device to see exactly what it contains and how those materials can affect your health if mishandled.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach ($harmfulDevices as $d)
                <div class="harm-card bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 shadow-sm hover:shadow-lg hover:border-emerald-200">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="icon-badge w-10 h-10 shrink-0 rounded-xl bg-gray-50 flex items-center justify-center text-[#334155]">
                                {!! $svgOpen . $icons[$d['icon']] . '</svg>' !!}
                            </span>
                            <div class="min-w-0">
                                <p class="font-semibold text-[#0B1720] text-sm leading-tight break-words">{{ $d['name'] }}</p>
                                <span class="inline-flex items-center gap-1.5 mt-1 text-[11px] font-semibold" style="color: {{ $d['color'] }}">
                                    <span class="severity-dot" style="background: {{ $d['color'] }}"></span>
                                    {{ $d['severity'] }} Risk
                                </span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-[#64748B] mt-3"><strong class="text-[#334155]">Contains:</strong> {{ $d['materials'] }}</p>

                    <button class="toggle-harm-btn mt-3 w-full text-center text-xs font-semibold text-[#334155] bg-gray-50 hover:bg-gray-100 py-2 rounded-lg transition flex items-center justify-center gap-1.5">
                        <span class="btn-label">Health Impact</span>
                        <svg class="w-3.5 h-3.5 chevron transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="harm-panel">
                        <p class="text-xs text-[#334155] pt-3 mt-1 border-t border-gray-100 leading-relaxed">{{ $d['impact'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ===== How to dispose safely ===== --}}
<div class="max-w-6xl mx-auto px-3 sm:px-4 py-10 sm:py-14">
    <div class="max-w-2xl mb-8 sm:mb-10">
        <h2 class="text-xl sm:text-2xl font-bold text-[#0B1720] mb-2">How to Protect Yourself & Dispose Responsibly</h2>
        <p class="text-sm sm:text-base text-[#64748B]">Small habits make a big difference. Here's how to handle old electronics the right way.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        @foreach ($disposalTips as $tip)
            <div class="glass rounded-2xl p-5 shadow-sm flex items-start gap-3">
                <span class="icon-badge w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    {!! $svgOpen . $icons[$tip['icon']] . '</svg>' !!}
                </span>
                <div class="min-w-0">
                    <p class="font-semibold text-[#0B1720] text-sm mb-1">{{ $tip['title'] }}</p>
                    <p class="text-xs sm:text-sm text-[#64748B]">{{ $tip['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- ===== CTA ===== --}}
<div class="max-w-6xl mx-auto px-3 sm:px-4 pb-14">
    <div class="rounded-3xl bg-gradient-to-r from-[#10B981] to-[#0EA5E9] px-5 sm:px-12 py-10 sm:py-14 text-center text-white shadow-lg">
        <h2 class="text-xl sm:text-2xl font-bold mb-2">Ready to Recycle Responsibly?</h2>
        <p class="text-sm sm:text-base text-white/90 max-w-xl mx-auto mb-6">Find a certified facility near you, or look up your device to see exactly what it's made of and how much it's worth.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('facilities.map') }}" class="bg-white text-[#0B1720] font-semibold text-sm px-6 py-3 rounded-lg hover:-translate-y-0.5 transition-all shadow-md">
                Find a Recycling Facility
            </a>
            <a href="{{ route('devices.search') }}" class="bg-white/10 border border-white/40 text-white font-semibold text-sm px-6 py-3 rounded-lg hover:bg-white/20 transition-all">
                Search Your Device
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-harm-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const panel = btn.nextElementSibling;
            const chevron = btn.querySelector('.chevron');
            const label = btn.querySelector('.btn-label');
            const isOpen = panel.classList.toggle('open');
            chevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
            label.textContent = isOpen ? 'Hide Details' : 'Health Impact';
        });
    });
</script>
@endpush