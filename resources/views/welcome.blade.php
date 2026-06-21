<!DOCTYPE html><html lang="id" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>SmartSort - Digital Waste Bank</title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>♻️</text></svg>">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">

<style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased selection:bg-primary-container selection:text-on-primary-container">
<!-- Navigation (Shared Component) -->
<nav class="bg-surface/60 dark:bg-surface-dim/60 backdrop-blur-xl docked full-width top-0 sticky z-50 border-b border-white/25 dark:border-white/10 shadow-sm">
<div class="flex justify-between items-center px-container-margin py-4 w-full max-w-7xl mx-auto">
<div class="flex items-center gap-2">
<span class="text-headline-md font-headline-lg text-primary font-bold tracking-tight text-2xl">SmartSort</span>
</div>
<!-- Desktop Nav Links -->
<ul class="hidden md:flex items-center gap-stack-md text-label-md font-label-md">
<li class=""><a class="text-primary dark:text-primary-fixed-dim border-b-2 border-primary dark:border-primary-fixed-dim pb-1 hover:opacity-90 duration-200" href="#beranda">Beranda</a></li>
<li class=""><a class="text-gray-800 hover:text-primary transition-colors hover:opacity-90 duration-200" href="#tentang-kami">Tentang Kami</a></li>
<li class=""><a class="text-gray-800 hover:text-primary transition-colors hover:opacity-90 duration-200" href="#program-kerja">Program Kerja</a></li>
<li class=""><a class="text-gray-800 hover:text-primary transition-colors hover:opacity-90 duration-200" href="#harga-sampah">Harga Sampah</a></li>
<li class=""><a class="text-gray-800 hover:text-primary transition-colors hover:opacity-90 duration-200" href="#kontak">Kontak</a></li>
</ul>
<div class="hidden md:flex items-center gap-stack-sm">
@auth
    @if(Auth::user()->role === 'warga')
        <a href="{{ route('warga.dashboard') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg text-label-md font-label-md hover:bg-primary-container transition-colors shadow-sm">Dashboard Warga</a>
    @else
        <a href="{{ route('admin.dashboard') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg text-label-md font-label-md hover:bg-primary-container transition-colors shadow-sm">Dashboard Admin</a>
    @endif
@else
    <a href="{{ route('login') }}" class="text-gray-800 hover:text-primary transition-colors text-label-md font-label-md px-4 py-2 font-semibold">Login</a>
    <a href="{{ route('register') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg text-label-md font-label-md hover:bg-primary-container transition-colors shadow-sm">Register</a>
