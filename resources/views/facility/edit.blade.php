@extends('facility.layouts.app')

@section('title', 'Edit Facility Profile')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass rounded-2xl p-8 shadow-sm">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Edit Facility Profile</h2>
        <p class="text-sm text-gray-500 mb-6">Update your facility information.</p>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('facility.profile.update') }}">
            @csrf
            @method('PUT')
            @include('facility.partials.form')

            <div class="mt-6 flex gap-3">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-sm transition">
                    Save Changes
                </button>
                <a href="{{ route('facility.dashboard') }}"
                   class="bg-white hover:bg-gray-50 text-gray-700 font-medium px-6 py-3 rounded-xl border border-gray-200 shadow-sm transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection