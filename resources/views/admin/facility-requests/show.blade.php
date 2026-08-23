@extends('admin.layouts.app')
@section('page-title', 'Facility Request Details')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- LEFT: Request Details --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-6">
        <h2 class="font-heading font-bold text-lg text-[#0B1720] mb-4">Facility Request Details</h2>

        <div class="space-y-3 text-sm">
            <p><span class="font-semibold text-[#0B1720]">Facility Name:</span> <span class="text-[#64748B]">{{ $request->facility_name }}</span></p>
            <p><span class="font-semibold text-[#0B1720]">Contact Person:</span> <span class="text-[#64748B]">{{ $request->contact_person }}</span></p>
            <p><span class="font-semibold text-[#0B1720]">Email:</span> <span class="text-[#64748B]">{{ $request->email }}</span></p>
            <p><span class="font-semibold text-[#0B1720]">City:</span> <span class="text-[#64748B]">{{ $request->city->name ?? '-' }}</span></p>
            <p><span class="font-semibold text-[#0B1720]">Area:</span> <span class="text-[#64748B]">{{ $request->area }}</span></p>
            <p><span class="font-semibold text-[#0B1720]">Full Address:</span> <span class="text-[#64748B]">{{ $request->full_address }}</span></p>
            <p><span class="font-semibold text-[#0B1720]">Accepted E-Waste:</span> <span class="text-[#64748B]">{{ $request->accepted_ewaste }}</span></p>
            <p><span class="font-semibold text-[#0B1720]">Pickup Availability:</span> <span class="text-[#64748B]">{{ $request->pickup_availability }}</span></p>
            <p><span class="font-semibold text-[#0B1720]">Working Hours:</span> <span class="text-[#64748B]">{{ $request->working_hours }}</span></p>
            <p><span class="font-semibold text-[#0B1720]">Status:</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $request->status == 'pending' ? 'bg-[#F59E0B]/10 text-[#F59E0B]' :
                       ($request->status == 'approved' ? 'bg-[#10B981]/10 text-[#10B981]' : 'bg-[#EF4444]/10 text-[#EF4444]') }}">
                    {{ ucfirst($request->status) }}
                </span>
            </p>
        </div>

        @if($request->status === 'pending')
        <div id="geocode-status" class="mt-4 text-xs text-[#22D3EE] font-medium"></div>

        <form id="approveForm" action="{{ route('admin.facility-requests.approve', $request->id) }}" method="POST" class="mt-6 flex gap-3">
            @csrf
            <input type="hidden" name="latitude" id="lat-input">
            <input type="hidden" name="longitude" id="lng-input">

           <button type="submit" id="approveBtn" disabled
    class="bg-[#10B981] hover:bg-[#0ea371] text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow disabled:opacity-40 disabled:cursor-not-allowed transition">
    Confirm &amp; Approve
</button>

<button type="button" onclick="rejectRequest({{ $request->id }})"
    class="bg-[#EF4444] hover:bg-red-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow transition">
    Reject