@endauth
</div>
</div>
</nav>
<main>
<!-- 1. Hero Section (Beranda) -->
<section class="relative pt-section-gap pb-section-gap px-container-margin overflow-hidden min-h-[85vh] flex items-center justify-center" id="beranda">
    <!-- Background Slider Container -->
    <div class="absolute inset-0 -z-20 bg-black">
        <div id="bg-slider" class="relative w-full h-full">
            <!-- Global Overlay -->
            <div class="absolute inset-0 bg-black/50 z-10 pointer-events-none"></div>
            
            <!-- Slide 1 -->
            <div class="slide-item absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-100">
                <img src="{{ asset('storage/images/IMG_1067.webp') }}" class="w-full h-full object-cover object-center" alt="Background 1">
            </div>
            <!-- Slide 2 -->
            <div class="slide-item absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0">
                <img src="{{ asset('storage/images/IMG_1069.webp') }}" class="w-full h-full object-cover object-center" alt="Background 2">
            </div>
            <!-- Slide 3 -->
            <div class="slide-item absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0">
                <img src="{{ asset('storage/images/IMG_7733.webp') }}" class="w-full h-full object-cover object-center" alt="Background 3">
            </div>
        </div>
    </div>

    <!-- Hero Content -->
    <div class="max-w-5xl mx-auto w-full relative z-20 mt-12 md:mt-0">
        <!-- Text Content enclosed in Light Glass Card -->
        <div class="bg-white/80 backdrop-blur-md p-8 md:p-14 rounded-3xl flex flex-col items-center text-center shadow-2xl border border-white/50">
            <div class="z-20 w-full text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold font-['Plus_Jakarta_Sans'] text-on-surface mb-6 leading-tight tracking-tight">
                    Ubah Sampah Menjadi <span class="text-primary">Sembako</span>
                </h1>
                <p class="text-lg md:text-xl font-['Plus_Jakarta_Sans'] text-on-surface-variant max-w-3xl mx-auto font-medium leading-relaxed mb-8">
                    Sistem digitalisasi pengelolaan sampah desa terintegrasi. Setor sampah, kumpulkan poin, dan tukarkan dengan sembako berkualitas setiap akhir pekan.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row justify-center gap-4 z-20 w-full">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-primary text-white px-8 py-4 rounded-xl text-base font-bold font-['Plus_Jakarta_Sans'] hover:bg-primary-container hover:scale-105 transition-all duration-300 shadow-md inline-block">
                    Mulai Menabung
                </a>
                <a href="#tentang" class="w-full sm:w-auto bg-transparent text-primary border-2 border-primary px-8 py-4 rounded-xl text-base font-bold font-['Plus_Jakarta_Sans'] hover:bg-primary/5 hover:scale-105 transition-all duration-300 inline-block">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.slide-item');
        if(slides.length > 0) {
            let currentSlide = 0;
            const totalSlides = slides.length;
            setInterval(() => {
                // Sembunyikan slide saat ini
                slides[currentSlide].classList.remove('opacity-100');
                slides[currentSlide].classList.add('opacity-0');
                
                // Lanjut ke slide berikutnya
                currentSlide = (currentSlide + 1) % totalSlides;
                
                // Tampilkan slide berikutnya
                slides[currentSlide].classList.remove('opacity-0');
                slides[currentSlide].classList.add('opacity-100');
            }, 5000); // Ganti slide setiap 5 detik
        }
    });
