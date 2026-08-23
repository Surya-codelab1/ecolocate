@extends('layouts.public')

@section('title', 'Device Search - EcoLocate')

@push('styles')
<style>
    .device-card { transition: box-shadow .25s ease, transform .25s ease; }
    .device-card:hover { transform: translateY(-2px); }
    .detail-panel {
        max-height: 0;
        overflow: hidden;
        transition: max-height .35s ease;
    }
    .detail-panel.open { max-height: 600px; }

    .skeleton {
        background: linear-gradient(90deg, #eef2f2 25%, #f6f8f8 37%, #eef2f2 63%);
        background-size: 400% 100%;
        animation: skeletonShine 1.4s ease infinite;
    }
    @keyframes skeletonShine {
        0% { background-position: 100% 50%; }
        100% { background-position: 0 50%; }
    }

    /* Highlighted eco stats on device cards */
    .eco-highlight {
        border-width: 1.5px;
        font-variant-numeric: tabular-nums;
    }
    .eco-highlight svg { flex-shrink: 0; }
</style>
@endpush

@section('content')
@php
    $initialDevicesJson = ($initialDevices ?? collect())->values();
@endphp

<div class="max-w-6xl mx-auto px-3 sm:px-4 py-6 sm:py-10">

    <div class="mb-6 sm:mb-8">
        <span class="inline-block text-xs font-semibold tracking-wide uppercase text-[#10B981] bg-[#10B981]/10 px-3 py-1 rounded-full mb-3">Know Your Device</span>
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#0B1720] mb-1">Search Any Device's Recycling Info</h1>
        <p class="text-sm sm:text-base text-[#64748B] max-w-2xl">Look up e-waste devices by brand or model to see what materials they contain, harmful components, estimated recycling value, and eco credits you can earn.</p>
    </div>

    {{-- Search + filter --}}
    <div class="glass rounded-2xl p-3 sm:p-4 shadow-sm mb-6 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1 min-w-0">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <input
                id="deviceSearchInput"
                type="text"
                placeholder="Search by brand or model, e.g. 'Samsung', 'iPhone 13'..."
                autocomplete="off"
                class="w-full bg-white border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400"
            >
        </div>

        <select id="categoryFilter" class="bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 w-full sm:w-56">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>

        <button id="clearDeviceSearch" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold px-4 py-2.5 rounded-lg transition inline-flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 2v6h6"/><path d="M21 12A9 9 0 0 0 6 5.3L3 8"/><path d="M21 22v-6h-6"/><path d="M3 12a9 9 0 0 0 15 6.7l3-2.7"/>
            </svg>
            Reset
        </button>
    </div>

    <p id="deviceResultStatus" class="text-xs sm:text-sm text-gray-500 mb-3"></p>

    <div id="deviceGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
</div>
@endsection

@push('scripts')
<script>
    const initialDevices = @json($initialDevicesJson);
    const searchUrl = "{{ route('devices.search.results') }}";

    const grid = document.getElementById('deviceGrid');
    const statusEl = document.getElementById('deviceResultStatus');
    const input = document.getElementById('deviceSearchInput');
    const categorySelect = document.getElementById('categoryFilter');
    const resetBtn = document.getElementById('clearDeviceSearch');

    let debounceTimer = null;

    // Inline icon set (stroke-based, no emojis)
    const ICONS = {
        leaf: `<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 11 13.42 11 12"/></svg>`,
        coin: `<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9 8h4a2 2 0 0 1 0 4H9m0 0h5m-5 0v3"/><path d="M11 5v2M11 15v2"/></svg>`,
        chevron: `<svg class="w-3.5 h-3.5 chevron transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>`,
        search: `<svg class="w-9 h-9 sm:w-10 sm:h-10 text-gray-300 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>`
    };

    function categoryColor(category) {
        const palette = ['#10B981', '#0EA5E9', '#8B5CF6', '#F59E0B', '#EF4444', '#14B8A6'];
        let hash = 0;
        for (let i = 0; i < (category || '').length; i++) hash = category.charCodeAt(i) + ((hash << 5) - hash);
        return palette[Math.abs(hash) % palette.length];
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }

    function deviceCard(d) {
        const color = categoryColor(d.category);
        const desc = d.description ? escapeHtml(d.description) : 'No description available.';
        const hasExtra = d.materials || d.harmful_components || d.recycling_information;
        const hasEcoStats = (d.eco_credits !== null && d.eco_credits !== undefined) || (d.estimated_recycling_value !== null && d.estimated_recycling_value !== undefined);

        return `
            <div class="device-card bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 hover:shadow-lg hover:border-emerald-200 shadow-sm flex flex-col">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div class="min-w-0">
                        <p class="font-semibold text-[#0B1720] text-sm sm:text-base leading-tight break-words">${escapeHtml(d.brand)} ${escapeHtml(d.model_name)}</p>
                        <span class="inline-block mt-1.5 text-[11px] font-semibold px-2 py-0.5 rounded-full" style="background:${color}1A;color:${color}">${escapeHtml(d.category)}</span>
                    </div>
                </div>

                ${hasEcoStats ? `
                    <div class="flex flex-wrap gap-2 mt-3">
                        ${d.eco_credits !== null && d.eco_credits !== undefined ? `
                            <span class="eco-highlight inline-flex items-center gap-1.5 text-xs font-bold text-[#047857] bg-[#10B981]/10 border border-[#10B981]/40 px-2.5 py-1.5 rounded-full">
                                ${ICONS.leaf}
                                ${d.eco_credits} Eco Credits
                            </span>
                        ` : ''}
                        ${d.estimated_recycling_value !== null && d.estimated_recycling_value !== undefined ? `
                            <span class="eco-highlight inline-flex items-center gap-1.5 text-xs font-bold text-[#0369A1] bg-[#0EA5E9]/10 border border-[#0EA5E9]/40 px-2.5 py-1.5 rounded-full">
                                ${ICONS.coin}
                                ₹${Number(d.estimated_recycling_value).toFixed(0)} Est. Value
                            </span>
                        ` : ''}
                    </div>
                ` : ''}

                <p class="text-xs sm:text-sm text-[#64748B] mt-3 line-clamp-2">${desc}</p>

                ${hasExtra ? `
                    <button class="toggle-detail-btn mt-4 w-full text-center text-xs font-semibold text-[#334155] bg-gray-50 hover:bg-gray-100 py-2 rounded-lg transition flex items-center justify-center gap-1.5">
                        <span class="btn-label">View Full Details</span>
                        ${ICONS.chevron}
                    </button>
                    <div class="detail-panel mt-1">
                        <div class="pt-3 space-y-2 text-xs text-[#334155] border-t border-gray-100 mt-2">
                            ${d.materials ? `<p><strong class="text-[#0B1720]">Materials:</strong> ${escapeHtml(d.materials)}</p>` : ''}
                            ${d.harmful_components ? `<p><strong class="text-[#0B1720]">Harmful Components:</strong> ${escapeHtml(d.harmful_components)}</p>` : ''}
                            ${d.recycling_information ? `<p><strong class="text-[#0B1720]">Recycling Info:</strong> ${escapeHtml(d.recycling_information)}</p>` : ''}
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
    }

    function skeletonCards(n = 6) {
        return Array.from({ length: n }).map(() => `
            <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5">
                <div class="skeleton h-4 w-3/4 rounded mb-2"></div>
                <div class="skeleton h-3 w-1/3 rounded mb-4"></div>
                <div class="skeleton h-3 w-full rounded mb-1"></div>
                <div class="skeleton h-3 w-5/6 rounded"></div>
            </div>
        `).join('');
    }

    function renderDevices(devices) {
        if (!devices.length) {
            grid.innerHTML = `
                <div class="col-span-full text-center py-12 sm:py-14">
                    ${ICONS.search}
                    <p class="text-sm font-semibold text-[#0B1720]">No devices found</p>
                    <p class="text-xs text-[#64748B] mt-1">Try a different brand, model, or category.</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = devices.map(deviceCard).join('');

        grid.querySelectorAll('.toggle-detail-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const panel = btn.nextElementSibling;
                const chevron = btn.querySelector('.chevron');
                const label = btn.querySelector('.btn-label');
                const isOpen = panel.classList.toggle('open');
                chevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
                label.textContent = isOpen ? 'Hide Details' : 'View Full Details';
            });
        });
    }

    async function runSearch() {
        const q = input.value.trim();
        const category = categorySelect.value;

        grid.innerHTML = skeletonCards();
        statusEl.textContent = 'Searching…';

        try {
            const params = new URLSearchParams();
            if (q) params.set('q', q);
            if (category) params.set('category', category);

            const res = await fetch(`${searchUrl}?${params.toString()}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            statusEl.textContent = `${data.length} device${data.length === 1 ? '' : 's'} found.`;
            renderDevices(data);
        } catch (e) {
            statusEl.textContent = 'Something went wrong while searching. Please try again.';
            grid.innerHTML = '';
        }
    }

    input.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(runSearch, 350);
    });

    categorySelect.addEventListener('change', runSearch);

    resetBtn.addEventListener('click', () => {
        input.value = '';
        categorySelect.value = '';
        statusEl.textContent = `${initialDevices.length} device${initialDevices.length === 1 ? '' : 's'} shown.`;
        renderDevices(initialDevices);
    });

    // Initial render with server-provided devices, no network round-trip needed
    statusEl.textContent = `${initialDevices.length} device${initialDevices.length === 1 ? '' : 's'} shown.`;
    renderDevices(initialDevices);
</script>
@endpush