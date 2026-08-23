<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Facility Name</label>
        <input type="text" name="facility_name" value="{{ old('facility_name', $facility->facility_name ?? '') }}"
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white/70 focus:ring-2 focus:ring-emerald-400 focus:outline-none"
               placeholder="Enter facility name">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Contact Person</label>
        <input type="text" name="contact_person" value="{{ old('contact_person', $facility->contact_person ?? '') }}"
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white/70 focus:ring-2 focus:ring-emerald-400 focus:outline-none"
               placeholder="Contact person name">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email', $facility->email ?? '') }}"
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white/70 focus:ring-2 focus:ring-emerald-400 focus:outline-none"
               placeholder="Email address">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1.5">City</label>
        <select name="city_id"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white/70 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
            <option value="">Select City</option>
            @forelse($cities as $city)
                <option value="{{ $city->id }}"
                    @selected(old('city_id', $facility->city_id ?? '') == $city->id)>
                    {{ $city->name }}
                </option>
            @empty
                <option value="" disabled>No cities available — add cities first</option>
            @endforelse
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Area</label>
        <input type="text" name="area" value="{{ old('area', $facility->area ?? '') }}"
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white/70 focus:ring-2 focus:ring-emerald-400 focus:outline-none"
               placeholder="Area / locality">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Working Hours</label>
        <input type="text" name="working_hours" value="{{ old('working_hours', $facility->working_hours ?? '') }}"
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white/70 focus:ring-2 focus:ring-emerald-400 focus:outline-none"
               placeholder="e.g. 9 AM - 6 PM">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Full Address</label>
        <textarea name="full_address" rows="3"
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white/70 focus:ring-2 focus:ring-emerald-400 focus:outline-none"
                  placeholder="Complete address">{{ old('full_address', $facility->full_address ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Accepted E-Waste</label>
        <input type="text" name="accepted_ewaste" value="{{ old('accepted_ewaste', $facility->accepted_ewaste ?? '') }}"
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white/70 focus:ring-2 focus:ring-emerald-400 focus:outline-none"
               placeholder="e.g. Laptops, Batteries, Mobile phones">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Pickup Availability</label>
        <input type="text" name="pickup_availability" value="{{ old('pickup_availability', $facility->pickup_availability ?? '') }}"
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-white/70 focus:ring-2 focus:ring-emerald-400 focus:outline-none"
               placeholder="e.g. Mon-Sat">
    </div>

</div>