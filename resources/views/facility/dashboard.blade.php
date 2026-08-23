@extends('facility.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="max-w-5xl mx-auto px-1 sm:px-0">
        <div class="glass rounded-2xl p-5 shadow-sm hover:shadow-md transition">
            <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Facility Name</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ $facility->facility_name }}</p>
        </div>

        <div class="glass rounded-2xl p-5 shadow-sm hover:shadow-md transition">
            <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Total Pickups</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pickupsCount }}</p>
        </div>

        <div class="glass rounded-2xl p-5 shadow-sm hover:shadow-md transition">
            <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Pending Pickups</p>
            <p class="text-3xl font-bold text-amber-600 mt-2">{{ $pendingPickups }}</p>
        </div>

    </div>

    <div class="glass rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('facility.pickups.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-sm transition">
                View Pickups
            </a>
            <a href="{{ route('facility.profile.edit') }}"
               class="bg-white hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-xl border border-gray-200 shadow-sm transition">
                Edit Facility Profile
            </a>
        </div>
    </div>

</div>
@endsection