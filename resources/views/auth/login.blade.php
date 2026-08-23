@extends('layouts.auth')
@section('title', 'Login - EcoLocate')
@section('content')

<div class="mb-8 lg:hidden flex items-center gap-2">
    <span class="w-8 h-8 rounded-full bg-[#10B981]/10 flex items-center justify-center text-[#10B981]">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/>
            <path d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12"/>
            <path d="m14 16-3 3 3 3"/>
            <path d="M8.293 13.596 7.196 9.5 3.1 10.598"/>
            <path d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 12 3a1.784 1.784 0 0 1 1.545.888l3.943 6.843"/>
            <path d="m13.378 9.633 4.096 1.098 1.097-4.096"/>
        </svg>
    </span>
    <span class="font-display font-bold text-lg">Eco<span class="text-[#10B981]">Locate</span></span>
</div>

<h2 class="font-display font-extrabold text-2xl text-[#0B1720] mb-1">Welcome back</h2>
<p class="text-[#64748B] text-sm mb-6">Log in to continue recycling responsibly.</p>

@if ($errors->any())
<div class="mb-5 text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg px-4 py-3">
    {{ $errors->first() }}
</div>
@endif
@if (session('status'))
<div class="mb-5 text-sm text-[#10B981] bg-[#10B981]/10 border border-[#10B981]/20 rounded-lg px-4 py-3">
    {{ session('status') }}
</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-[#0B1720] mb-1">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
            class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
            placeholder="you@example.com">
    </div>

    <div>
        <div class="flex items-center justify-between mb-1">
            <label class="block text-sm font-medium text-[#0B1720]">Password</label>
            <a href="{{ route('password.request') }}" class="text-xs text-[#10B981] hover:underline">Forgot password?</a>
        </div>
        <input type="password" name="password" required
            class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
            placeholder="********">
    </div>

    <label class="flex items-center gap-2 text-sm text-[#64748B]">
        <input type="checkbox" name="remember" class="rounded border-[#E2E8F0] text-[#10B981] focus:ring-[#10B981]">
        Remember me
    </label>

    <button type="submit"
        class="w-full bg-gradient-to-r from-[#10B981] to-[#34D399] text-white font-semibold text-sm py-3 rounded-lg shadow-md shadow-[#10B981]/25 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all mt-2">
        Log In
    </button>
</form>

<p class="text-center text-sm text-[#64748B] mt-6">
    New to EcoLocate?
    <a href="{{ route('register') }}" class="text-[#10B981] font-semibold hover:underline">Create an account</a>
</p>
@endsection