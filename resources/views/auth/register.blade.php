@extends('layouts.auth')
@section('title', 'Register - EcoLocate')
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

<h2 class="font-display font-extrabold text-2xl text-[#0B1720] mb-1">Create your account</h2>
<p class="text-[#64748B] text-sm mb-6">Join EcoLocate to recycle responsibly and earn rewards.</p>

@if ($errors->any())
<div class="mb-5 text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg px-4 py-3">
    <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Role Toggle -->
<div class="relative flex bg-slate-100 rounded-full p-1 mb-6 text-sm font-semibold">
    <span id="roleIndicator" class="absolute top-1 bottom-1 left-1 w-[calc(50%-4px)] bg-white rounded-full shadow-sm transition-transform duration-300"></span>
    <button type="button" data-role="user" class="role-btn relative z-10 flex-1 py-2 rounded-full text-[#0B1720] transition-colors">I'm a User</button>
    <button type="button" data-role="facility" class="role-btn relative z-10 flex-1 py-2 rounded-full text-[#64748B] transition-colors">I'm a Recycle Facility Partner</button>
</div>

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="role" id="roleInput" value="user">

    <div>
        <label class="block text-sm font-medium text-[#0B1720] mb-1" id="nameLabel">E Waste Recycle Facility Center Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required
            class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
            placeholder="e.g. Eco Recycle Center ">
    </div>

    <div>
        <label class="block text-sm font-medium text-[#0B1720] mb-1">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required
            class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
            placeholder="name@example.com">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-[#0B1720] mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
                placeholder="********">
        </div>
        <div>
            <label class="block text-sm font-medium text-[#0B1720] mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full rounded-lg border border-[#E2E8F0] px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981]"
                placeholder="********">
        </div>
    </div>

    <button type="submit"
        class="w-full bg-gradient-to-r from-[#10B981] to-[#34D399] text-white font-semibold text-sm py-3 rounded-lg shadow-md shadow-[#10B981]/25 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all mt-2">
        Create Account
    </button>
</form>

<p class="text-center text-sm text-[#64748B] mt-6">
    Already have an account?
    <a href="{{ route('login') }}" class="text-[#10B981] font-semibold hover:underline">Log in</a>
</p>

<script>
    const buttons = document.querySelectorAll('.role-btn');
    const indicator = document.getElementById('roleIndicator');
    const roleInput = document.getElementById('roleInput');
    const nameLabel = document.getElementById('nameLabel');

    buttons.forEach((btn, i) => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('text-[#0B1720]'));
            buttons.forEach(b => b.classList.add('text-[#64748B]'));
            btn.classList.remove('text-[#64748B]');
            btn.classList.add('text-[#0B1720]');
            indicator.style.transform = i === 0 ? 'translateX(0)' : 'translateX(100%)';
            roleInput.value = btn.dataset.role;
            nameLabel.textContent = btn.dataset.role === 'facility' ? 'Facility Name' : 'Full Name';
        });
    });
</script>
@endsection