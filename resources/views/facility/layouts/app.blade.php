<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Facility Panel') | EcoLocate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }

        .sidebar-panel {
            background: linear-gradient(160deg, #052e2b 0%, #0b3d3a 45%, #0f5048 100%);
        }

        .app-bg {
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdfa 45%, #e0f2fe 100%);
        }

        /* Mobile: sidebar becomes an off-canvas drawer */
        @media (max-width: 1023px) {
            #facilitySidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            #facilitySidebar.sidebar-open {
                transform: translateX(0);
            }
            #sidebarOverlay {
                display: none;
            }
            #sidebarOverlay.overlay-visible {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="app-bg min-h-screen">

    {{-- Overlay for mobile when sidebar is open --}}
    <div id="sidebarOverlay" onclick="closeSidebar()" class="fixed inset-0 bg-black/40 z-40 lg:hidden"></div>

    <div class="flex min-h-screen">
        @include('facility.layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0 w-full">
            @include('facility.layouts.topbar')

            <main class="flex-1 p-4 sm:p-6">
                @if(session('success'))
                    <div class="mb-4 bg-[#10B981]/10 border border-[#10B981]/30 text-[#0B1720] px-4 py-3 rounded-xl text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('facilitySidebar').classList.add('sidebar-open');
            document.getElementById('sidebarOverlay').classList.add('overlay-visible');
        }
        function closeSidebar() {
            document.getElementById('facilitySidebar').classList.remove('sidebar-open');
            document.getElementById('sidebarOverlay').classList.remove('overlay-visible');
        }
    </script>

    @stack('scripts')
</body>
</html>
