@extends('admin.layouts.app')
@section('page-title', 'Devices')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="font-heading font-bold text-xl text-[#0B1720]">Devices</h2>
        <p class="text-sm text-[#64748B] mt-0.5">Manage the device catalog and recycling values.</p>
    </div>

    <a href="{{ route('admin.devices.create') }}"
       class="flex items-center gap-2 bg-[#10B981] hover:bg-[#0ea371] text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14M5 12h14"/>
        </svg>
        Add New Device
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-[#F7FAF8] text-[#64748B] text-left">
            <tr>
                <th class="px-5 py-3 font-semibold">Brand</th>
                <th class="px-5 py-3 font-semibold">Model</th>
                <th class="px-5 py-3 font-semibold">Category</th>
                <th class="px-5 py-3 font-semibold">Recycling Value</th>
                <th class="px-5 py-3 font-semibold">EcoCredits</th>
                <th class="px-5 py-3 font-semibold text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#E2E8F0]">
            @forelse($devices as $device)
            <tr class="hover:bg-[#F7FAF8] transition">
                <td class="px-5 py-3 font-medium text-[#0B1720]">{{ $device->brand }}</td>
                <td class="px-5 py-3 text-[#64748B]">{{ $device->model_name }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-[#22D3EE]/10 text-[#0E7490]">
                        {{ $device->category }}
                    </span>
                </td>
                <td class="px-5 py-3 text-[#64748B]">₹{{ number_format($device->estimated_recycling_value ?? 0) }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#10B981]/10 text-[#10B981] rounded-full text-xs font-semibold">
                        {{ $device->eco_credits }} pts
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.devices.edit', $device->id) }}"
                           class="text-[#10B981] font-semibold hover:underline">Edit</a>
                        <form action="{{ route('admin.devices.destroy', $device->id) }}" method="POST"
                              onsubmit="return confirm('Delete this device?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[#EF4444] font-semibold hover:underline">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-10 text-[#64748B]">
                    No devices added yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $devices->links() }}</div>
@endsection