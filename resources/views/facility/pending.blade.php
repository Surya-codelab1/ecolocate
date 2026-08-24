@extends('facility.layouts.app')

@section('title', 'Pending Approval')
@section('page-title', 'Facility Status')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass rounded-2xl p-8 shadow-sm text-center">

        @if($facilityRequest->status === 'pending')
            <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-amber-500/10 border border-amber-400/30 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5l3 2"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-[#0B1720] mb-2">Request Pending Approval</h2>
            <p class="text-[#64748B] text-sm leading-relaxed max-w-md mx-auto">
                Your facility request for
                <span class="font-semibold text-[#0B1720]">{{ $facilityRequest->facility_name }}</span>
                is currently under review. You will get access to your dashboard once an admin approves the request.
            </p>

        @elseif($facilityRequest->status === 'rejected')
            <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-red-500/10 border border-red-400/30 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M15 9l-6 6M9 9l6 6"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-[#0B1720] mb-2">Request Rejected</h2>
            <p class="text-[#64748B] text-sm leading-relaxed max-w-md mx-auto">
                Your facility request for
                <span class="font-semibold text-[#0B1720]">{{ $facilityRequest->facility_name }}</span>
                was not approved. Please contact support for more information.
            </p>

        @else
            <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-gray-500/10 border border-gray-300 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 8v5"/>
                    <path d="M12 16h.01"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-[#0B1720] mb-2">Status: {{ ucfirst($facilityRequest->status) }}</h2>
            <p class="text-[#64748B] text-sm">
                Your request for
                <span class="font-semibold text-[#0B1720]">{{ $facilityRequest->facility_name }}</span>
                is currently marked as <span class="font-semibold">{{ $facilityRequest->status }}</span>.
            </p>
        @endif

        <div class="mt-8 pt-6 border-t border-[#E2E8F0]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 text-sm font-medium text-[#64748B] hover:text-red-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 3v18"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

    </div>
</div>
@endsection