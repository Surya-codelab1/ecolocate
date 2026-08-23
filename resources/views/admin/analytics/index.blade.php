@extends('admin.layouts.app')
@section('page-title', 'Analytics')

@section('content')
<div class="mb-6">
    <h2 class="font-heading font-bold text-xl text-[#0B1720]">Analytics</h2>
    <p class="text-sm text-[#64748B] mt-0.5">Platform trends across facilities, devices, and pickups.</p>
</div>

@php
    // Palette pulled straight from cards used elsewhere in the admin
    $palette = ['#10B981', '#22D3EE', '#F59E0B', '#34D399', '#6366F1', '#F43F5E', '#A78BFA', '#FBBF24'];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Facilities by City --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#10B981] to-[#34D399] flex items-center justify-center shadow-[0_4px_14px_rgba(16,185,129,0.25)]">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18"/><path d="M5 21V7l7-4v18"/><path d="M12 10h7v11"/>
                </svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-[#0B1720]">Facilities by City</h3>
                <p class="text-xs text-[#64748B] mt-0.5">Distribution across locations</p>
            </div>
        </div>
        <div class="relative h-72">
            <canvas id="facilitiesByCityChart"></canvas>
        </div>
    </div>

    {{-- Device Categories --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#22D3EE] to-[#67E8F9] flex items-center justify-center shadow-[0_4px_14px_rgba(34,211,238,0.25)]">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="7" y="2" width="10" height="20" rx="2.5"/><path d="M11 18h2"/>
                </svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-[#0B1720]">Device Categories</h3>
                <p class="text-xs text-[#64748B] mt-0.5">Breakdown by device type</p>
            </div>
        </div>
        <div class="relative h-72">
            <canvas id="deviceCategoriesChart"></canvas>
        </div>
    </div>

    {{-- Pickup Status --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#F59E0B] to-[#FBBF24] flex items-center justify-center shadow-[0_4px_14px_rgba(245,158,11,0.25)]">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m21 8-9-5-9 5 9 5 9-5Z"/><path d="M3 8v9l9 5 9-5V8"/><path d="M12 13v9"/>
                </svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-[#0B1720]">Pickup Status</h3>
                <p class="text-xs text-[#64748B] mt-0.5">Current stage of all requests</p>
            </div>
        </div>
        <div class="relative h-72">
            <canvas id="pickupStatsChart"></canvas>
        </div>
    </div>

    {{-- EcoCredits Monthly --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#34D399] to-[#6EE7B7] flex items-center justify-center shadow-[0_4px_14px_rgba(52,211,153,0.25)]">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M9.5 15.5c.7.6 1.6 1 2.5 1 1.7 0 3-1 3-2.5S13.7 11.5 12 11.5s-3-1-3-2.5 1.3-2.5 3-2.5c.9 0 1.8.4 2.5 1"/>
                    <path d="M12 6.5v11"/>
                </svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-[#0B1720]">EcoCredits Issued</h3>
                <p class="text-xs text-[#64748B] mt-0.5">Monthly trend</p>
            </div>
        </div>
        <div class="relative h-72">
            <canvas id="ecoCreditsChart"></canvas>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/chart.js') }}"></script>
<script src="{{ asset('assets/admin/js/count.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const palette = @json($palette);

    // ── Facilities by City (Bar) ─────────────────────────
    const facilitiesByCity = @json($facilitiesByCity);
    new Chart(document.getElementById('facilitiesByCityChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(facilitiesByCity),
            datasets: [{
                label: 'Facilities',
                data: Object.values(facilitiesByCity),
                backgroundColor: '#10B981',
                borderRadius: 8,
                maxBarThickness: 36,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#F1F5F9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // ── Device Categories (Doughnut) ─────────────────────
    const deviceCategories = @json($deviceCategories);
    new Chart(document.getElementById('deviceCategoriesChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(deviceCategories),
            datasets: [{
                data: Object.values(deviceCategories),
                backgroundColor: palette,
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14, font: { size: 11 } } } }
        }
    });

    // ── Pickup Status (Doughnut) ──────────────────────────
    const pickupStats = @json($pickupStats);
    const statusColors = {
        'Requested': '#94A3B8',
        'Accepted':  '#22D3EE',
        'Scheduled': '#F59E0B',
        'Collected': '#34D399',
        'Completed': '#10B981',
        'Cancelled': '#F43F5E',
    };
    new Chart(document.getElementById('pickupStatsChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(pickupStats),
            datasets: [{
                data: Object.values(pickupStats),
                backgroundColor: Object.keys(pickupStats).map(s => statusColors[s] || '#CBD5E1'),
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14, font: { size: 11 } } } }
        }
    });

    // ── EcoCredits Monthly (Line) ─────────────────────────
    const ecoCreditsMonthly = @json($ecoCreditsMonthly);
    new Chart(document.getElementById('ecoCreditsChart'), {
        type: 'line',
        data: {
            labels: Object.keys(ecoCreditsMonthly),
            datasets: [{
                label: 'EcoCredits',
                data: Object.values(ecoCreditsMonthly),
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#10B981',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.35,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#F1F5F9' } },
                x: { grid: { display: false } }
            }
        }
    });

});
</script>
@endpush