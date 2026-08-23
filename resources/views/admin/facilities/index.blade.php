@extends('admin.layouts.app')
@section('page-title', 'Facilities')

@section('content')

<div class="mb-6">
    <h2 class="font-heading font-bold text-xl text-[#0B1720]">Facilities</h2>
    <p class="text-sm text-[#64748B] mt-0.5">Manage all approved and inactive recycling facilities.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-[#F7FAF8] text-[#64748B] text-left">
            <tr>
                <th class="px-5 py-3 font-semibold">Facility Name</th>
                <th class="px-5 py-3 font-semibold">City</th>
                <th class="px-5 py-3 font-semibold">Contact Person</th>
                <th class="px-5 py-3 font-semibold">Status</th>
                <th class="px-5 py-3 font-semibold">Pickup Requests</th>
                <th class="px-5 py-3 font-semibold text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#E2E8F0]">
            @forelse($facilities as $facility)
            <tr class="hover:bg-[#F7FAF8] transition">
                <td class="px-5 py-3 font-medium text-[#0B1720]">{{ $facility->facility_name }}</td>
                <td class="px-5 py-3 text-[#64748B]">{{ $facility->city->name ?? '-' }}</td>
                <td class="px-5 py-3 text-[#64748B]">{{ $facility->contact_person }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                        {{ $facility->status == 'approved' ? 'bg-[#10B981]/10 text-[#10B981]' :
                           ($facility->status == 'inactive' ? 'bg-[#64748B]/10 text-[#64748B]' : 'bg-[#EF4444]/10 text-[#EF4444]') }}">
                        <span class="w-1.5 h-1.5 rounded-full
                            {{ $facility->status == 'approved' ? 'bg-[#10B981]' :
                               ($facility->status == 'inactive' ? 'bg-[#64748B]' : 'bg-[#EF4444]') }}"></span>
                        {{ ucfirst($facility->status) }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="inline-flex items-center px-3 py-1 bg-[#22D3EE]/10 text-[#0B1720] rounded-full text-xs font-semibold">
                        {{ $facility->pickup_requests_count }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.facilities.edit', $facility->id) }}"
                           class="text-[#10B981] font-semibold hover:underline">Edit</a>

                        @if($facility->status === 'approved')
                            <form action="{{ route('admin.facilities.update', $facility->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="facility_name" value="{{ $facility->facility_name }}">
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit" class="text-[#F59E0B] font-semibold hover:underline">Deactivate</button>
                            </form>
                        @else
                            <form action="{{ route('admin.facilities.update', $facility->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="facility_name" value="{{ $facility->facility_name }}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="text-[#10B981] font-semibold hover:underline">Activate</button>
                            </form>
                        @endif

                        <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST"
                              onsubmit="return confirm('Delete this facility permanently?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[#EF4444] font-semibold hover:underline">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-10 text-[#64748B]">
                    No facilities yet. Approve a facility request to get started.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $facilities->links() }}</div>
@endsection