</script>
<!-- 2. About Us (Tentang Kami) -->
<section class="py-section-gap px-container-margin bg-surface-container-lowest" id="tentang-kami">
<div class="max-w-7xl mx-auto">
<div class="text-center max-w-3xl mx-auto mb-section-gap">
<h2 class="text-headline-lg font-headline-lg text-on-surface mb-stack-md">Tentang SmartSort</h2>
<p class="text-body-lg font-body-lg text-on-surface-variant">SmartSort adalah program inovatif koperasi BUMDes yang mengintegrasikan kepedulian lingkungan dengan kesejahteraan ekonomi warga desa.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-stack-lg">
<!-- Value 1 -->
<div class="glass p-stack-lg rounded-lg hover:shadow-[0_8px_32px_0_rgba(22,163,74,0.1)] transition-shadow duration-300">
<div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center mb-stack-md">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: &quot;FILL&quot; 1;">eco</span>
</div>
<h3 class="text-headline-md font-headline-md text-on-surface mb-stack-sm">Desa Bersih</h3>
<p class="text-body-md font-body-md text-on-surface-variant">Mengurangi tumpukan sampah liar dan menciptakan lingkungan desa yang asri, sehat, dan nyaman untuk ditinggali.</p>
</div>
<!-- Value 2 -->
<div class="glass p-stack-lg rounded-lg hover:shadow-[0_8px_32px_0_rgba(234,179,8,0.15)] transition-shadow duration-300">
<div class="w-12 h-12 rounded-full bg-secondary-container/20 flex items-center justify-center mb-stack-md">
<span class="material-symbols-outlined text-secondary" style="font-variation-settings: &quot;FILL&quot; 1;">monetization_on</span>
</div>
<h3 class="text-headline-md font-headline-md text-on-surface mb-stack-sm">Warga Sejahtera</h3>
<p class="text-body-md font-body-md text-on-surface-variant">Mengubah barang sisa menjadi nilai ekonomis yang dapat ditukarkan dengan kebutuhan pokok sehari-hari.</p>
</div>
<!-- Value 3 -->
<div class="glass p-stack-lg rounded-lg hover:shadow-[0_8px_32px_0_rgba(22,163,74,0.1)] transition-shadow duration-300">
<div class="w-12 h-12 rounded-full bg-tertiary-fixed/50 flex items-center justify-center mb-stack-md">
<span class="material-symbols-outlined text-tertiary" style="font-variation-settings: &quot;FILL&quot; 1;">health_and_safety</span>
</div>
<h3 class="text-headline-md font-headline-md text-on-surface mb-stack-sm">Keuangan Transparan</h3>
<p class="text-body-md font-body-md text-on-surface-variant">Sistem pencatatan digital memastikan setiap gram sampah dan poin yang ditukar tercatat dengan akurat dan aman.</p>
</div>
</div>
</div>
</section>
<!-- 3. Work Program (Program Kerja) -->
<section class="py-16 md:py-24 px-container-margin relative bg-surface" id="program-kerja">
    <div class="absolute inset-0 bg-surface-container-lowest/50 -z-10"></div>
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16 md:mb-24">
            <h2 class="text-4xl md:text-5xl font-bold text-primary mb-6 font-['Plus_Jakarta_Sans']">Alur Program Kerja</h2>
            <p class="text-lg text-on-surface-variant">Proses transparan dan efisien untuk mengubah sampah rumah tangga menjadi aset berharga yang mendukung kesejahteraan komunitas secara berkelanjutan.</p>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <!-- Vertical Line -->
            <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-[2px] bg-primary/20 -translate-x-1/2 rounded-full"></div>

            <div class="flex flex-col gap-8 md:gap-16">
                
                <!-- Step 1 (Left) -->
                <div class="flex flex-col md:flex-row items-center justify-between w-full relative">
                    <!-- Mobile Number & Icon -->
                    <div class="md:hidden flex items-center mb-4 self-start">
                        <div class="w-10 h-10 rounded-full border-4 border-primary bg-surface-container-lowest z-10 flex items-center justify-center shadow-sm"></div>
                        <span class="ml-4 text-3xl font-bold text-primary/40">01</span>
                    </div>
                    <!-- Card -->
                    <div class="w-full md:w-5/12 flex justify-end">
                        <div class="bg-surface-container-lowest rounded-2xl p-8 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-outline/50 w-full md:text-right transition-transform hover:-translate-y-1">
                            <h3 class="text-2xl font-bold text-primary mb-3">Pilah Sampah</h3>
                            <p class="text-base text-on-surface-variant leading-relaxed">Warga secara mandiri memilah sampah organik dan anorganik dari rumah masing-masing, memastikan material yang dapat didaur ulang tetap bersih.</p>
                        </div>
                    </div>
                    <!-- Center Graphic Desktop -->
                    <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center justify-center">
                        <div class="w-12 h-12 rounded-full border-[3px] border-primary bg-surface-container-lowest z-10 flex items-center justify-center shadow-sm"></div>
                        <span class="absolute left-20 text-5xl font-bold text-primary/40">01</span>
                    </div>
                    <!-- Empty Right -->
                    <div class="hidden md:block w-5/12"></div>
                </div>

                <!-- Step 2 (Right) -->
                <div class="flex flex-col md:flex-row items-center justify-between w-full relative">
                    <!-- Mobile Number & Icon -->
                    <div class="md:hidden flex items-center mb-4 self-start">
                        <div class="w-10 h-10 rounded-full bg-primary text-white z-10 flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">hourglass_top</span>
                        </div>
                        <span class="ml-4 text-3xl font-bold text-primary/40">02</span>
                    </div>
                    <!-- Empty Left -->
                    <div class="hidden md:block w-5/12 text-right"></div>
                    <!-- Center Graphic Desktop -->
                    <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center justify-center">
                        <span class="absolute right-20 text-5xl font-bold text-primary/40">02</span>
                        <div class="w-12 h-12 rounded-full bg-primary text-white z-10 flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined" style="font-size: 20px; font-variation-settings: 'FILL' 1;">hourglass_top</span>
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="w-full md:w-5/12 flex justify-start">
                        <div class="bg-surface-container-lowest rounded-2xl p-8 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-outline/50 w-full text-left transition-transform hover:-translate-y-1">
                            <h3 class="text-2xl font-bold text-primary mb-3">Timbang &amp; Setor</h3>
                            <p class="text-base text-on-surface-variant leading-relaxed">Sampah yang telah dipilah diserahkan ke petugas timbangan di Balai Desa. Sistem digital kami akan mencatat berat dan jenis sampah secara akurat.</p>
                        </div>
                    </div>
                </div>

                <!-- Step 3 (Left) -->
                <div class="flex flex-col md:flex-row items-center justify-between w-full relative">
                    <!-- Mobile Number & Icon -->
                    <div class="md:hidden flex items-center mb-4 self-start">
                        <div class="w-10 h-10 rounded-full border-4 border-amber-500 bg-surface-container-lowest text-amber-500 z-10 flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">account_balance_wallet</span>
                        </div>
                        <span class="ml-4 text-3xl font-bold text-primary/40">03</span>
                    </div>
                    <!-- Card -->
                    <div class="w-full md:w-5/12 flex justify-end">
                        <div class="bg-surface-container-lowest rounded-2xl p-8 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-outline/50 w-full md:text-right transition-transform hover:-translate-y-1">
                            <h3 class="text-2xl font-bold text-primary mb-3">Dapatkan Poin</h3>
                            <p class="text-base text-on-surface-variant leading-relaxed">Nilai sampah yang disetorkan langsung dikonversi menjadi poin digital dan secara otomatis masuk ke saldo kartu/akun warga secara real-time.</p>
                        </div>
                    </div>
                    <!-- Center Graphic Desktop -->
                    <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center justify-center">
                        <div class="w-12 h-12 rounded-full border-[3px] border-amber-500 bg-surface-container-lowest text-amber-500 z-10 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined" style="font-size: 20px; font-variation-settings: 'FILL' 1;">account_balance_wallet</span>
                        </div>
                        <span class="absolute left-20 text-5xl font-bold text-primary/40">03</span>
                    </div>
                    <!-- Empty Right -->
                    <div class="hidden md:block w-5/12"></div>
                </div>

                <!-- Step 4 (Right) -->
                <div class="flex flex-col md:flex-row items-center justify-between w-full relative">
                    <!-- Mobile Number & Icon -->
                    <div class="md:hidden flex items-center mb-4 self-start">
                        <div class="w-10 h-10 rounded-full bg-amber-500 text-white z-10 flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">storefront</span>
                        </div>
                        <span class="ml-4 text-3xl font-bold text-primary/40">04</span>
                    </div>
                    <!-- Empty Left -->
                    <div class="hidden md:block w-5/12 text-right"></div>
                    <!-- Center Graphic Desktop -->
                    <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center justify-center">
                        <span class="absolute right-20 text-5xl font-bold text-primary/40">04</span>
                        <div class="w-12 h-12 rounded-full bg-amber-500 text-white z-10 flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined" style="font-size: 20px; font-variation-settings: 'FILL' 1;">storefront</span>
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="w-full md:w-5/12 flex justify-start">
                        <div class="bg-surface-container-lowest rounded-2xl p-8 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] border border-outline/50 w-full text-left transition-transform hover:-translate-y-1">
                            <h3 class="text-2xl font-bold text-primary mb-3">Tukar Sembako</h3>
                            <p class="text-base text-on-surface-variant leading-relaxed">Setiap hari Sabtu atau Minggu, poin yang terkumpul dapat ditukarkan dengan sembako berkualitas tinggi dan kebutuhan pokok lainnya yang disediakan oleh BUMDes.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- 4. Price Board (Harga Sampah Hari Ini) -->
