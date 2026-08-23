<x-app-layout>

    <div class="py-8" style="background: linear-gradient(135deg, #F7FAF8 0%, #ECFDF5 100%);">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-5 text-sm text-[#10B981] bg-[#10B981]/10 border border-[#10B981]/20 rounded-lg px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <h1 class="font-display font-extrabold text-2xl text-[#0B1720] mb-1">Welcome back{{ auth()->user()->name ? ', ' . explode(' ', auth()->user()->name)[0] : '' }}</h1>
            <p class="text-[#64748B] text-sm mb-6">Here's an overview of your recycling activity.</p>

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5 mb-8">

                <div class="relative overflow-hidden rounded-lg p-6 shadow-sm bg-white border border-[#E2E8F0]">
                    <div class="w-10 h-10 rounded-full bg-[#10B981]/10 flex items-center justify-center text-[#10B981] mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12.5l1.5 1.5 3.5-3.5" />
                        </svg>
                    </div>
                    <p class="text-xs uppercase tracking-wide font-semibold text-[#64748B]">EcoCredits</p>
                    <p class="text-3xl font-extrabold mt-1 font-display text-[#10B981]">
                        {{ number_format($ecoCreditsBalance) }}
                    </p>
                </div>

                <div class="relative overflow-hidden rounded-lg p-6 shadow-sm bg-white border border-[#E2E8F0]">
                    <div class="w-10 h-10 rounded-full bg-[#10B981]/10 flex items-center justify-center text-[#10B981] mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <p class="text-xs uppercase tracking-wide font-semibold text-[#64748B]">Completed Pickups</p>
                    <p class="text-3xl font-extrabold mt-1 font-display text-[#0B1720]">
                        {{ $completedPickups }}
                    </p>
                </div>

                <div class="relative overflow-hidden rounded-lg p-6 shadow-sm bg-white border border-[#E2E8F0]">
                    <div class="w-10 h-10 rounded-full bg-[#10B981]/10 flex items-center justify-center text-[#10B981] mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <p class="text-xs uppercase tracking-wide font-semibold text-[#64748B]">Devices Recycled</p>
                    <p class="text-3xl font-extrabold mt-1 font-display text-[#0B1720]">
                        {{ $devicesRecycled }}
                    </p>
                </div>

            </div>

            {{-- Find facility button --}}
            <div class="mb-8">
                <a href="{{ route('facilities.map') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-[#10B981] to-[#34D399] text-white font-semibold text-sm px-5 py-3 rounded-lg shadow-md shadow-[#10B981]/25 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all">
                    Find a Facility & Request Pickup
                </a>
            </div>

            {{-- EcoCredits Chart --}}
            <div class="rounded-lg p-4 sm:p-6 bg-white border border-[#E2E8F0] shadow-sm mb-8">
                <h3 class="font-display font-bold text-[#0B1720] mb-4 text-sm sm:text-base">EcoCredits Earned Over Time</h3>
                @if(collect($chartData)->sum() > 0)
                    <div class="relative w-full h-56 sm:h-80">
                        <canvas id="ecoCreditsChart"></canvas>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-40 sm:h-56 text-center">
                        <svg class="w-10 h-10 text-[#E2E8F0] mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 15l4-4 3 3 5-6" />
                        </svg>
                        <p class="text-sm text-[#64748B]">No EcoCredits earned yet</p>
                        <p class="text-xs text-[#64748B] mt-1">Your chart will appear here once a pickup is completed</p>
                    </div>
                @endif
            </div>

            {{-- Pickup history --}}
            <div class="rounded-lg bg-white border border-[#E2E8F0] shadow-sm overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-[#E2E8F0]">
                    <h3 class="font-display font-bold text-[#0B1720]">My Pickup Requests</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[700px]">
                        <thead>
                            <tr class="text-left text-[#64748B] uppercase text-xs tracking-wide">
                                <th class="px-4 sm:px-6 py-3">Facility</th>
                                <th class="px-4 sm:px-6 py-3">Device</th>
                                <th class="px-4 sm:px-6 py-3">Preferred Date</th>
                                <th class="px-4 sm:px-6 py-3">Status</th>
                                <th class="px-4 sm:px-6 py-3">Certificate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2E8F0]">
                            @forelse($pickups as $pickup)
                                <tr class="hover:bg-[#10B981]/5 transition">
                                    <td class="px-4 sm:px-6 py-4 font-medium text-[#0B1720]">{{ $pickup->facility->facility_name ?? 'N/A' }}</td>
                                    <td class="px-4 sm:px-6 py-4 text-[#64748B]">
                                        {{ $pickup->device->brand ?? '' }} {{ $pickup->device->model_name ?? '' }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-[#64748B]">{{ $pickup->preferred_date?->format('d M Y') }}</td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($pickup->status === 'Requested') bg-amber-100 text-amber-700
                                            @elseif(in_array($pickup->status, ['Accepted','Scheduled'])) bg-blue-100 text-blue-700
                                            @elseif(in_array($pickup->status, ['Collected','Completed'])) bg-[#10B981]/10 text-[#10B981]
                                            @else bg-red-100 text-red-700
                                            @endif">
                                            {{ $pickup->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        @if($pickup->certificate_path)
                                            <a href="{{ asset('storage/' . $pickup->certificate_path) }}" target="_blank"
                                               class="text-[#10B981] font-semibold text-xs hover:underline">
                                                Download
                                            </a>
                                        @else
                                            <span class="text-[#64748B] text-xs">Not available yet</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-[#64748B]">
                                        No pickup requests yet. Find a facility to get started.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pickups->hasPages())
                    <div class="px-4 sm:px-6 py-4 border-t border-[#E2E8F0]">
                        {{ $pickups->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('ecoCreditsChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'EcoCredits',
                        data: @json($chartData),
                        fill: true,
                        tension: 0.35,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        pointBackgroundColor: '#10B981',
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    </script>
    @endpush
</x-app-layout>