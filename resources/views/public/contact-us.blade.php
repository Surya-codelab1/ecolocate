@extends('layouts.public')

@section('title', 'Contact Us - EcoLocate')

@section('content')
<div class="max-w-3xl mx-auto px-3 sm:px-4 py-8 sm:py-14">

    <div class="text-center max-w-xl mx-auto mb-8 sm:mb-12">
        <span class="inline-block text-xs font-semibold tracking-wide uppercase text-[#10B981] bg-[#10B981]/10 px-3 py-1 rounded-full mb-3">Get in Touch</span>
        <h1 class="text-2xl sm:text-3xl font-bold text-[#0B1720] mb-2">We'd love to hear from you</h1>
        <p class="text-sm sm:text-base text-[#64748B]">Questions about a facility, a pickup request, or partnering with EcoLocate? Send us a message and our team will respond soon.</p>
    </div>

    {{-- Contact form --}}
    <div class="glass rounded-2xl p-4 sm:p-8 shadow-sm">

        @if (session('success'))
            <div class="mb-5 text-sm text-[#10B981] bg-[#10B981]/10 border border-[#10B981]/20 rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg px-4 py-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#0B1720] mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
                        placeholder="Your name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#0B1720] mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
                        placeholder="you@example.com">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#0B1720] mb-1">Phone <span class="text-[#94A3B8] font-normal">(optional)</span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                        class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
                        placeholder="+91 98765 43210">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#0B1720] mb-1">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required
                        class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
                        placeholder="How can we help?">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#0B1720] mb-1">Message</label>
                <textarea name="message" rows="5" required
                    class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] resize-none"
                    placeholder="Tell us more...">{{ old('message') }}</textarea>
            </div>

            <button type="submit"
                class="w-full sm:w-auto bg-gradient-to-r from-[#10B981] to-[#34D399] text-white font-semibold text-sm px-8 py-3 rounded-lg shadow-md shadow-[#10B981]/25 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all">
                Send Message
            </button>
        </form>
    </div>
</div>
@endsection