<section class="py-section-gap px-container-margin bg-surface-container-lowest" id="harga-sampah">
<div class="max-w-4xl mx-auto">
<div class="flex flex-col md:flex-row justify-between items-center mb-stack-lg gap-stack-md">
<h2 class="text-headline-lg font-headline-lg text-on-surface">Harga Sampah Hari Ini</h2>
<span class="px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed text-label-sm font-label-sm">Diperbarui: Hari ini, 08:00 WIB</span>
</div>
<div class="glass rounded-lg overflow-hidden shadow-sm">
<!-- Tabs (Visual Only for MVP) -->
<div class="flex border-b border-surface-variant bg-surface-bright/50">
<button class="flex-1 py-3 text-label-md font-label-md text-primary border-b-2 border-primary">Semua Kategori</button>
</div>
<!-- Table -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50">
<th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider">Jenis Sampah</th>
<th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider">Kategori</th>
<th class="py-3 px-4 text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider text-right">Harga (Rp/kg)</th>
</tr>
</thead>
<tbody class="text-body-md font-body-md">
@forelse($categories as $category)
<tr class="border-b border-surface-variant/50 hover:bg-surface-bright/50 transition-colors">
<td class="py-4 px-4 text-on-surface">{{ $category->name }}</td>
<td class="py-4 px-4">
    <span class="px-2 py-1 rounded-full text-label-sm {{ strtolower($category->name) == 'kertas' || strtolower($category->name) == 'kardus' ? 'bg-primary-container/10 text-primary' : 'bg-tertiary-fixed/50 text-tertiary' }}">
        {{ $category->name }}
    </span>
