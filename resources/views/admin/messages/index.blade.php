@extends('admin.layouts.app')
@section('page-title', 'Messages')

@section('content')

@php
    // Color tokens per status
    $statusStyles = [
        'New'      => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'dot' => 'bg-amber-400'],
        'Read'     => ['bg' => 'bg-cyan-50',    'text' => 'text-cyan-700',    'dot' => 'bg-cyan-400'],
        'Resolved' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
    ];
@endphp

{{-- Success toast --}}
@if(session('success'))
    <div class="mb-5 flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">

    {{-- Header row --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-[#E2E8F0]">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#10B981] to-[#34D399] flex items-center justify-center shadow-[0_4px_14px_rgba(16,185,129,0.25)]">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 5h16v11H8l-4 4V5Z"/>
                    <path d="M8 9h8"/>
                    <path d="M8 12h5"/>
                </svg>
            </div>
            <div>
                <h3 class="font-heading font-bold text-[#0B1720]">Contact Messages</h3>
                <p class="text-xs text-[#64748B] mt-0.5">{{ $messages->total() }} total messages</p>
            </div>
        </div>
    </div>

    {{-- List --}}
    <div class="divide-y divide-[#E2E8F0]">
        @forelse($messages as $message)
            @php
                $style = $statusStyles[$message->status] ?? $statusStyles['New'];
            @endphp

            <div class="px-6 py-5 hover:bg-[#F8FAFC] transition-colors">
                <div class="flex items-start justify-between gap-4">

                    {{-- Left: sender + subject + message --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#10B981] to-[#22D3EE] text-white flex items-center justify-center text-xs font-semibold shrink-0">
                                {{ strtoupper(substr($message->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-[#0B1720] text-sm truncate">{{ $message->name }}</p>
                                <p class="text-xs text-[#94A3B8] truncate">{{ $message->email }}</p>
                            </div>
                        </div>

                        <p class="font-semibold text-[#0B1720] text-sm mt-3">{{ $message->subject }}</p>
                        <p class="text-sm text-[#64748B] mt-1 line-clamp-2">{{ $message->message }}</p>

                        <p class="text-xs text-[#94A3B8] mt-2">{{ $message->created_at->diffForHumans() }}</p>
                    </div>

                    {{-- Right: status badge + update form --}}
                    <div class="flex flex-col items-end gap-2.5 shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $style['bg'] }} {{ $style['text'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }}"></span>
                            {{ $message->status }}
                        </span>

                        <form action="{{ route('admin.messages.updateStatus', $message->id) }}" method="POST" class="flex items-center gap-1.5">
                            @csrf
                            <select name="status"
                                    onchange="this.form.submit()"
                                    class="text-xs rounded-lg border border-[#E2E8F0] bg-white text-[#334155] px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#10B981]/30 focus:border-[#10B981] cursor-pointer">
                                @foreach(['New', 'Read', 'Resolved'] as $option)
                                    <option value="{{ $option }}" @selected($message->status === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                </div>
            </div>

        @empty
            <div class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#94A3B8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 5h16v11H8l-4 4V5Z"/>
                            <path d="M8 9h8"/>
                            <path d="M8 12h5"/>
                        </svg>
                    </div>
                    <p class="text-sm text-[#64748B]">No messages yet.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($messages->hasPages())
        <div class="px-6 py-4 border-t border-[#E2E8F0]">
            {{ $messages->links() }}
        </div>
    @endif

</div>

@endsection