</button>
        </form>
        @else
        <div class="mt-6 text-sm text-[#64748B] bg-[#F7FAF8] rounded-xl p-4">
            This request has already been <strong>{{ $request->status }}</strong>. No further action needed.
        </div>
        @endif
    </div>

    {{-- RIGHT: Map --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-4">
        <p class="text-sm text-[#64748B] mb-3">The location is detected automatically. Drag the marker or use the search box to adjust it if needed.</p>

        <div class="relative">
            <input type="text" id="geocoder-search" placeholder="Search address..."
                class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
                autocomplete="off">
            <div id="geocoder-results" class="hidden absolute z-[1000] left-0 right-0 bg-white border border-[#E2E8F0] rounded-lg shadow-lg mt-1 max-h-56 overflow-y-auto"></div>
        </div>

        <div id="map" class="w-full h-[380px] rounded-xl overflow-hidden"></div>
    </div>
</div>

<style>
.pulse-marker {
    width: 20px;
    height: 20px;
    background: #10B981;
    border-radius: 50%;
    box-shadow: 0 0 0 rgba(16, 185, 129, 0.6);
    animation: pulse 1.5s infinite;
    border: 3px solid white;
    cursor: grab;
}
@keyframes pulse {
    0%   { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6); }
    70%  { box-shadow: 0 0 0 18px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.geocoder-result-item {
    padding: 10px 12px;
    font-size: 13px;
    cursor: pointer;
    border-bottom: 1px solid #F1F5F9;
}
.geocoder-result-item:hover {
    background: #F0FDF4;
}
.geocoder-result-item:last-child {
    border-bottom: none;
}
</style>

@push('scripts')
{{-- MapLibre GL JS — used only on this page, no API key required --}}
<link href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" rel="stylesheet" />
<script src="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js"></script>

<script>
const fullAddress = @json($request->full_address . ', ' . $request->area . ', ' . ($request->city->name ?? ''));
const areaCityAddress = @json($request->area . ', ' . ($request->city->name ?? ''));
const cityOnlyAddress = @json($request->city->name ?? '');
const isPending = @json($request->status === 'pending');

// Free raster style using OSM tiles directly — no key, no signup required
const osmRasterStyle = {
    version: 8,
    sources: {
        osm: {
            type: 'raster',
            tiles: ['https://tile.openstreetmap.org/{z}/{x}/{y}.png'],
            tileSize: 256,
            attribution: '&copy; OpenStreetMap contributors'
        }
    },
    layers: [{
        id: 'osm-layer',
        type: 'raster',
        source: 'osm'
    }]
};

const map = new maplibregl.Map({
    container: 'map',
    style: osmRasterStyle,
    center: [78.9629, 20.5937],
    zoom: 4,
});

map.addControl(new maplibregl.NavigationControl(), 'top-right');

let marker = null;

function setStatus(text, type = 'info') {
    const statusEl = document.getElementById('geocode-status');
    if (!statusEl) return;
    statusEl.innerText = text;
    statusEl.style.color = type === 'error' ? '#EF4444' : (type === 'ok' ? '#10B981' : '#22D3EE');
}

function placeMarker(lat, lng) {
    if (marker) marker.remove();

    const el = document.createElement('div');
    el.className = 'pulse-marker';

    marker = new maplibregl.Marker({ element: el, draggable: isPending })
        .setLngLat([lng, lat])
        .addTo(map);

    map.flyTo({ center: [lng, lat], zoom: 15, duration: 1200 });

    if (isPending) {
        document.getElementById('lat-input').value = lat;
        document.getElementById('lng-input').value = lng;
        document.getElementById('approveBtn').disabled = false;

        marker.on('dragend', () => {
            const pos = marker.getLngLat();
            document.getElementById('lat-input').value = pos.lat;
            document.getElementById('lng-input').value = pos.lng;
        });
    }
}

// ── Photon geocoding (free, no key, CORS-enabled) ─────
async function photonSearch(query) {
    const res = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=5`);
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const data = await res.json();
    return (data.features || []).map(f => ({
        label: [
            f.properties.name,
            f.properties.street,
            f.properties.city,
            f.properties.state,
            f.properties.country
        ].filter(Boolean).join(', '),
        lat: f.geometry.coordinates[1],
        lng: f.geometry.coordinates[0],
    }));
}

// Progressively broader search: full address -> area+city -> city only.
// This ensures we always land on the nearest resolvable point instead of failing outright.
async function geocodeAddress() {
    setStatus('Locating address automatically...');

    const searchInput = document.getElementById('geocoder-search');
    const attempts = [fullAddress, areaCityAddress, cityOnlyAddress].filter(Boolean);

    for (const query of attempts) {
        try {
            const results = await photonSearch(query);
            console.log('Photon geocode attempt:', query, results);

            if (results.length > 0) {
                placeMarker(results[0].lat, results[0].lng);
                if (searchInput) searchInput.value = fullAddress;

                if (query === fullAddress) {
                    setStatus('Exact address found and marked on the map. If it looks correct, click "Confirm & Approve".', 'ok');
                } else {
                    setStatus('Exact address not found — showing the nearest resolvable location for "' + query + '". Please drag the marker to the precise facility location.', 'error');
                }
                return;
            }
        } catch (err) {
            console.error('Geocode attempt failed for:', query, err);
        }
    }

    if (searchInput) searchInput.value = fullAddress;
    setStatus('Could not locate this address automatically. Try searching manually below, or click on the map to set the location.', 'error');
}

// ── Manual search box with live dropdown ─────
const searchInput = document.getElementById('geocoder-search');
const resultsBox = document.getElementById('geocoder-results');
let searchDebounce = null;

searchInput.addEventListener('input', () => {
    clearTimeout(searchDebounce);
    const query = searchInput.value.trim();

    if (query.length < 3) {
        resultsBox.classList.add('hidden');
        resultsBox.innerHTML = '';
        return;
    }

    searchDebounce = setTimeout(async () => {
        try {
            const results = await photonSearch(query);
            if (!results.length) {
                resultsBox.innerHTML = '<div class="geocoder-result-item text-[#94A3B8]">No results found</div>';
                resultsBox.classList.remove('hidden');
                return;
            }

            resultsBox.innerHTML = results.map((r, i) =>
                `<div class="geocoder-result-item" data-index="${i}">${r.label}</div>`
            ).join('');
            resultsBox.classList.remove('hidden');

            resultsBox.querySelectorAll('.geocoder-result-item[data-index]').forEach(item => {
                item.addEventListener('click', () => {
                    const r = results[parseInt(item.dataset.index)];
                    searchInput.value = r.label;
                    placeMarker(r.lat, r.lng);
                    resultsBox.classList.add('hidden');
                    setStatus('Location updated from the search box. Drag the marker to fine-tune if needed.', 'ok');
                });
            });
        } catch (err) {
            console.error('Search failed:', err);
        }
    }, 400);
});

document.addEventListener('click', (e) => {
    if (!resultsBox.contains(e.target) && e.target !== searchInput) {
        resultsBox.classList.add('hidden');
    }
});

if (isPending) {
    map.on('click', (e) => {
        placeMarker(e.lngLat.lat, e.lngLat.lng);
    });
}

map.on('load', geocodeAddress);

function rejectRequest(id) {
    if (confirm('Reject this facility request?')) {
        fetch(`/admin/facility-requests/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(() => window.location.href = '{{ route("admin.facility-requests.index") }}');
    }
}
</script>
@endpush

@endsection