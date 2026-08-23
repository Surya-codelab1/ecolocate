<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EcoLocate')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }

        @keyframes flow { 0% { stroke-dashoffset: 240; opacity:.25; } 50% { opacity: 1; } 100% { stroke-dashoffset: 0; opacity:.25; } }
        .flow-line { stroke-dasharray: 8 6; animation: flow 3.2s linear infinite; }

        @keyframes pulse-ring { 0% { transform: scale(0.9); opacity:.8; } 70% { transform: scale(1.5); opacity:0; } 100% { transform: scale(0.9); opacity:0; } }
        .pulse-ring { animation: pulse-ring 2.2s ease-out infinite; }

        @keyframes float { 0%,100% { transform: translateY(0px); } 50% { transform: translateY(-8px); } }
        .float-icon { animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body class="font-body bg-[#F7FAF8] text-[#0B1720] antialiased">
    <div class="min-h-screen flex">

        <!-- Left Brand Panel -->
        <div class="hidden lg:flex lg:w-[42%] relative overflow-hidden flex-col justify-between p-10 xl:p-12 bg-gradient-to-br from-[#0B1720] to-[#0F2A38]">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#10B981]/25 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-72 h-72 bg-[#22D3EE]/15 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex items-center gap-2">
                <span class="relative w-9 h-9 flex items-center justify-center shrink-0">
                    <span class="absolute inset-0 rounded-full border-2 border-[#10B981] pulse-ring"></span>
                    <svg class="w-5 h-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/>
                        <path d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12"/>
                        <path d="m14 16-3 3 3 3"/>
                        <path d="M8.293 13.596 7.196 9.5 3.1 10.598"/>
                        <path d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 12 3a1.784 1.784 0 0 1 1.545.888l3.943 6.843"/>
                        <path d="m13.378 9.633 4.096 1.098 1.097-4.096"/>
                    </svg>
                </span>
                <span class="font-display font-bold text-xl text-white">Eco<span class="text-[#10B981]">  Locate</span></span>
            </div>

            <div class="relative z-10">
                <h1 class="font-display font-extrabold text-3xl xl:text-4xl text-white leading-tight mb-4">
                    Give your e-waste<br/>a better destination.
                </h1>
                <p class="text-slate-300 text-sm max-w-sm mb-10">
                    Locate nearby recycling facilities, understand what's inside your devices, and earn EcoCredits for responsible recycling.
                </p>

                <svg viewBox="0 0 320 120" class="w-full max-w-sm">
                    <g class="float-icon">
                        <circle cx="30" cy="30" r="18" fill="#0F2430" stroke="#34D399" stroke-width="1.5"/>
                        <svg x="18" y="18" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#34D399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/>
                            <path d="M12 18h.01"/>
                        </svg>
                    </g>
                    <g class="float-icon" style="animation-delay:.6s">
                        <circle cx="30" cy="90" r="18" fill="#0F2430" stroke="#22D3EE" stroke-width="1.5"/>
                        <svg x="18" y="78" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22D3EE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="16" height="10" x="2" y="7" rx="2" ry="2"/>
                            <line x1="22" x2="22" y1="11" y2="13"/>
                        </svg>
                    </g>
                    <path d="M48 30 C 140 30, 140 60, 218 60" fill="none" stroke="#10B981" stroke-width="2" class="flow-line"/>
                    <path d="M48 90 C 140 90, 140 60, 218 60" fill="none" stroke="#10B981" stroke-width="2" class="flow-line"/>
                    <circle cx="232" cy="60" r="22" fill="#10B981"/>
                    <svg x="220" y="48" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0B1720" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/>
                        <path d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12"/>
                        <path d="m14 16-3 3 3 3"/>
                        <path d="M8.293 13.596 7.196 9.5 3.1 10.598"/>
                        <path d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 12 3a1.784 1.784 0 0 1 1.545.888l3.943 6.843"/>
                        <path d="m13.378 9.633 4.096 1.098 1.097-4.096"/>
                    </svg>
                </svg>
                <p class="text-[#34D399] text-xs font-semibold tracking-wide uppercase mt-4">Find &middot; Recycle &middot; Earn</p>
            </div>

            <p class="relative z-10 text-slate-500 text-xs">&copy; {{ date('Y') }} EcoLocate. All rights reserved.</p>
        </div>

        <!-- Right Form Panel -->
        <div class="flex-1 flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>