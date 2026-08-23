@extends('admin.layouts.app')
@section('page-title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="font-heading font-bold text-xl text-[#0B1720]">Dashboard</h2>
    <p class="text-sm text-[#64748B] mt-0.5">Live snapshot of platform activity.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    @php
        $cards = [
            [
                'label' => 'Total Users',
                'value' => $stats['total_users'],
                'color' => 'from-[#10B981] to-[#34D399]',
                'icon' => 'users',
            ],
            [
                'label' => 'Facilities',
                'value' => $stats['total_facilities'],
                'color' => 'from-[#22D3EE] to-[#67E8F9]',
                'icon' => 'building',
            ],
            [
                'label' => 'Pending Facilities',
                'value' => $stats['pending_facilities'],
                'color' => 'from-[#F59E0B] to-[#FBBF24]',
                'icon' => 'clock',
            ],
            [
                'label' => 'Total Devices',
                'value' => $stats['total_devices'],
                'color' => 'from-[#34D399] to-[#6EE7B7]',
                'icon' => 'device',
            ],
            [
                'label' => 'Pending Device Requests',
                'value' => $stats['pending_device_reqs'],
                'color' => 'from-[#F59E0B] to-[#FBBF24]',
                'icon' => 'clipboard',
            ],
            [
                'label' => 'Total Pickups',
                'value' => $stats['total_pickups'],
                'color' => 'from-[#10B981] to-[#34D399]',
                'icon' => 'package',
            ],
            [
                'label' => 'Completed Pickups',
                'value' => $stats['completed_pickups'],
                'color' => 'from-[#22D3EE] to-[#67E8F9]',
                'icon' => 'check',
            ],
            [
                'label' => 'EcoCredits Issued',
                'value' => $stats['ecocredits_issued'],
                'color' => 'from-[#34D399] to-[#6EE7B7]',
                'icon' => 'coin',
            ],
        ];
    @endphp

    @foreach($cards as $card)
        <div class="group relative bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-5 hover:shadow-lg hover:-translate-y-0.5 hover:border-[#10B981]/30 transition-all duration-300 overflow-hidden">

            {{-- subtle glow on hover --}}
            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $card['color'] }} opacity-0 group-hover:opacity-10 blur-2xl transition-opacity duration-300"></div>

            <div class="relative flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $card['color'] }} flex items-center justify-center shadow-[0_4px_14px_rgba(16,185,129,0.25)]">

                    {{-- Icons --}}
                    @switch($card['icon'])
                        @case('users')
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            @break
                        @case('building')
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21h18"/>
                                <path d="M5 21V5l7-2v18"/>
                                <path d="M12 7h7v14"/>
                                <path d="M8 8h1"/><path d="M8 12h1"/><path d="M8 16h1"/>
                                <path d="M15 10h1"/><path d="M15 14h1"/><path d="M15 18h1"/>
                            </svg>
                            @break
                        @case('clock')
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 3"/>
                            </svg>
                            @break
                        @case('device')
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="7" y="2" width="10" height="20" rx="2.5"/>
                                <path d="M11 18h2"/>
                            </svg>
                            @break
                        @case('clipboard')
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="4" width="14" height="17" rx="2"/>
                                <path d="M9 4V2h6v2"/>
                                <path d="M9 10h6"/>
                                <path d="M9 14h4"/>
                            </svg>
                            @break
                        @case('package')
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m21 8-9-5-9 5 9 5 9-5Z"/>
                                <path d="M3 8v9l9 5 9-5V8"/>
                                <path d="M12 13v9"/>
                            </svg>
                            @break
                        @case('check')
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            @break
                        @case('coin')
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M9.5 15.5c.7.6 1.6 1 2.5 1 1.7 0 3-1 3-2.5S13.7 11.5 12 11.5s-3-1-3-2.5 1.3-2.5 3-2.5c.9 0 1.8.4 2.5 1"/>
                                <path d="M12 6.5v11"/>
                            </svg>
                            @break
                    @endswitch

                </div>

                <span class="w-2 h-2 rounded-full bg-gradient-to-br {{ $card['color'] }}"></span>
            </div>

            <h3 class="relative text-2xl font-heading font-extrabold text-[#0B1720] counter" data-target="{{ $card['value'] }}">0</h3>
            <p class="relative text-sm text-[#64748B] mt-1">{{ $card['label'] }}</p>
        </div>
    @endforeach
</div>

<div class="mt-8 bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-6 flex items-center justify-center gap-3 text-[#64748B] text-sm">
    <svg class="w-5 h-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 19V5"/>
        <path d="M4 19h17"/>
        <path d="m7 15 4-4 3 2 5-7"/>
        <circle cx="7" cy="15" r="1"/>
        <circle cx="11" cy="11" r="1"/>
        <circle cx="14" cy="13" r="1"/>
        <circle cx="19" cy="6" r="1"/>
    </svg>
    <span>Detailed graphs available on the <a href="{{ route('admin.analytics.index') }}" class="text-[#10B981] font-semibold underline underline-offset-2 hover:text-[#0EA271] transition-colors">Analytics</a> page.</span>
</div>
@push('styles')
<style>
    .grid > div { animation: fadeSlideUp 0.4s ease both; }
    .grid > div:nth-child(1) { animation-delay: 0.02s; }
    .grid > div:nth-child(2) { animation-delay: 0.06s; }
    .grid > div:nth-child(3) { animation-delay: 0.10s; }
    .grid > div:nth-child(4) { animation-delay: 0.14s; }
    .grid > div:nth-child(5) { animation-delay: 0.18s; }
    .grid > div:nth-child(6) { animation-delay: 0.22s; }
    .grid > div:nth-child(7) { animation-delay: 0.26s; }
    .grid > div:nth-child(8) { animation-delay: 0.30s; }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@endsection