<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - SmartSort')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>♻️</text></svg>">
    
    <!-- Google Fonts & Material Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-outline-variant/30 shadow-sm hidden md:flex flex-col">
        <div class="h-16 flex items-center px-6 border-b border-outline-variant/30">
            <span class="text-2xl font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[28px]" style="font-variation-settings: 'FILL' 1;">eco</span>
                SmartSort
            </span>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            
            <p class="px-4 pt-4 pb-2 text-xs font-bold text-on-surface-variant/60 uppercase tracking-wider">Layanan</p>
            
            <a href="{{ route('admin.transaksi') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.transaksi') ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }}">
                <span class="material-symbols-outlined">local_shipping</span>
                POS Transaksi
            </a>

            <a href="{{ route('admin.penukaran') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.penukaran') ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }}">
                <span class="material-symbols-outlined">fact_check</span>
                Approval Penukaran
            </a>

            <p class="px-4 pt-4 pb-2 text-xs font-bold text-on-surface-variant/60 uppercase tracking-wider">Master Data</p>
            
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.users') ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }}">
                <span class="material-symbols-outlined">group</span>
                Pengguna
            </a>

            <a href="{{ route('admin.kategori') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.kategori') ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }}">
                <span class="material-symbols-outlined">category</span>
                Kategori Sampah
            </a>

            <a href="{{ route('admin.reward') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.reward') ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }}">
                <span class="material-symbols-outlined">featured_seasonal_and_gifts</span>
                Daftar Reward
            </a>
        </nav>

        <div class="p-4 border-t border-outline-variant/30">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-error bg-red-50 hover:bg-red-100 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-error">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-background">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-outline-variant/30 shadow-sm flex items-center justify-between px-6 z-10">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-bold text-on-surface">@yield('header', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-on-surface">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-on-surface-variant capitalize">{{ Auth::user()->role ?? 'Administrator' }}</p>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if (session('success'))
        <div class="m-6 mb-0 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        @endif

        @if (session('error'))
        <div class="m-6 mb-0 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-red-600">error</span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
