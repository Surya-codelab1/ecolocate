@extends('facility.layouts.app')

@section('title', 'Create Facility')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass rounded-2xl p-8 shadow-sm">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Create Facility Request</h2>
        <p class="text-sm text-gray-500 mb-6">Fill in your facility details for admin approval.</p>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('facility.store') }}">
            @csrf
            @include('facility.partials.form')

            <div class="mt-6">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-sm transition">
                    Submit for Approval
                </button>
            </div>
        </form>
    </div>
</div>
@endsection