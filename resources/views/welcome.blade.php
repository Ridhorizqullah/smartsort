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
<section class="relative pt-section-gap pb-section-gap px-container-margin overflow-hidden min-h-[80vh] flex items-center" id="beranda">
<!-- Decorative Background Element -->
<div class="absolute top-0 right-0 w-1/2 h-full bg-linear-to-bl from-primary-container/20 to-transparent -z-10 blur-3xl"></div>
<div class="max-w-7xl mx-auto w-full grid grid-cols-1 gap-section-gap items-center">
<!-- Text Content -->
<div class="flex flex-col gap-stack-lg z-10 items-center text-center max-w-3xl mx-auto">
<div>
<h1 class="text-display-lg font-display-lg text-on-surface mb-stack-md">Ubah Sampah Menjadi Sembako: <span class="text-primary">Mewujudkan Desa Bersih, Sehat, dan Makmur</span></h1>
<p class="text-body-lg font-body-lg text-on-surface-variant">Sistem digitalisasi pengelolaan sampah desa terintegrasi. Setor sampah, kumpulkan poin, dan tukarkan dengan sembako berkualitas setiap akhir pekan.</p>
</div>
<div class="flex gap-stack-md">
<button class="bg-primary text-on-primary px-6 py-3 rounded-lg text-label-md font-label-md hover:bg-primary-container transition-colors shadow-sm">Mulai Menabung</button>
<button class="bg-surface-container-lowest text-primary border border-primary px-6 py-3 rounded-lg text-label-md font-label-md hover:bg-surface-container-low transition-colors">Pelajari Lebih Lanjut</button>
</div>
</div>
<!-- Interaction Card (Glassmorphism) -->
</div>
</section>
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
<section class="py-section-gap px-container-margin relative" id="program-kerja">
<div class="absolute inset-0 bg-surface-container-low/50 -z-10"></div>
<div class="max-w-7xl mx-auto">
<h2 class="text-headline-lg font-headline-lg text-on-surface mb-section-gap text-center">Cara Kerja SmartSort</h2>
<div class="grid grid-cols-1 md:grid-cols-4 gap-stack-md relative">
<!-- Connection Line (Desktop) -->
<div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-surface-variant -translate-y-1/2 -z-10"></div>
<!-- Step 1 -->
<div class="flex flex-col items-center text-center relative z-10">
<div class="w-16 h-16 rounded-full bg-surface-container-lowest border-4 border-primary flex items-center justify-center mb-stack-md shadow-sm">
<span class="text-headline-md font-headline-md text-primary">1</span>
</div>
<h3 class="text-label-md font-label-md text-on-surface mb-stack-sm">Pilah Sampah</h3>
<p class="text-body-md font-body-md text-on-surface-variant">Warga memilah sampah dari rumah sesuai kategori.</p>
</div>
<!-- Step 2 -->
<div class="flex flex-col items-center text-center relative z-10">
<div class="w-16 h-16 rounded-full bg-surface-container-lowest border-4 border-outline-variant flex items-center justify-center mb-stack-md shadow-sm">
<span class="text-headline-md font-headline-md text-on-surface-variant">2</span>
</div>
<h3 class="text-label-md font-label-md text-on-surface mb-stack-sm">Timbang &amp; Setor</h3>
<p class="text-body-md font-body-md text-on-surface-variant">Diserahkan ke petugas timbangan di Balai Desa.</p>
</div>
<!-- Step 3 -->
<div class="flex flex-col items-center text-center relative z-10">
<div class="w-16 h-16 rounded-full bg-surface-container-lowest border-4 border-outline-variant flex items-center justify-center mb-stack-md shadow-sm">
<span class="text-headline-md font-headline-md text-on-surface-variant">3</span>
</div>
<h3 class="text-label-md font-label-md text-on-surface mb-stack-sm">Dapatkan Poin</h3>
<p class="text-body-md font-body-md text-on-surface-variant">Poin digital masuk ke kartu warga secara otomatis.</p>
</div>
<!-- Step 4 -->
<div class="flex flex-col items-center text-center relative z-10">
<div class="w-16 h-16 rounded-full bg-surface-container-lowest border-4 border-secondary flex items-center justify-center mb-stack-md shadow-sm">
<span class="text-headline-md font-headline-md text-secondary">4</span>
</div>
<h3 class="text-label-md font-label-md text-on-surface mb-stack-sm">Tukar Sembako</h3>
<p class="text-body-md font-body-md text-on-surface-variant">Setiap hari Sabtu/Minggu, poin ditukarkan sembako siap saji dari BUMDes.</p>
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
<section class="py-section-gap px-container-margin relative overflow-hidden" id="kontak">
<!-- Decorative elements -->
<div class="absolute -bottom-32 -left-32 w-96 h-96 bg-primary-container/10 rounded-full blur-3xl -z-10"></div>
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-section-gap">
<!-- Contact Info -->
<div>
<h2 class="text-headline-lg font-headline-lg text-on-surface mb-stack-sm">Hubungi Kami</h2>
<p class="text-body-md font-body-md text-on-surface-variant mb-stack-lg">Ada pertanyaan tentang pendaftaran atau jadwal penukaran sembako? Silakan kunjungi loket kami atau kirim pesan.</p>
<div class="flex flex-col gap-stack-md">
<div class="flex items-start gap-stack-md">
<div class="w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 shadow-sm border border-outline-variant/30">
<span class="material-symbols-outlined text-primary">location_on</span>
</div>
<div>
<h4 class="text-label-md font-label-md text-on-surface">Lokasi</h4>
<p class="text-body-md font-body-md text-on-surface-variant">Balai Desa Makmur, Loket SmartSort<br>RT 01/RW 03</p>
</div>
</div>
<div class="flex items-start gap-stack-md">
<div class="w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 shadow-sm border border-outline-variant/30">
<span class="material-symbols-outlined text-primary">schedule</span>
</div>
<div>
<h4 class="text-label-md font-label-md text-on-surface">Jam Operasional Setor</h4>
<p class="text-body-md font-body-md text-on-surface-variant">Senin - Jumat: 08:00 - 15:00</p>
<p class="text-body-md font-body-md text-primary mt-1">Sabtu &amp; Minggu: Khusus Penukaran Sembako</p>
</div>
</div>
</div>
</div>
<!-- Contact Form -->
<div class="glass p-stack-lg rounded-lg">
<form class="flex flex-col gap-stack-md">
<div>
<label class="block text-label-sm font-label-sm text-on-surface-variant mb-1" for="nama">Nama Lengkap</label>
<input class="w-full bg-surface-container-low text-on-surface border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:outline-none" id="nama" type="text">
</div>
<div class="grid grid-cols-2 gap-stack-md">
<div>
<label class="block text-label-sm font-label-sm text-on-surface-variant mb-1" for="rt-rw">RT/RW</label>
<input class="w-full bg-surface-container-low text-on-surface border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:outline-none" id="rt-rw" placeholder="Cth: 01/03" type="text">
</div>
<div>
<label class="block text-label-sm font-label-sm text-on-surface-variant mb-1" for="email">Email (Opsional)</label>
<input class="w-full bg-surface-container-low text-on-surface border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:outline-none" id="email" type="email">
</div>
</div>
<div>
<label class="block text-label-sm font-label-sm text-on-surface-variant mb-1" for="pesan">Pesan</label>
<textarea class="w-full bg-surface-container-low text-on-surface border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:outline-none resize-none" id="pesan" rows="4"></textarea>
</div>
<button class="mt-2 w-full bg-primary text-on-primary font-label-md text-label-md py-3 rounded-lg hover:bg-primary-container transition-colors shadow-sm" type="button">Kirim Pesan</button>
</form>
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

