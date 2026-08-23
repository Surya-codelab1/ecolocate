@extends('admin.layouts.app')
@section('page-title', 'Add New Device')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-6">
    <h2 class="font-heading font-bold text-lg text-[#0B1720] mb-1">Add New Device</h2>
    <p class="text-sm text-[#64748B] mb-5">Add a device to the recycling catalog.</p>

    @if ($errors->any())
        <div class="mb-4 bg-[#EF4444]/10 border border-[#EF4444]/30 text-[#EF4444] px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.devices.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-[#0B1720]">Brand</label>
                <input type="text" name="brand" value="{{ old('brand') }}" placeholder="e.g. Vivo"
                    class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
            </div>
            <div>
                <label class="text-sm font-semibold text-[#0B1720]">Model Name</label>
                <input type="text" name="model_name" value="{{ old('model_name') }}" placeholder="e.g. V29"
                    class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
            </div>
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Category</label>
            <select name="category" class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
                <option value="">Select category</option>
                <option value="Smartphone" {{ old('category') == 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
                <option value="Laptop" {{ old('category') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                <option value="Tablet" {{ old('category') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                <option value="Monitor" {{ old('category') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                <option value="Battery" {{ old('category') == 'Battery' ? 'selected' : '' }}>Battery</option>
                <option value="Keyboard/Mouse" {{ old('category') == 'Keyboard/Mouse' ? 'selected' : '' }}>Keyboard/Mouse</option>
                <option value="Charger/Cable" {{ old('category') == 'Charger/Cable' ? 'selected' : '' }}>Charger/Cable</option>
                <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Description</label>
            <textarea name="description" rows="2"
                class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Recoverable Materials</label>
            <input type="text" name="materials" value="{{ old('materials') }}" placeholder="e.g. Copper, Aluminium, Gold"
                class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Harmful Components</label>
            <input type="text" name="harmful_components" value="{{ old('harmful_components') }}" placeholder="e.g. Lead, Mercury, Cadmium"
                class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-[#0B1720]">Estimated Recycling Value (₹)</label>
                <input type="number" step="0.01" name="estimated_recycling_value" value="{{ old('estimated_recycling_value') }}"
                    class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
            </div>
            <div>
                <label class="text-sm font-semibold text-[#0B1720]">EcoCredits</label>
                <input type="number" name="eco_credits" value="{{ old('eco_credits') }}" placeholder="e.g. 150"
                    class="mt-1 w-full border border-[#10B981]/40 bg-[#10B981]/5 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#10B981]">
            </div>
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Recycling Information</label>
            <textarea name="recycling_information" rows="2" placeholder="Tips on how to prepare this device for recycling"
                class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">{{ old('recycling_information') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-[#10B981] hover:bg-[#0ea371] text-white px-6 py-2.5 rounded-xl font-semibold text-sm shadow transition">
                Save Device
            </button>
            <a href="{{ route('admin.devices.index') }}" class="bg-[#F7FAF8] text-[#64748B] px-6 py-2.5 rounded-xl font-semibold text-sm border border-[#E2E8F0] hover:bg-gray-100 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection