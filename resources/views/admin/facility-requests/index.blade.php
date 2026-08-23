@extends('admin.layouts.app')
@section('page-title', 'Facility Requests')

@section('content')

<div class="mb-6">
    <h2 class="font-heading font-bold text-xl text-[#0B1720]">Facility Requests</h2>
    <p class="text-sm text-[#64748B] mt-0.5">Review pending applications and manage the live map.</p>
</div>

@php
    $pendingCount = $requests->where('status', 'pending')->count();
    $approvedCount = $requests->where('status', 'approved')->count();
    $rejectedCount = $requests->where('status', 'rejected')->count();

    // Cycle of colors so each pending marker on the map gets its own identity
    $markerPalette = ['#10B981', '#22D3EE', '#F59E0B', '#A78BFA', '#F43F5E', '#34D399', '#6366F1'];

    // Precomputed here (not inline inside @json) to keep the Blade directive parser happy
    $existingFacilitiesForMap = $existingFacilities->map(function ($f) {
        return [
            'name' => $f->facility_name,
            'lat' => (float) $f->latitude,
            'lng' => (float) $f->longitude,
        ];
    })->values();

    $pendingOnly = $requests->where('status', 'pending')->values();
    $pendingRequestsForMap = $pendingOnly->map(function ($r, $i) use ($markerPalette) {
        return [
            'id' => $r->id,
            'name' => $r->facility_name,
            'contact' => $r->contact_person,
            'address' => $r->full_address . ', ' . $r->area . ', ' . ($r->city->name ?? ''),
            'color' => $markerPalette[$i % count($markerPalette)],
            'reviewUrl' => route('admin.facility-requests.show', $r->id),
        ];
    });
@endphp