</td>
<td class="py-4 px-4 text-right font-medium text-on-surface">{{ number_format($category->price_per_kg, 0, ',', '.') }}</td>
</tr>
@empty
<tr>
<td colspan="3" class="py-4 px-4 text-center text-on-surface-variant">Belum ada data harga sampah hari ini.</td>
</tr>
@endforelse
</tbody>
</table>
</div>
<div class="bg-surface-bright/80 p-4 text-label-sm font-label-sm text-on-surface-variant flex items-start gap-2 border-t border-surface-variant">
<span class="material-symbols-outlined text-[16px]">info</span>
<p class="">Catatan: Pastikan sampah sudah dipilah dan dibersihkan dari kotoran sebelum disetorkan untuk mendapatkan harga maksimal.</p>
</div>
</div>
</div>
</section>
<!-- 5. Contact (Kontak Kami) -->
<section class="py-16 md:py-24 px-container-margin relative overflow-hidden bg-surface-container-lowest/30" id="kontak">
    <!-- Decorative elements -->
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-primary-container/10 rounded-full blur-3xl -z-10"></div>
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-primary mb-4 font-['Plus_Jakarta_Sans']">Hubungi Kami</h2>
            <p class="text-lg text-on-surface-variant">Punya pertanyaan mengenai program bank sampah atau penukaran sembako?<br>Tim SmartSort siap membantu Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-stretch">
            
            <!-- Left Card: Kirim Pesan -->
            <div class="bg-surface-container-lowest rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-outline/10 flex flex-col h-full">
                <h3 class="text-2xl font-bold text-on-surface mb-8">Kirim Pesan</h3>
                
                <form class="flex flex-col gap-6 flex-grow">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-on-surface-variant mb-2" for="nama">Nama Lengkap</label>
                            <input class="w-full bg-surface-container-lowest text-on-surface border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none transition-shadow" id="nama" placeholder="Masukkan nama Anda" type="text">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-on-surface-variant mb-2" for="email">Alamat Email</label>
                            <input class="w-full bg-surface-container-lowest text-on-surface border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none transition-shadow" id="email" placeholder="contoh@email.com" type="email">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-on-surface-variant mb-2" for="rt-rw">RT / RW</label>
                        <input class="w-full bg-surface-container-lowest text-on-surface border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none transition-shadow" id="rt-rw" placeholder="Contoh: RT 01 / RW 03" type="text">
                    </div>
                    
                    <div class="flex-grow flex flex-col">
                        <label class="block text-sm font-bold text-on-surface-variant mb-2" for="pesan">Pesan</label>
                        <textarea class="w-full flex-grow bg-surface-container-lowest text-on-surface border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none transition-shadow resize-none min-h-[140px]" id="pesan" placeholder="Tuliskan pertanyaan atau pesan Anda di sini..."></textarea>
                    </div>
                    
                    <button class="mt-4 w-full bg-amber-500 text-white font-bold text-base py-4 rounded-xl hover:bg-amber-600 transition-colors shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2" type="button">
                        Kirim Pesan <span class="material-symbols-outlined text-[20px]">send</span>
                    </button>
                </form>
            </div>

            <!-- Right Card: Informasi Layanan -->
            <div class="bg-surface-container-lowest rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-outline/10 flex flex-col gap-8">
                <h3 class="text-2xl font-bold text-on-surface mb-2">Informasi Layanan</h3>
                
                <!-- Location -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary">location_on</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-on-surface mb-1">Lokasi Kami</h4>
                        <p class="text-base text-on-surface-variant">Desa Tuksono, Sentolo, Kulon Progo</p>
                    </div>
                </div>
                
                <!-- Operating Hours -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary">schedule</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-on-surface mb-1">Jam Operasional</h4>
                        <p class="text-base text-on-surface-variant">Buka: Senin - Jumat (08:00 - 15:00)</p>
                        <div class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1.5 rounded-md mt-3 border border-amber-200">
                            <span class="material-symbols-outlined text-[14px]">event</span>
                            Hari Penukaran Sembako: Sabtu &amp; Minggu
                        </div>
                    </div>
                </div>

                <!-- Socials & Program Info -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary">diversity_3</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-on-surface mb-1">Penyelenggara</h4>
                        <p class="text-sm text-on-surface-variant leading-relaxed">Program Pengabdian dan Pemberdayaan Masyarakat<br>Kemendikbud Ristek RI</p>
                        <div class="flex flex-wrap gap-4 mt-3">
                            <a href="https://www.instagram.com/ppkormawa.himfa?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw%3D%3D" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary-container transition-colors">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                @ppkormawa.himfa
                            </a>
                            <a href="https://tiktok.com/@ppko.himfa" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary-container transition-colors">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93v7.2c0 1.61-.31 3.22-1.14 4.61-1.21 2.04-3.4 3.44-5.78 3.65-2.43.21-4.94-.3-6.85-1.92-1.84-1.55-2.9-3.9-2.75-6.32.14-2.31 1.41-4.45 3.32-5.7 1.68-1.1 3.7-1.5 5.67-1.12.18.04.36.1.53.16v4.14c-1.3-.23-2.67-.18-3.89.28-1.27.48-2.29 1.57-2.66 2.9-.38 1.34-.14 2.84.66 3.95.83 1.17 2.27 1.78 3.7 1.68 1.41-.09 2.7-.82 3.49-1.94.8-1.12 1.12-2.52 1.1-3.9v-15.3z"/></svg>
                                @ppko.himfa
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Map Card -->
                <div class="relative w-full h-64 rounded-xl overflow-hidden mt-auto border border-outline/20 shadow-sm bg-surface-variant">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31617.529189573823!2d110.24627149999999!3d-7.875058!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7af95013f2a89f%3A0xd3b148a742a54501!2sTuksono%2C%20Sentolo%2C%20Kulon%20Progo%20Regency%2C%20Special%20Region%20of%20Yogyakarta!5e0!3m2!1sen!2sid!4v1782059056764!5m2!1sen!2sid" class="w-full h-full absolute inset-0" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            
        </div>
    </div>
