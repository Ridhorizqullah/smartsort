<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'SmartSort - Digital Waste Bank')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>♻️</text></svg>">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#006b2c",
                        "on-primary": "#ffffff",
                        "primary-container": "#00873a",
                        "on-primary-container": "#f7fff2",
                        "secondary": "#785a00",
                        "on-secondary": "#ffffff",
                        "secondary-container": "#fdc425",
                        "on-secondary-container": "#6d5200",
                        "tertiary": "#545c72",
                        "on-tertiary": "#ffffff",
                        "background": "#f7f9fb",
                        "on-background": "#191c1e",
                        "surface": "#f7f9fb",
                        "on-surface": "#191c1e",
                        "surface-variant": "#e0e3e5",
                        "on-surface-variant": "#3e4a3d",
                        "outline": "#6e7b6c",
                        "outline-variant": "#bdcaba",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f2f4f6",
                        "surface-container": "#eceef0",
                        "surface-container-high": "#e6e8ea",
                        "surface-container-highest": "#e0e3e5",
                        "error": "#ba1a1a",
                        "on-error": "#ffffff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "container-margin": "24px",
                        "stack-sm": "8px",
                        "stack-md": "16px",
                        "stack-lg": "32px",
                        "gutter": "16px",
                        "section-gap": "64px",
                        "base": "8px"
                    },
                    "fontFamily": {
                        "display-lg": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "label-sm": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "body-lg": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "fontWeight": "500" }],
                        "label-md": ["14px", { "lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600" }],
                        "headline-lg-mobile": ["28px", { "lineHeight": "36px", "fontWeight": "700" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }]
                    }
                }
            }
        }
    </script>
    <style>
        .premium-gradient {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 50%, #785a00 100%);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        
        /* Utility for badging */
        .badge-pending { background-color: #fef08a; color: #854d0e; } /* Kuning */
        .badge-approved { background-color: #bfdbfe; color: #1e40af; } /* Biru */
        .badge-ready { background-color: #bbf7d0; color: #166534; } /* Hijau */
        .badge-completed { background-color: #e5e7eb; color: #374151; } /* Abu */
        .badge-rejected { background-color: #fecaca; color: #991b1b; } /* Merah */
    </style>
</head>
<body class="bg-background font-body-md text-on-surface min-h-[1109px] w-[1280px] mx-auto antialiased flex flex-col overflow-x-hidden" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <!-- TopAppBar -->
    <header class="bg-surface/60 backdrop-blur-xl border-b border-white/25 sticky top-0 z-50 w-full shadow-sm">
        <div class="flex justify-between items-center px-container-margin py-4 w-full max-w-7xl mx-auto">
            <div class="text-headline-md font-headline-lg text-primary tracking-tight">
                SmartSort
            </div>
            
            <nav class="hidden md:flex items-center gap-stack-lg">
                <a href="{{ route('warga.dashboard') }}" class="{{ request()->routeIs('warga.dashboard') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }} font-label-md">Beranda</a>
                <a href="{{ route('warga.katalog') }}" class="{{ request()->routeIs('warga.katalog') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }} font-label-md">Katalog</a>
                <a href="{{ route('warga.transaksi') }}" class="{{ request()->routeIs('warga.transaksi') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }} font-label-md">Riwayat Setor</a>
                <a href="{{ route('warga.redemption') }}" class="{{ request()->routeIs('warga.redemption') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }} font-label-md">Penukaran</a>
            </nav>
            
            <div class="flex items-center gap-4">
                <button class="text-on-surface-variant hover:text-primary transition-colors p-2 rounded-full">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="bg-primary text-on-primary px-4 py-2 rounded-lg font-label-md">
                    {{ number_format(auth()->user()->saldo_poin ?? 0, 0, ',', '.') }} Poin
                </div>
                <!-- Simple Logout Form -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-on-surface-variant hover:text-error transition-colors p-2 rounded-full">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-1 w-full max-w-7xl mx-auto px-container-margin pt-stack-lg pb-section-gap" style="min-height: 800px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-low border-t border-outline-variant/30 mt-auto">
        <div class="flex flex-col md:flex-row justify-between items-center gap-stack-md px-container-margin py-stack-lg w-full max-w-7xl mx-auto">
            <div class="text-headline-md font-headline-md text-primary">
                SmartSort
            </div>
            <div class="flex flex-wrap justify-center gap-6">
                <a class="text-on-surface-variant hover:text-primary transition-colors text-label-sm" href="#">Kebijakan Privasi</a>
                <a class="text-on-surface-variant hover:text-primary transition-colors text-label-sm" href="#">Syarat &amp; Ketentuan</a>
            </div>
            <div class="text-on-surface-variant text-label-sm text-center md:text-right">
                © 2024 SmartSort Village Digital Waste Bank. In partnership with BUMDes.
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