{{-- Success toast --}}
@if(session('success'))
    <div class="mb-5 flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- Stat strip --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-heading font-extrabold text-[#0B1720] leading-none">{{ $pendingCount }}</p>
            <p class="text-xs text-[#64748B] mt-1">Awaiting review</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-heading font-extrabold text-[#0B1720] leading-none">{{ $approvedCount }}</p>
            <p class="text-xs text-[#64748B] mt-1">Approved this page</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
            </svg>
        </div>
        <div>
            <p class="text-xl font-heading font-extrabold text-[#0B1720] leading-none">{{ $rejectedCount }}</p>
            <p class="text-xs text-[#64748B] mt-1">Rejected this page</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Live "new request" toast --}}
    <div id="liveToast" class="hidden fixed top-5 right-5 z-[1100] max-w-sm bg-white rounded-2xl shadow-xl border border-emerald-200 p-4 flex items-start gap-3">
        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-[#0B1720]">New facility request</p>
            <p id="liveToastText" class="text-xs text-[#64748B] mt-0.5 truncate"></p>
        </div>
        <button onclick="document.getElementById('liveToast').classList.add('hidden')" class="text-[#94A3B8] hover:text-[#0B1720] shrink-0">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
            </svg>
        </button>
    </div>

    {{-- LIVE MAP --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-4 relative">

        <div class="flex items-center justify-between mb-3 px-1">
            <div class="flex items-center gap-2.5">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <h3 class="font-heading font-bold text-[#0B1720] text-sm">Live Requests Map</h3>
            </div>
            <p class="text-xs text-[#94A3B8]">Nearby requests cluster automatically</p>
        </div>

        <div id="map" class="w-full h-[560px] rounded-xl overflow-hidden bg-[#F1F5F9]"></div>

        {{-- Lottie loading overlay --}}
        <div id="mapLoader" class="absolute inset-4 top-14 rounded-xl bg-white/85 backdrop-blur-sm flex flex-col items-center justify-center gap-2 z-[999] transition-opacity duration-300">
            <lottie-player
                src="{{ asset('assets/admin/lottie/map-locating.json') }}"
                background="transparent" speed="1" style="width: 140px; height: 140px;"
                loop autoplay
                onerror="this.style.display='none'">
            </lottie-player>
            <p class="text-xs text-[#64748B] font-medium">Locating pending requests…</p>
        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-4 mt-3 px-1 flex-wrap">
            <div class="flex items-center gap-1.5 text-xs text-[#64748B]">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(52,211,153,0.8)]"></span>
                Pending request (blinking)
            </div>
            <div class="flex items-center gap-1.5 text-xs text-[#64748B]">
                <span class="w-2.5 h-2.5 rounded-full bg-sky-400 shadow-[0_0_6px_rgba(56,189,248,0.8)]"></span>
                Existing approved facility (blinking)
            </div>
        </div>
    </div>

    {{-- REQUEST LIST --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden flex flex-col">

        <div class="px-5 py-4 border-b border-[#E2E8F0]">
            <h3 class="font-heading font-bold text-[#0B1720] text-sm">Pending Review</h3>
            <p class="text-xs text-[#64748B] mt-0.5">Click a card to locate it on the map</p>
        </div>

        <div class="divide-y divide-[#E2E8F0] overflow-y-auto max-h-[560px]">
            @php $colorIndex = 0; @endphp
            @forelse($requests as $req)
                @php
                    $color = $req->status === 'pending' ? $markerPalette[$colorIndex % count($markerPalette)] : '#CBD5E1';
                    if ($req->status === 'pending') $colorIndex++;
                @endphp

                <button type="button"
                        onclick="focusMarker({{ $req->id }})"
                        class="w-full text-left px-5 py-4 hover:bg-[#F8FAFC] transition-colors group">
                    <div class="flex items-start gap-3">
                        <span class="w-2.5 h-2.5 rounded-full mt-1.5 shrink-0" style="background:{{ $color }}"></span>

                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-[#0B1720] text-sm truncate">{{ $req->facility_name }}</p>
                            <p class="text-xs text-[#94A3B8] truncate mt-0.5">{{ $req->area }}, {{ $req->city->name ?? '—' }}</p>

                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold
                                    {{ $req->status === 'pending' ? 'bg-amber-50 text-amber-600' :
                                       ($req->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500') }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                                <span class="text-[10px] text-[#94A3B8]">{{ $req->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <a href="{{ route('admin.facility-requests.show', $req->id) }}"
                           onclick="event.stopPropagation()"
                           class="shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-[#94A3B8] group-hover:text-[#10B981] group-hover:bg-[#10B981]/10 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>
                    </div>
                </button>
            @empty
                <div class="px-6 py-14 flex flex-col items-center gap-3">
                    <lottie-player
                        src="{{ asset('assets/admin/lottie/empty-inbox.json') }}"
                        background="transparent" speed="1" style="width: 140px; height: 140px;"
                        loop autoplay
                        onerror="this.style.display='none'">
                    </lottie-player>
                    <p class="text-sm text-[#64748B]">No facility requests yet.</p>
                </div>
            @endforelse
        </div>

        @if($requests->hasPages())
            <div class="px-5 py-3 border-t border-[#E2E8F0]">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

</div>

<style>
    /* Blinking pulse ring per marker color, driven by a CSS variable so each marker keeps its own color */
    .pulse-marker {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 3px solid white;
        background: var(--pulse-color, #10B981);
        box-shadow: 0 0 0 rgba(0,0,0,0);
        animation: pulseRing 1.6s infinite;
        position: relative;
    }
    @keyframes pulseRing {
        0%   { box-shadow: 0 0 0 0 color-mix(in srgb, var(--pulse-color, #10B981) 60%, transparent); }
        70%  { box-shadow: 0 0 0 16px color-mix(in srgb, var(--pulse-color, #10B981) 0%, transparent); }
        100% { box-shadow: 0 0 0 0 color-mix(in srgb, var(--pulse-color, #10B981) 0%, transparent); }
    }
    .existing-marker {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #CBD5E1;
        border: 2px solid white;
    }
    .marker-cluster-custom {
        background: rgba(16,185,129,0.15);
        border: 2px solid #10B981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0EA271;
        font-weight: 700;
        font-size: 12px;
    }
    .leaflet-popup-content-wrapper { border-radius: 14px; }
    .leaflet-popup-content { margin: 12px 14px; font-family: inherit; }
</style>

@push('scripts')
{{-- Leaflet already used on the show page; MarkerCluster groups nearby pins together --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

{{-- Lottie web component --}}
<script src="https://unpkg.com/@lottiefiles/lottie-player@1.5.7/dist/lottie-player.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const map = L.map('map', { zoomControl: true }).setView([20.5937, 78.9629], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const cluster = L.markerClusterGroup({
        iconCreateFunction: function (c) {
            return L.divIcon({
                html: `<div class="marker-cluster-custom" style="width:${34 + c.getChildCount()}px;height:${34 + c.getChildCount()}px">${c.getChildCount()}</div>`,
                className: '',
                iconSize: [40, 40],
            });
        }
    });

    const markerRefs = {}; // request id -> marker
    const bounds = [];

    // ── Existing approved facilities (faint, static context markers) ─────
    const existingFacilities = @json($existingFacilitiesForMap);

    existingFacilities.forEach(f => {
        const icon = L.divIcon({
            className: '',
            html: '<div class="pulse-marker" style="--pulse-color:#38BDF8"></div>',
            iconSize: [18, 18],
        });
        L.marker([f.lat, f.lng], { icon }).addTo(map).bindPopup(`<strong>${f.name}</strong><br><span style="color:#94A3B8;font-size:12px">Existing facility</span>`);
        bounds.push([f.lat, f.lng]);
    });

    // ── Pending requests — geocoded client-side and shown as blinking colorful pins ─────
    const pendingRequests = @json($pendingRequestsForMap);

    const geocodeCacheKey = addr => 'geocode:' + addr;

    async function geocode(address) {
        const cached = localStorage.getItem(geocodeCacheKey(address));
        if (cached) return JSON.parse(cached);

        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(address)}`);
        const data = await res.json();
        if (!data.length) return null;

        const point = { lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) };
        localStorage.setItem(geocodeCacheKey(address), JSON.stringify(point));
        return point;
    }

    async function plotPendingRequests() {
        for (const r of pendingRequests) {
            const point = await geocode(r.address);
            if (!point) continue;

            const icon = L.divIcon({
                className: '',
                html: `<div class="pulse-marker" style="--pulse-color:${r.color}"></div>`,
                iconSize: [18, 18],
            });

            const marker = L.marker([point.lat, point.lng], { icon });
            marker.bindPopup(`
                <strong>${r.name}</strong><br>
                <span style="color:#64748B;font-size:12px">${r.contact}</span><br>
                <a href="${r.reviewUrl}" style="color:${r.color};font-weight:600;font-size:12px;text-decoration:underline">Review &amp; Approve →</a>
            `);
            cluster.addLayer(marker);
            markerRefs[r.id] = marker;
            bounds.push([point.lat, point.lng]);

            // Respect Nominatim's fair-use rate limit (max 1 request/sec) when not cached
            await new Promise(resolve => setTimeout(resolve, 250));
        }

        map.addLayer(cluster);
        if (bounds.length) map.fitBounds(bounds, { padding: [40, 40], maxZoom: 13 });

        const loader = document.getElementById('mapLoader');
        if (loader) { loader.style.opacity = '0'; setTimeout(() => loader.remove(), 300); }
    }

    window.focusMarker = function (id) {
        const marker = markerRefs[id];
        if (!marker) return;
        cluster.zoomToShowLayer(marker, () => {
            map.flyTo(marker.getLatLng(), 15, { duration: 1.2 });
            marker.openPopup();
        });
    };

    // ── Real-time polling: pick up brand-new pending requests without a refresh ─────
    const plottedIds = new Set(pendingRequests.map(r => r.id));
    let nextColorIndex = pendingRequests.length;
    const palette = @json($markerPalette);

    function showToast(text) {
        const toast = document.getElementById('liveToast');
        const toastText = document.getElementById('liveToastText');
        if (!toast || !toastText) return;
        toastText.textContent = text;
        toast.classList.remove('hidden');
        clearTimeout(window._liveToastTimer);
        window._liveToastTimer = setTimeout(() => toast.classList.add('hidden'), 6000);
    }

    async function plotSingleMarker(r) {
        const point = await geocode(r.address);
        if (!point) return;

        const color = palette[nextColorIndex % palette.length];
        nextColorIndex++;

        const icon = L.divIcon({
            className: '',
            html: `<div class="pulse-marker" style="--pulse-color:${color}"></div>`,
            iconSize: [18, 18],
        });

        const marker = L.marker([point.lat, point.lng], { icon });
        marker.bindPopup(`
            <strong>${r.name}</strong><br>
            <span style="color:#64748B;font-size:12px">${r.contact}</span><br>
            <a href="${r.reviewUrl}" style="color:${color};font-weight:600;font-size:12px;text-decoration:underline">Review &amp; Approve →</a>
        `);
        cluster.addLayer(marker);
        markerRefs[r.id] = marker;

        map.flyTo([point.lat, point.lng], Math.max(map.getZoom(), 12), { duration: 1.2 });
        marker.openPopup();
    }

    async function pollForNewRequests() {
        try {
            const res = await fetch('{{ route("admin.facility-requests.live") }}');
            const liveRequests = await res.json();

            for (const r of liveRequests) {
                if (plottedIds.has(r.id)) continue;
                plottedIds.add(r.id);
                showToast(`${r.name} just submitted a request`);
                await plotSingleMarker(r);
            }
        } catch (err) {
            // Silently skip this cycle — next poll will retry
        }
    }

    setInterval(pollForNewRequests, 12000);

    if (pendingRequests.length === 0) {
        const loader = document.getElementById('mapLoader');
        if (loader) loader.remove();
        if (bounds.length) map.fitBounds(bounds, { padding: [40, 40], maxZoom: 13 });
    } else {
        plotPendingRequests();
    }

});
</script>
@endpush

@endsection