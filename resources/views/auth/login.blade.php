<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>SmartSort - Login Warga</title>
<link href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>♻️</text></svg>" rel="icon"/>
<!-- Google Fonts: Plus Jakarta Sans & Inter -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<!-- Tailwind Configuration -->

<style>
        .glass-card {
            background-color: rgba(247, 249, 251, 0.75); /* surface color with opacity */
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .input-glow:focus-within {
            box-shadow: 0 0 0 2px rgba(0, 107, 44, 0.2); /* primary color glow */
        }
    </style>
</head>
<body class="bg-background text-on-surface antialiased min-h-screen relative overflow-hidden flex items-center justify-center p-container-margin" style="font-family: 'Plus Jakarta Sans', sans-serif;">
<!-- Background Image with Overlay -->
<div class="absolute inset-0 z-0">
<img alt="Background" class="w-full h-full object-cover object-center opacity-80" data-alt="A bright, modern, and highly sophisticated digital waste processing facility bathed in soft natural light filtering through large glass windows. The scene features sleek, clean white metallic surfaces and subtle emerald green accents, representing a high-end ecological initiative. The atmosphere is calm, tech-forward, and premium, completely avoiding messy or dirty stereotypes of waste management. The overall aesthetic is minimalist, airy, and trustworthy, perfect for a financial-grade environmental platform." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBY6VoXlik9s49XiOcFjzEJOHNs_dTs9XaEmsKVWOGzgEMbm-pUMknXSPEAOjmKh2VnrKEK83MF02bSr3Ofnxj0Zbrf6G3Ax2b1bcmq2-C-lF52nz9Jka0kMdARhHURmTGBsu0uVnBcHZ2OxcO_IusZciACwymr4Mzrmt0AIGOlOr_ivQOIpY-GYT279FRfFMG36fV_zSFrjsQrHnoz402Mun5TFXY4MLCoye5SVSX1bZ5l9ib9RLjZ71bGoajNCaZ-SBetL01tcfxc"/>
<div class="absolute inset-0 bg-surface/30 mix-blend-overlay"></div>
</div>
<!-- Login Glass Card -->
<main class="z-10 w-full max-w-[440px] glass-card rounded-xl p-stack-lg shadow-2xl shadow-primary/5 flex flex-col gap-stack-md transform transition-all duration-500 hover:shadow-primary/10">
<!-- Header -->
<div class="text-center mb-base">
<div class="flex justify-center items-center gap-2 mb-stack-sm">
    <span class="text-headline-md font-headline-lg text-primary font-bold tracking-tight text-3xl">SmartSort</span>
</div>
<h1 class="font-headline-md text-headline-md text-on-surface mb-1">Masuk ke Akun</h1>
<p class="font-body-md text-body-md text-on-surface-variant">SmartSort Village - Digital Waste Bank</p>
</div>
<!-- Form -->
                @if ($errors->any())
                <div class="bg-error-container text-on-error-container rounded-lg p-3 text-label-sm font-label-md mb-2">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
<form method="POST" action="{{ route('login') }}" class="flex flex-col gap-stack-md w-full">
                @csrf
<!-- Username/NIK Field -->
<div class="flex flex-col gap-1">
<label class="font-label-md text-label-md text-on-surface-variant" for="nik">NIK</label>
<div class="relative flex items-center input-glow rounded-lg transition-shadow duration-300">
<svg class="absolute left-3 w-5 h-5 text-tertiary pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
</svg>
<input class="w-full pl-10 pr-4 py-3 rounded-lg bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 outline-none font-body-md text-body-md text-on-surface transition-colors placeholder:text-outline" id="nik" name="nik" value="{{ old('nik') }}" autofocus placeholder="Masukkan NIK KTP Anda" required="" type="text"/>
</div>
</div>
<!-- Password Field -->
<div class="flex flex-col gap-1">
<label class="font-label-md text-label-md text-on-surface-variant" for="password">Password</label>
<div class="relative flex items-center input-glow rounded-lg transition-shadow duration-300">
<svg class="absolute left-3 w-5 h-5 text-tertiary pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
</svg>
<input class="w-full pl-10 pr-10 py-3 rounded-lg bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 outline-none font-body-md text-body-md text-on-surface transition-colors placeholder:text-outline" id="password" name="password" placeholder="Masukkan password Anda" required="" type="password"/>
<button aria-label="Toggle password visibility" class="absolute right-3 text-tertiary hover:text-primary transition-colors focus:outline-none" type="button">
<span class="material-symbols-outlined">visibility_off</span>
</button>
</div>
</div>
<!-- Options Row -->
<div class="flex items-center justify-between mt-1">
<label class="flex items-center gap-2 cursor-pointer group">
<input class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary focus:ring-offset-0 bg-surface-container-low transition-colors cursor-pointer" type="checkbox"/>
<span class="font-body-md text-body-md text-on-surface-variant group-hover:text-on-surface transition-colors">Ingat saya</span>
</label>
<button type="button" class="font-label-md text-label-md text-primary hover:text-primary-container transition-colors focus:outline-none" onclick="alert('Jika Anda lupa password, silakan hubungi atau temui Petugas Bank Sampah di Balai Desa untuk melakukan reset password menggunakan NIK Anda.')">Lupa Password?</button>
</div>
<!-- Submit Button (Primary Style - Soft Gold) -->
<button class="w-full mt-stack-sm py-3 rounded-lg bg-secondary-fixed-dim text-on-secondary-fixed-variant font-label-md text-label-md flex justify-center items-center gap-2 hover:opacity-90 active:scale-[0.98] transition-all duration-200 shadow-sm shadow-secondary-fixed-dim/20" type="submit">
<span>Masuk</span>
<span class="material-symbols-outlined text-[20px]">login</span>
</button>
</form>
<!-- Divider -->
<div class="relative flex items-center py-2">
<div class="grow border-t border-outline-variant/50"></div>
<span class="shrink-0 mx-4 font-label-sm text-label-sm text-outline">atau</span>
<div class="grow border-t border-outline-variant/50"></div>
</div>
<!-- Register Link -->
<p class="text-center font-body-md text-body-md text-on-surface-variant">
            Belum punya akun? 
            <a class="font-label-md text-label-md text-primary hover:text-primary-container underline underline-offset-2 transition-colors" href="{{ route('register') }}">Daftar sekarang</a>
</p>
</main>
</body></html>

