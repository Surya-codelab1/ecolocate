@extends('facility.layouts.app')

@section('page-title', 'Pickup Requests')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-5">Pickup Requests</h2>

        <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
            <table class="w-full text-sm min-w-[640px]">
                <thead>
                    <tr class="text-left text-gray-500 uppercase text-xs tracking-wide border-b border-gray-200">
                        <th class="py-3 pr-4">Date</th>
                        <th class="py-3 pr-4">Time</th>
                        <th class="py-3 pr-4">Address</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3 pr-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pickups as $pickup)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-3 pr-4 font-medium text-gray-700 whitespace-nowrap">{{ $pickup->preferred_date?->format('d M Y') }}</td>
                            <td class="py-3 pr-4 text-gray-600 whitespace-nowrap">{{ $pickup->preferred_time }}</td>
                            <td class="py-3 pr-4 text-gray-600 max-w-[200px] truncate">{{ $pickup->pickup_address }}</td>
                            <td class="py-3 pr-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                                    @if($pickup->status === 'Requested') bg-amber-100 text-amber-700
                                    @elseif(in_array($pickup->status, ['Accepted','Scheduled'])) bg-blue-100 text-blue-700
                                    @elseif(in_array($pickup->status, ['Collected','Completed'])) bg-emerald-100 text-emerald-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ $pickup->status }}
                                </span>
                            </td>
                            <td class="py-3 pr-4">
                                @if($pickup->status === 'Completed')
                                    @if($pickup->certificate_path)
                                        <a href="{{ asset('storage/' . $pickup->certificate_path) }}" target="_blank"
                                           class="text-emerald-600 font-semibold text-xs hover:underline whitespace-nowrap">
                                            View Certificate
                                        </a>
                                    @else
                                        <form method="POST" action="{{ route('facility.pickups.generateCertificate', $pickup->id) }}">
                                            @csrf
                                            <button type="submit" class="text-emerald-600 font-semibold text-xs hover:underline whitespace-nowrap">
                                                Generate Report
                                            </button>
                                        </form>
                                    @endif
                                @elseif(!$pickup->isFinal())
                                    <form method="POST" action="{{ route('facility.pickups.updateStatus', $pickup->id) }}">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()"
                                                class="border border-gray-200 rounded-lg px-2 py-1.5 text-xs bg-white focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                                            <option value="{{ $pickup->status }}" selected>{{ $pickup->status }} (current)</option>
                                            @if($pickup->nextStatus())
                                                <option value="{{ $pickup->nextStatus() }}">{{ $pickup->nextStatus() }}</option>
                                            @endif
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">No pickup requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pickups->links() }}
        </div>
    </div>

</div>
@endsection