<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>SmartSort - Register Warga</title>
<link href="data:image/svg+xml,&lt;svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22&gt;&lt;text y=%22.9em%22 font-size=%2290%22&gt;♻️&lt;/text&gt;&lt;/svg&gt;" rel="icon"/>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>

<style>
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .input-glow:focus-within {
            box-shadow: 0 0 0 4px rgba(0, 107, 44, 0.1);
        }
    </style>
</head>
<body class="bg-background min-h-screen flex flex-col font-body-md text-on-background antialiased relative overflow-x-hidden" style="font-family: 'Plus Jakarta Sans', sans-serif;">
<!-- Background Image with Overlay -->
<div class="absolute inset-0 z-0">
<img alt="Background" class="w-full h-full object-cover object-center opacity-80" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBY6VoXlik9s49XiOcFjzEJOHNs_dTs9XaEmsKVWOGzgEMbm-pUMknXSPEAOjmKh2VnrKEK83MF02bSr3Ofnxj0Zbrf6G3Ax2b1bcmq2-C-lF52nz9Jka0kMdARhHURmTGBsu0uVnBcHZ2OxcO_IusZciACwymr4Mzrmt0AIGOlOr_ivQOIpY-GYT279FRfFMG36fV_zSFrjsQrHnoz402Mun5TFXY4MLCoye5SVSX1bZ5l9ib9RLjZ71bGoajNCaZ-SBetL01tcfxc"/>
<div class="absolute inset-0 bg-surface/30 mix-blend-overlay"></div>
</div>
<!-- Main Content -->
<main class="grow flex items-center justify-center relative z-10 px-gutter py-section-gap">
<div class="w-full max-w-[500px]">
<!-- Header/Brand -->
<div class="text-center mb-stack-lg flex flex-col items-center">
<div class="flex justify-center items-center gap-2 mb-stack-sm">
    <span class="text-headline-md font-headline-lg text-primary font-bold tracking-tight text-3xl">SmartSort</span>
</div>
<p class="font-body-md text-body-md text-on-surface-variant">
                    Bergabung untuk masa depan lingkungan yang lebih baik.
                </p>
</div>
<!-- Registration Card -->
<div class="glass-card rounded-xl p-stack-lg shadow-sm">
<h2 class="font-headline-md text-headline-md text-on-surface mb-stack-lg text-center">Buat Akun Baru</h2>
<form method="POST" action="{{ route('register') }}" class="flex flex-col gap-stack-md">
                @csrf
                @if ($errors->any())
                <div class="bg-error-container text-on-error-container rounded-lg p-3 text-label-sm font-label-md mb-2">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
<!-- Nama Lengkap -->
<div class="flex flex-col gap-base">
<label class="font-label-md text-label-md text-on-surface" for="fullName">Nama Lengkap</label>
<div class="relative input-glow rounded-lg transition-all duration-300">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<svg class="w-5 h-5 text-tertiary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
</svg>
</div>
<input class="w-full pl-10 pr-3 py-3 bg-surface-container-low border border-surface-variant rounded-lg font-body-md text-body-md text-on-surface placeholder-outline focus:outline-none focus:border-primary focus:ring-0 transition-colors" id="name" name="name" value="{{ old('name') }}" autofocus placeholder="Masukkan nama lengkap" required="" type="text"/>
</div>
</div>
<!-- NIK -->
<div class="flex flex-col gap-base">
<label class="font-label-md text-label-md text-on-surface" for="nik">NIK KTP</label>
<div class="relative input-glow rounded-lg transition-all duration-300">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<svg class="w-5 h-5 text-tertiary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
</svg>
</div>
<input class="w-full pl-10 pr-3 py-3 bg-surface-container-low border border-surface-variant rounded-lg font-body-md text-body-md text-on-surface placeholder-outline focus:outline-none focus:border-primary focus:ring-0 transition-colors" id="nik" name="nik" value="{{ old('nik') }}" placeholder="Masukkan 16 digit NIK" required="" type="text"/>
</div>
</div>
<!-- Alamat (RT/RW) -->
<div class="flex flex-col gap-base">
<label class="font-label-md text-label-md text-on-surface" for="rt_rw">Alamat (RT/RW)</label>
<div class="relative input-glow rounded-lg transition-all duration-300">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<svg class="w-5 h-5 text-tertiary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
</svg>
</div>
<input class="w-full pl-10 pr-3 py-3 bg-surface-container-low border border-surface-variant rounded-lg font-body-md text-body-md text-on-surface placeholder-outline focus:outline-none focus:border-primary focus:ring-0 transition-colors" id="rt_rw" name="rt_rw" value="{{ old('rt_rw') }}" placeholder="Contoh: RT 01 / RW 02" required="" type="text"/>
</div>
</div>

<!-- Password -->
<div class="flex flex-col gap-base">
<label class="font-label-md text-label-md text-on-surface" for="password">Kata Sandi</label>
<div class="relative input-glow rounded-lg transition-all duration-300">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<svg class="w-5 h-5 text-tertiary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
</svg>
</div>
<input class="w-full pl-10 pr-10 py-3 bg-surface-container-low border border-surface-variant rounded-lg font-body-md text-body-md text-on-surface placeholder-outline focus:outline-none focus:border-primary focus:ring-0 transition-colors" id="password" name="password" placeholder="Minimal 8 karakter" required="" type="password"/>
<button aria-label="Toggle password visibility" class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface transition-colors" type="button">
<span class="material-symbols-outlined">visibility</span>
</button>
</div>
</div>
<!-- Confirm Password -->
<div class="flex flex-col gap-base">
<label class="font-label-md text-label-md text-on-surface" for="confirm_password">Konfirmasi Kata Sandi</label>
<div class="relative input-glow rounded-lg transition-all duration-300">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<svg class="w-5 h-5 text-tertiary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
</svg>
</div>
<input class="w-full pl-10 pr-10 py-3 bg-surface-container-low border border-surface-variant rounded-lg font-body-md text-body-md text-on-surface placeholder-outline focus:outline-none focus:border-primary focus:ring-0 transition-colors" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang kata sandi" required="" type="password"/>
<button aria-label="Toggle confirm password visibility" class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface transition-colors" type="button">
<span class="material-symbols-outlined">visibility</span>
</button>
</div>
</div>
<!-- CTA Button -->
<button class="mt-stack-sm w-full bg-secondary-container text-on-secondary-container font-label-md text-label-md py-3 px-4 rounded-xl hover:bg-secondary-fixed transition-colors flex items-center justify-center gap-2 group" type="submit">
                        Daftar Sekarang
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
</form>
<!-- Login Link -->
<div class="mt-stack-lg text-center">
<p class="font-body-md text-body-md text-on-surface-variant">
                        Sudah punya akun? 
                        <a class="text-primary font-label-md text-label-md hover:underline hover:text-primary-container transition-colors" href="{{ route('login') }}">
                            Masuk di sini
                        </a>
</p>
</div>
</div>
</div>
</main>
</body></html>
