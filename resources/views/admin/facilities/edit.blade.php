@extends('admin.layouts.app')
@section('page-title', 'Edit Facility')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-[#E2E8F0] p-6">
    <h2 class="font-heading font-bold text-lg text-[#0B1720] mb-1">Edit Facility</h2>
    <p class="text-sm text-[#64748B] mb-5">Update facility details and status.</p>

    <form action="{{ route('admin.facilities.update', $facility->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Facility Name</label>
            <input type="text" name="facility_name" value="{{ old('facility_name', $facility->facility_name) }}"
                class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Contact Person</label>
            <input type="text" name="contact_person" value="{{ old('contact_person', $facility->contact_person) }}"
                class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Email</label>
            <input type="email" name="email" value="{{ old('email', $facility->email) }}"
                class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Area</label>
            <input type="text" name="area" value="{{ old('area', $facility->area) }}"
                class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Full Address</label>
            <textarea name="full_address" rows="2"
                class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">{{ old('full_address', $facility->full_address) }}</textarea>
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Accepted E-Waste</label>
            <input type="text" name="accepted_ewaste" value="{{ old('accepted_ewaste', $facility->accepted_ewaste) }}"
                placeholder="e.g. Mobile, Laptop, Battery"
                class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-[#0B1720]">Pickup Availability</label>
                <input type="text" name="pickup_availability" value="{{ old('pickup_availability', $facility->pickup_availability) }}"
                    class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
            </div>
            <div>
                <label class="text-sm font-semibold text-[#0B1720]">Working Hours</label>
                <input type="text" name="working_hours" value="{{ old('working_hours', $facility->working_hours) }}"
                    class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
            </div>
        </div>

        <div>
            <label class="text-sm font-semibold text-[#0B1720]">Status</label>
            <select name="status" class="mt-1 w-full border border-[#E2E8F0] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981]">
                <option value="approved" {{ $facility->status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="inactive" {{ $facility->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-[#10B981] hover:bg-[#0ea371] text-white px-6 py-2.5 rounded-xl font-semibold text-sm shadow transition">
                Save Changes
            </button>
            <a href="{{ route('admin.facilities.index') }}" class="bg-[#F7FAF8] text-[#64748B] px-6 py-2.5 rounded-xl font-semibold text-sm border border-[#E2E8F0] hover:bg-gray-100 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection