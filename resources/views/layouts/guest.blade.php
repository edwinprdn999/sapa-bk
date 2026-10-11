<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SAPA-BK') }} — Layanan Bimbingan & Konseling Siswa dan Guru BK</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- Tailwind CSS (via Vite & CDN fallback for standalone resilience) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        },
                        secondary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                        }
                    },
                    fontFamily: {
                        sans: ['Roboto', 'system-ui', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary-600 to-indigo-500 text-white flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-2xl">favorite</span>
                </div>
                <div>
                    <span class="font-heading font-bold text-xl text-slate-900 tracking-tight block">SAPA<span class="text-primary-600">-BK</span></span>
                    <span class="text-[10px] text-slate-500 tracking-wider uppercase block -mt-1 font-medium">Bimbingan & Konseling Sekolah</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="{{ route('home') }}#layanan" class="hover:text-primary-600 transition-colors">Layanan BK</a>
                <a href="{{ route('home') }}#konselor" class="hover:text-primary-600 transition-colors">Guru BK</a>
                <a href="{{ route('home') }}#alur" class="hover:text-primary-600 transition-colors">Alur Bimbingan</a>
                <a href="{{ route('home') }}#privasi" class="hover:text-primary-600 transition-colors">Kerahasiaan & Etika</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->isStudent())
                        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-50 text-primary-700 text-sm font-medium hover:bg-primary-100 transition-colors">
                            <span class="material-symbols-outlined text-lg">dashboard</span>
                            Portal Siswa
                        </a>
                    @elseif(auth()->user()->isCounselor())
                        <a href="{{ route('counselor.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-teal-50 text-teal-700 text-sm font-medium hover:bg-teal-100 transition-colors">
                            <span class="material-symbols-outlined text-lg">dashboard</span>
                            Portal Guru BK
                        </a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-50 text-purple-700 text-sm font-medium hover:bg-purple-100 transition-colors">
                            <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
                            Admin Panel
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full text-slate-700 hover:text-primary-600 text-sm font-medium transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-full bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 shadow-md shadow-primary-500/20 active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-lg">person_add</span>
                        Daftar Akun Siswa
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-primary-600 text-white flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">favorite</span>
                        </div>
                        <span class="font-heading font-bold text-lg text-slate-900">SAPA-BK Sekolah</span>
                    </div>
                    <p class="text-sm text-slate-600 max-w-md leading-relaxed">
                        Sistem Aplikasi Pelayanan Bimbingan dan Konseling Sekolah. Memberikan ruang aman, nyaman, dan rahasia bagi siswa untuk berkonsultasi dengan Guru BK seputar belajar, pribadi, karir, dan sosial.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-900 text-sm mb-3">Layanan Bimbingan Sekolah</h4>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-emerald-500 text-base">location_on</span> Ruang BK Gedung Utama Sekolah</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-indigo-500 text-base">mail</span> bk-sekolah@sapa-bk.sch.id</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-amber-500 text-base">phone</span> Telp Sekolah: (021) 789-0123</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-900 text-sm mb-3">Asas Kerahasiaan BK</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Setiap sesi dan catatan bimbingan konseling terlindungi oleh asas kerahasiaan profesi Bimbingan dan Konseling (BK) serta dilindungi secara aman di sistem.
                    </p>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} SAPA-BK Sekolah. Seluruh hak cipta dilindungi.</p>
                <p class="mt-2 sm:mt-0">Sistem Bimbingan dan Konseling Siswa & Guru BK</p>
            </div>
        </div>
    </footer>

</body>
</html>
