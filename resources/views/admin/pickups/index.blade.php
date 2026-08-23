@extends('admin.layouts.app')
@section('page-title', 'Pickups')

@section('content')
<div class="flex flex-wrap items-center gap-2 mb-4">
    @php
        $statuses = ['All', 'Requested', 'Accepted', 'Scheduled', 'Collected', 'Completed', 'Cancelled'];
        $current = request('status', 'All');
    @endphp

    @foreach($statuses as $status)
        <a href="{{ $status === 'All' ? route('admin.pickups.index') : route('admin.pickups.index', ['status' => $status]) }}"
           class="px-3.5 py-1.5 rounded-full text-xs font-semibold border transition
                  {{ $current === $status
                      ? 'bg-[#0B1720] text-white border-[#0B1720]'
                      : 'bg-white text-[#64748B] border-[#E2E8F0] hover:border-[#10B981]/40 hover:text-[#10B981]' }}">
            {{ $status }}
        </a>
    @endforeach
</div>

@php
    // Color tokens per status — keeps the visual flow readable at a glance
    $statusStyles = [
        'Requested' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'dot' => 'bg-slate-400'],
        'Accepted'  => ['bg' => 'bg-cyan-50',   'text' => 'text-cyan-700',  'dot' => 'bg-cyan-400'],
        'Scheduled' => ['bg' => 'bg-amber-50',  'text' => 'text-amber-700', 'dot' => 'bg-amber-400'],
        'Collected' => ['bg' => 'bg-emerald-50','text' => 'text-emerald-700','dot' => 'bg-emerald-400'],
        'Completed' => ['bg' => 'bg-emerald-100','text' => 'text-emerald-800','dot' => 'bg-emerald-500'],
        'Cancelled' => ['bg' => 'bg-red-50',    'text' => 'text-red-600',   'dot' => 'bg-red-400'],
    ];
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">

    {{-- Header row --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-[#E2E8F0]">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#10B981] to-[#34D399] flex items-center justify-center shadow-[0_4px_14px_rgba(16,185,129,0.25)]">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m21 8-9-5-9 5 9 5 9-5Z"/>
                    <path d="M3 8v9l9 5 9-5V8"/>
                    <path d="M12 13v9"/>
                </svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-[#0B1720]">All Pickups</h3>
                <p class="text-xs text-[#64748B] mt-0.5">{{ $pickups->total() }} total requests</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="sticky top-0 z-10">
    <tr class="bg-[#F8FAFC] text-[#64748B] text-xs uppercase tracking-wider">
                    <th class="text-left font-semibold px-6 py-3">User</th>
                    <th class="text-left font-semibold px-6 py-3">Device</th>
                    <th class="text-left font-semibold px-6 py-3">Facility</th>
                    <th class="text-left font-semibold px-6 py-3">Pickup Address</th>
                    <th class="text-left font-semibold px-6 py-3">Preferred Date</th>
                    <th class="text-left font-semibold px-6 py-3">Status</th>
                    <th class="text-left font-semibold px-6 py-3">Certificate</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#E2E8F0]">
                @forelse($pickups as $pickup)
                    @php
                        $style = $statusStyles[$pickup->status] ?? $statusStyles['Requested'];
                    @endphp
                    <tr class="hover:bg-[#F8FAFC] transition-colors">

                        {{-- User --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#10B981] to-[#22D3EE] text-white flex items-center justify-center text-xs font-semibold shrink-0">
                                    {{ strtoupper(substr($pickup->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-[#0B1720] truncate">{{ $pickup->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-[#94A3B8] truncate">{{ $pickup->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Device --}}
                        <td class="px-6 py-4">
                            @if($pickup->device)
                                <p class="font-medium text-[#0B1720]">{{ $pickup->device->brand }} {{ $pickup->device->model_name }}</p>
                                <p class="text-xs text-[#94A3B8]">{{ $pickup->device->category }}</p>
                            @else
                                <span class="text-[#334155]">N/A</span>
                            @endif
                        </td>

                        {{-- Facility --}}
                        <td class="px-6 py-4 text-[#334155]">
                            {{ $pickup->facility->name ?? 'N/A' }}
                        </td>

                        {{-- Address --}}
                        <td class="px-6 py-4 text-[#64748B] max-w-[220px]">
                            <span class="line-clamp-2">{{ $pickup->pickup_address }}</span>
                        </td>

                        {{-- Preferred Date/Time --}}
                        <td class="px-6 py-4 text-[#334155]">
                            <div class="flex flex-col">
                                <span>{{ $pickup->preferred_date?->format('d M, Y') ?? '—' }}</span>
                                <span class="text-xs text-[#94A3B8]">{{ $pickup->preferred_time ?? '' }}</span>
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $style['bg'] }} {{ $style['text'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }}"></span>
                                {{ $pickup->status }}
                            </span>
                        </td>

                        {{-- Certificate --}}
                        <td class="px-6 py-4">
                            @if($pickup->certificate_path)
                                <a href="{{ asset('storage/' . $pickup->certificate_path) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 text-[#10B981] hover:text-[#0EA271] font-medium text-xs transition-colors">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <path d="M14 2v6h6"/>
                                    </svg>
                                    View
                                </a>
                            @else
                                <span class="text-xs text-[#CBD5E1]">—</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#94A3B8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m21 8-9-5-9 5 9 5 9-5Z"/>
                                        <path d="M3 8v9l9 5 9-5V8"/>
                                        <path d="M12 13v9"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-[#64748B]">No pickup requests yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pickups->hasPages())
        <div class="px-6 py-4 border-t border-[#E2E8F0]">
            {{ $pickups->links() }}
        </div>
    @endif

</div>

@endsection