</section>
</main>
<!-- Footer (Shared Component) -->
<footer class="bg-surface-container-low dark:bg-surface-container-highest full-width border-t border-outline-variant/30 flat no shadows">
<div class="flex flex-col md:flex-row justify-between items-center gap-stack-md px-container-margin py-stack-lg w-full max-w-7xl mx-auto">
<div class="flex items-center gap-2">
<span class="text-headline-md font-headline-lg text-primary font-bold tracking-tight text-2xl">SmartSort</span>
</div>
<p class="text-body-md font-body-md text-on-surface-variant dark:text-surface-variant text-center md:text-left">
                © 2024 SmartSort Village Digital Waste Bank. In partnership with BUMDes.
            </p>
<ul class="flex items-center gap-stack-md text-label-sm font-label-sm">
<li class=""><a class="text-on-surface-variant dark:text-surface-variant hover:text-primary dark:hover:text-primary-fixed-dim transition-colors" href="#">Kebijakan Privasi</a></li>
<li class=""><a class="text-on-surface-variant dark:text-surface-variant hover:text-primary dark:hover:text-primary-fixed-dim transition-colors" href="#">Syarat &amp; Ketentuan</a></li>
<li class=""><a class="text-on-surface-variant dark:text-surface-variant hover:text-primary dark:hover:text-primary-fixed-dim transition-colors" href="#">Peta Situs</a></li>
</ul>
</div>
</footer>


</body></html>

