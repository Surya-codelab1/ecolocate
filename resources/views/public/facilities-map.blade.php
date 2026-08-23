@extends('layouts.public')

@section('title', 'Find Facilities - EcoLocate')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <style>
        .leaflet-popup-content-wrapper { border-radius: 14px; max-width: 78vw; }
        .leaflet-popup-content { margin: 14px 16px; font-family: 'Poppins', sans-serif; width: auto !important; }
        .leaflet-routing-container { display: none; }

        @media (max-width: 480px) {
            .leaflet-popup-content-wrapper { max-width: 68vw; }
            .leaflet-popup-content { font-size: 12px; }
        }

        /* animated blinking dot for the user's current location */
        .user-pulse-wrap { position: relative; width: 18px; height: 18px; }
        .user-pulse-dot {
            position: absolute; top: 1px; left: 1px;
            width: 16px; height: 16px; border-radius: 50%;
            background: #3B82F6; border: 3px solid white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
            z-index: 2;
        }
        .user-pulse-ring {
            position: absolute; top: -6px; left: -6px;
            width: 30px; height: 30px; border-radius: 50%;
            background: rgba(59,130,246,0.35);
            animation: userPulseRing 1.6s infinite;
            z-index: 1;
        }
        @keyframes userPulseRing {
            0%   { transform: scale(0.4); opacity: 0.9; }
            70%  { transform: scale(1.4); opacity: 0; }
            100% { transform: scale(1.4); opacity: 0; }
        }

        /* animated dot for facility markers */
        .facility-pulse {
            width: 14px; height: 14px; border-radius: 50%;
            background: #10B981; border: 3px solid white;
            box-shadow: 0 0 0 rgba(16,185,129,0.6);
            animation: facilityPulse 1.8s infinite;
        }
        @keyframes facilityPulse {
            0%   { box-shadow: 0 0 0 0 rgba(16,185,129,0.6); }
            70%  { box-shadow: 0 0 0 14px rgba(16,185,129,0); }
            100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
        }

        .facility-tooltip {
            border-radius: 12px !important;
            border: none !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.18) !important;
            font-family: 'Poppins', sans-serif;
            padding: 10px 12px !important;
            max-width: 70vw;
            white-space: normal !important;
        }

        /* subtle drop-in animation for markers on load */
        .leaflet-marker-icon {
            animation: markerDrop 0.5s ease-out;
        }
        @keyframes markerDrop {
            0%   { transform: translateY(-14px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        #citySearchList { display: none; }
    </style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-3 sm:px-4 py-4 sm:py-6">
    <h1 class="text-lg sm:text-2xl font-bold text-gray-800 mb-1">Find a Recycling Facility Near You</h1>
    <p id="locationStatus" class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-5">Detecting your location…</p>

    {{-- City search bar --}}
    <div class="glass rounded-2xl p-3 shadow-sm mb-4 flex flex-col sm:flex-row gap-2 sm:items-center">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <input
                id="citySearchInput"
                type="text"
                placeholder="Search facilities by city..."
                autocomplete="off"
                list="citySearchList"
                class="w-full bg-white border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400"
            >
            <datalist id="citySearchList"></datalist>
        </div>
        <button id="clearCitySearch" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
            Show All Cities
        </button>
    </div>

    <div class="glass rounded-xl sm:rounded-2xl p-2 sm:p-3 shadow-sm">
        <div id="map" class="w-full h-[320px] sm:h-[440px] md:h-[480px] rounded-lg sm:rounded-xl overflow-hidden"></div>
    </div>

    <div class="mt-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Nearest Facilities</h2>
        <div id="facilityList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <p class="text-sm text-gray-400 col-span-full">Locating nearby facilities…</p>
        </div>
    </div>
</div>
@endsection

@php
    $facilitiesForMap = $facilities->map(function ($f) {
        return [
            'id' => $f->id,
            'name' => $f->facility_name,
            'city' => $f->city->name ?? '',
            'area' => $f->area,
            'address' => $f->full_address,
            'ewaste' => $f->accepted_ewaste,
            'hours' => $f->working_hours,
            'lat' => (float) $f->latitude,
            'lng' => (float) $f->longitude,
            'pickupUrl' => route('pickup-requests.create', $f->id),
        ];
    })->values();
@endphp

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<script>
    const facilities = @json($facilitiesForMap);

    const map = L.map('map').setView([20.5937, 78.9629], 5);

    // Colorful "Voyager" basemap instead of the plain default OSM tiles
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    const facilityMarkers = {};
    const bounds = [];
    let userLatLng = null;
    let userMarker = null;
    let routingControl = null;
    let activeCity = null;

    facilities.forEach(f => {
        const icon = L.divIcon({ className: '', html: '<div class="facility-pulse"></div>', iconSize: [14, 14] });
        const marker = L.marker([f.lat, f.lng], { icon }).addTo(map);
        facilityMarkers[f.id] = marker;
        bounds.push([f.lat, f.lng]);

        // Hover -> full facility details
        marker.bindTooltip(buildTooltip(f), {
            direction: 'top',
            offset: [0, -10],
            opacity: 1,
            className: 'facility-tooltip'
        });

        // Click -> full detail popup with actions
        marker.bindPopup(buildPopup(f));
        marker.on('popupopen', () => attachPopupHandlers(f));
    });

    if (bounds.length) {
        map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
    }

    // ---------- City search ----------
    const cityListEl = document.getElementById('citySearchList');
    const cityInput = document.getElementById('citySearchInput');
    const clearCityBtn = document.getElementById('clearCitySearch');

    const uniqueCities = [...new Set(facilities.map(f => f.city).filter(Boolean))].sort();
    uniqueCities.forEach(city => {
        const opt = document.createElement('option');
        opt.value = city;
        cityListEl.appendChild(opt);
    });

    function applyCityFilter(cityQuery) {
        activeCity = cityQuery ? cityQuery.trim().toLowerCase() : null;

        const matched = activeCity
            ? facilities.filter(f => (f.city || '').toLowerCase().includes(activeCity))
            : facilities;

        const matchedBounds = [];

        facilities.forEach(f => {
            const isMatch = !activeCity || (f.city || '').toLowerCase().includes(activeCity);
            const marker = facilityMarkers[f.id];
            if (isMatch) {
                if (!map.hasLayer(marker)) marker.addTo(map);
                matchedBounds.push([f.lat, f.lng]);
            } else {
                if (map.hasLayer(marker)) map.removeLayer(marker);
            }
        });

        if (matchedBounds.length) {
            map.fitBounds(matchedBounds, { padding: [40, 40], maxZoom: 13 });
        }

        const statusEl = document.getElementById('locationStatus');
        if (activeCity) {
            statusEl.textContent = matched.length
                ? `Showing ${matched.length} facility(ies) in "${cityQuery}".`
                : `No facilities found for "${cityQuery}".`;
        }

        const withDistance = userLatLng
            ? matched.map(f => ({ ...f, distance: haversine(userLatLng.lat, userLatLng.lng, f.lat, f.lng) })).sort((a, b) => a.distance - b.distance)
            : matched;

        renderFacilityList(withDistance);
    }

    cityInput.addEventListener('input', () => applyCityFilter(cityInput.value));

    clearCityBtn.addEventListener('click', () => {
        cityInput.value = '';
        applyCityFilter('');
        if (bounds.length) map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
    });

    function haversine(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLon/2)**2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    function distanceLabel(f) {
        if (!userLatLng) return '';
        const d = haversine(userLatLng.lat, userLatLng.lng, f.lat, f.lng);
        return `<p style="font-size:11px;color:#10B981;font-weight:600;margin-top:4px">${d.toFixed(1)} km away</p>`;
    }

    function buildTooltip(f) {
        return `
            <div style="min-width:210px;max-width:240px">
                <p style="font-weight:700;font-size:13px;color:#0B1720;margin-bottom:2px">${f.name}</p>
                <p style="font-size:11px;color:#64748B;margin-bottom:4px">${f.area}, ${f.city}</p>
                ${distanceLabel(f)}
                <div style="height:1px;background:#E2E8F0;margin:6px 0"></div>
                <p style="font-size:11px;color:#334155;margin-bottom:3px"><strong>Address:</strong> ${f.address}</p>
                <p style="font-size:11px;color:#334155;margin-bottom:3px"><strong>Accepts:</strong> ${f.ewaste}</p>
                <p style="font-size:11px;color:#334155"><strong>Hours:</strong> ${f.hours}</p>
            </div>
        `;
    }

    function buildPopup(f) {
        let distHtml = '';
        if (userLatLng) {
            const d = haversine(userLatLng.lat, userLatLng.lng, f.lat, f.lng);
            distHtml = `<p style="font-size:12px;color:#10B981;font-weight:600;margin-bottom:6px">${d.toFixed(1)} km away</p>`;
        }
        return `
            <div style="min-width:190px;max-width:250px">
                <p style="font-weight:700;font-size:14px;color:#0B1720;margin-bottom:2px">${f.name}</p>
                <p style="font-size:12px;color:#64748B;margin-bottom:6px">${f.area}, ${f.city}</p>
                ${distHtml}
                <p style="font-size:12px;color:#334155;margin-bottom:4px"><strong>Address:</strong> ${f.address}</p>
                <p style="font-size:12px;color:#334155;margin-bottom:4px"><strong>Accepts:</strong> ${f.ewaste}</p>
                <p style="font-size:12px;color:#334155;margin-bottom:10px"><strong>Hours:</strong> ${f.hours}</p>
                <div style="display:flex;gap:8px">
                    <a href="${f.pickupUrl}" style="flex:1;text-align:center;background:linear-gradient(135deg,#10B981,#34D399);color:white;font-weight:600;font-size:12px;padding:8px 0;border-radius:8px;text-decoration:none">
                        E-Waste Pickup Request
                    </a>
                    <button id="directionsBtn-${f.id}" style="flex:1;text-align:center;background:#F1F5F9;color:#334155;font-weight:600;font-size:12px;padding:8px 0;border-radius:8px;border:none;cursor:pointer">
                        Get Directions
                    </button>
                </div>
            </div>
        `;
    }

    function attachPopupHandlers(f) {
        const btn = document.getElementById(`directionsBtn-${f.id}`);
        if (!btn) return;

        btn.addEventListener('click', () => {
            if (!userLatLng) {
                alert('Please allow location access to get directions.');
                return;
            }
            showRoute(userLatLng, { lat: f.lat, lng: f.lng });
        });
    }

    function showRoute(from, to) {
        if (routingControl) {
            map.removeControl(routingControl);
        }

        // Distinct, high-contrast color so the shortest path stands out on the colorful basemap
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(from.lat, from.lng),
                L.latLng(to.lat, to.lng)
            ],
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            show: false,
            createMarker: () => null,
            lineOptions: {
                styles: [
                    { color: '#1D4ED8', weight: 7, opacity: 0.25 },
                    { color: '#2563EB', weight: 4, opacity: 0.95 }
                ]
            }
        }).addTo(map);
    }

    function renderFacilityList(sorted) {
        const container = document.getElementById('facilityList');
        container.innerHTML = '';

        sorted.forEach(f => {
            const distText = f.distance !== undefined ? `${f.distance.toFixed(1)} km away` : '';
            const card = document.createElement('div');
            card.className = 'bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-emerald-300 transition cursor-pointer';
            card.innerHTML = `
                <p class="font-semibold text-gray-800 text-sm">${f.name}</p>
                <p class="text-xs text-gray-500 mt-0.5">${f.area}, ${f.city}</p>
                ${distText ? `<p class="text-xs text-emerald-600 font-semibold mt-2">${distText}</p>` : ''}
                <div class="flex gap-2 mt-3">
                    <a href="${f.pickupUrl}" class="flex-1 text-center bg-gradient-to-r from-emerald-500 to-emerald-400 hover:from-emerald-600 hover:to-emerald-500 text-white text-xs font-semibold py-2 rounded-lg transition">
                        E-Waste Pickup Request
                    </a>
                    <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2 rounded-lg transition directions-btn" data-lat="${f.lat}" data-lng="${f.lng}">
                        Directions
                    </button>
                </div>
            `;

            card.addEventListener('click', (e) => {
                if (e.target.closest('a') || e.target.closest('.directions-btn')) return;
                map.flyTo([f.lat, f.lng], 15, { duration: 1.2 });
                facilityMarkers[f.id].openPopup();
            });

            card.querySelector('.directions-btn').addEventListener('click', () => {
                if (!userLatLng) {
                    alert('Please allow location access to get directions.');
                    return;
                }
                showRoute(userLatLng, { lat: f.lat, lng: f.lng });
                map.flyTo([f.lat, f.lng], 14, { duration: 1.2 });
            });

            container.appendChild(card);
        });
    }

    function locateUser() {
        const statusEl = document.getElementById('locationStatus');

        if (!navigator.geolocation) {
            statusEl.textContent = 'Location not supported by your browser.';
            renderFacilityList(facilities);
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                userLatLng = { lat: pos.coords.latitude, lng: pos.coords.longitude };

                const userIcon = L.divIcon({
                    className: '',
                    html: '<div class="user-pulse-wrap"><div class="user-pulse-ring"></div><div class="user-pulse-dot"></div></div>',
                    iconSize: [18, 18],
                    iconAnchor: [9, 9]
                });

                userMarker = L.marker([userLatLng.lat, userLatLng.lng], { icon: userIcon, zIndexOffset: 1000 })
                    .addTo(map)
                    .bindTooltip(' Your Location', {
                        direction: 'top',
                        offset: [0, -10],
                        opacity: 1,
                        className: 'facility-tooltip'
                    })
                    .bindPopup('<strong>Your Location</strong>');

                statusEl.textContent = 'Showing facilities near your current location.';

                // Refresh every facility's hover tooltip now that distance is known
                facilities.forEach(f => {
                    facilityMarkers[f.id].setTooltipContent(buildTooltip(f));
                });

                const withDistance = facilities.map(f => ({
                    ...f,
                    distance: haversine(userLatLng.lat, userLatLng.lng, f.lat, f.lng)
                })).sort((a, b) => a.distance - b.distance);

                renderFacilityList(withDistance);

                map.flyTo([userLatLng.lat, userLatLng.lng], 12, { duration: 1.2 });
            },
            () => {
                statusEl.textContent = 'Location access denied. Showing all facilities.';
                renderFacilityList(facilities);
            },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    }

    locateUser();
</script>
@endpush