<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | EcoLocate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .sidebar-panel {
            background: linear-gradient(160deg, #052e2b 0%, #0b3d3a 45%, #0f5048 100%);
        }

        .app-bg {
            background: linear-gradient(135deg, #ecfdf5 0%, #f0fdfa 45%, #e0f2fe 100%);
        }

        .sidebar-collapsed { width: 4.5rem !important; }
        .sidebar-collapsed .sidebar-label { display: none; }
        .sidebar-collapsed .sidebar-logo-text { display: none; }
    </style>

    @stack('styles')
</head>
<body class="app-bg min-h-screen">

    <div class="flex min-h-screen">
        @include('admin.layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0">
            @include('admin.layouts.topbar')

            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="glass mb-4 text-[#0B1720] px-4 py-3 rounded-xl text-sm shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('assets/admin/js/counter.js') }}"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            sidebar.classList.toggle('sidebar-collapsed');
            localStorage.setItem('admin_sidebar_collapsed', sidebar.classList.contains('sidebar-collapsed'));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const collapsed = localStorage.getItem('admin_sidebar_collapsed') === 'true';
            if (collapsed) {
                document.getElementById('adminSidebar').classList.add('sidebar-collapsed');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
