@extends('admin.layouts.app')
@section('page-title', 'Cities')

@section('content')

{{-- Success toast --}}
@if(session('success'))
    <div class="mb-5 flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-5 flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-600 text-sm font-medium px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="9"/><path d="M12 8v5"/><path d="M12 16h.01"/>
        </svg>
        <ul class="space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">

    {{-- Header row --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-[#E2E8F0]">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#10B981] to-[#34D399] flex items-center justify-center shadow-[0_4px_14px_rgba(16,185,129,0.25)]">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18"/><path d="M5 21V7l7-4v18"/><path d="M12 10h7v11"/>
                </svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-[#0B1720]">All Cities</h3>
                <p class="text-xs text-[#64748B] mt-0.5">{{ $cities->total() }} total cities</p>
            </div>
        </div>

        <button onclick="openModal('addCityModal')"
                class="inline-flex items-center gap-2 bg-[#10B981] hover:bg-[#0EA271] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-[0_4px_14px_rgba(16,185,129,0.25)]">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14"/><path d="M5 12h14"/>
            </svg>
            Add City
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#F8FAFC] text-[#64748B] text-xs uppercase tracking-wider">
                    <th class="text-left font-semibold px-6 py-3">City</th>
                    <th class="text-left font-semibold px-6 py-3">State</th>
                    <th class="text-left font-semibold px-6 py-3">Status</th>
                    <th class="text-left font-semibold px-6 py-3">Added</th>
                    <th class="text-right font-semibold px-6 py-3">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#E2E8F0]">
                @forelse($cities as $city)
                    <tr class="hover:bg-[#F8FAFC] transition-colors">

                        <td class="px-6 py-4 font-semibold text-[#0B1720]">{{ $city->name }}</td>
                        <td class="px-6 py-4 text-[#334155]">{{ $city->state }}</td>

                        <td class="px-6 py-4">
                            @if($city->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-[#64748B]">{{ $city->created_at->format('d M, Y') }}</td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1.5">

                                {{-- Edit --}}
                                <button onclick="openModal('editCityModal{{ $city->id }}')"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-[#64748B] hover:text-[#10B981] hover:bg-[#10B981]/[0.08] transition-colors">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/>
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST"
                                      onsubmit="return confirm('Remove {{ $city->name }}? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-[#64748B] hover:text-red-500 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal (per row) --}}
                    <div id="editCityModal{{ $city->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-[#0B1720]/50 backdrop-blur-sm" onclick="closeModal('editCityModal{{ $city->id }}')"></div>

                        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="font-heading font-bold text-lg text-[#0B1720]">Edit City</h3>
                                <button onclick="closeModal('editCityModal{{ $city->id }}')" class="text-[#94A3B8] hover:text-[#0B1720] transition-colors">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <form action="{{ route('admin.cities.update', $city->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block text-xs font-semibold text-[#64748B] mb-1.5">City Name</label>
                                    <input type="text" name="name" value="{{ $city->name }}" required
                                           class="w-full rounded-xl border border-[#E2E8F0] px-3.5 py-2.5 text-sm text-[#0B1720] focus:outline-none focus:ring-2 focus:ring-[#10B981]/30 focus:border-[#10B981]">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-[#64748B] mb-1.5">State</label>
                                    <input type="text" name="state" value="{{ $city->state }}" required
                                           class="w-full rounded-xl border border-[#E2E8F0] px-3.5 py-2.5 text-sm text-[#0B1720] focus:outline-none focus:ring-2 focus:ring-[#10B981]/30 focus:border-[#10B981]">
                                </div>

                                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                    <input type="checkbox" name="is_active" value="1" @checked($city->is_active)
                                           class="w-4 h-4 rounded border-[#E2E8F0] text-[#10B981] focus:ring-[#10B981]/30">
                                    <span class="text-sm text-[#334155] font-medium">Active</span>
                                </label>

                                <div class="flex items-center gap-3 pt-2">
                                    <button type="button" onclick="closeModal('editCityModal{{ $city->id }}')"
                                            class="flex-1 text-sm font-semibold text-[#64748B] border border-[#E2E8F0] rounded-xl py-2.5 hover:bg-[#F8FAFC] transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="flex-1 text-sm font-semibold text-white bg-[#10B981] hover:bg-[#0EA271] rounded-xl py-2.5 transition-colors">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#94A3B8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 21h18"/><path d="M5 21V7l7-4v18"/><path d="M12 10h7v11"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-[#64748B]">No cities added yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($cities->hasPages())
        <div class="px-6 py-4 border-t border-[#E2E8F0]">
            {{ $cities->links() }}
        </div>
    @endif

</div>

{{-- Add City Modal --}}
<div id="addCityModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-[#0B1720]/50 backdrop-blur-sm" onclick="closeModal('addCityModal')"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-heading font-bold text-lg text-[#0B1720]">Add New City</h3>
            <button onclick="closeModal('addCityModal')" class="text-[#94A3B8] hover:text-[#0B1720] transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.cities.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-[#64748B] mb-1.5">City Name</label>
                <input type="text" name="name" placeholder="e.g. Mumbai" required
                       class="w-full rounded-xl border border-[#E2E8F0] px-3.5 py-2.5 text-sm text-[#0B1720] placeholder:text-[#CBD5E1] focus:outline-none focus:ring-2 focus:ring-[#10B981]/30 focus:border-[#10B981]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#64748B] mb-1.5">State</label>
                <input type="text" name="state" placeholder="e.g. Maharashtra" required
                       class="w-full rounded-xl border border-[#E2E8F0] px-3.5 py-2.5 text-sm text-[#0B1720] placeholder:text-[#CBD5E1] focus:outline-none focus:ring-2 focus:ring-[#10B981]/30 focus:border-[#10B981]">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeModal('addCityModal')"
                        class="flex-1 text-sm font-semibold text-[#64748B] border border-[#E2E8F0] rounded-xl py-2.5 hover:bg-[#F8FAFC] transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 text-sm font-semibold text-white bg-[#10B981] hover:bg-[#0EA271] rounded-xl py-2.5 transition-colors">
                    Add City
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    // Reopen the relevant modal automatically if validation fails
    @if($errors->any() && old('name'))
        document.addEventListener('DOMContentLoaded', () => openModal('addCityModal'));
    @endif
</script>
@endpush