<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SAPA-BK Sekolah</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Tailwind CSS (via CDN with Material theme tokens) -->
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
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        secondary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                        }
                    },
                    boxShadow: {
                        'md-1': '0 1px 3px 0 rgba(0, 0, 0, 0.08), 0 1px 2px -1px rgba(0, 0, 0, 0.08)',
                        'md-2': '0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -2px rgba(0, 0, 0, 0.08)',
                        'md-3': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.08)',
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

    <!-- Top AppBar (Material Style) -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200/80 shadow-xs">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-primary-600 text-white flex items-center justify-center shadow-md shadow-primary-500/20">
                        <span class="material-symbols-outlined text-xl">favorite</span>
                    </div>
                    <div>
                        <span class="font-heading font-bold text-lg text-slate-900 tracking-tight">SAPA<span class="text-primary-600">-BK</span></span>
                        <span class="hidden sm:inline-block text-[11px] text-slate-500 ml-2 px-2 py-0.5 rounded-full bg-slate-100 font-medium">
                            {{ auth()->user()->role->display_name ?? 'Pengguna' }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <!-- Notifications icon -->
                <div class="relative">
                    <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center cursor-pointer hover:bg-slate-200 transition-colors">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                    </div>
                </div>

                <!-- User profile & Logout -->
                <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
                    <div class="w-9 h-9 rounded-full bg-primary-100 text-primary-700 font-bold flex items-center justify-center text-sm shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-sm font-semibold text-slate-800 leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-500">{{ auth()->user()->email }}</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-full hover:bg-rose-50 transition-colors cursor-pointer" title="Keluar">
                            <span class="material-symbols-outlined text-xl">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Workspace with Sidebar Navigation -->
    <div class="flex-1 flex max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6">

        <!-- Sidebar Navigation Drawer -->
        <aside class="w-64 flex-shrink-0 hidden md:block">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm sticky top-24">
                <div class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-slate-400">
                    Menu Navigasi
                </div>

                <nav class="mt-2 space-y-1.5">
                    @if(auth()->user()->isStudent())
                        <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('student.dashboard') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('student.dashboard') ? 'text-primary-600' : 'text-slate-400' }}">dashboard</span>
                            Dashboard Siswa
                        </a>
                        <a href="{{ route('student.appointment.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('student.appointment.create') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('student.appointment.create') ? 'text-primary-600' : 'text-slate-400' }}">add_circle</span>
                            Ajukan Bimbingan BK
                        </a>
                        <a href="{{ route('student.appointments') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('student.appointments*') && !request()->routeIs('student.appointment.create') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('student.appointments*') ? 'text-primary-600' : 'text-slate-400' }}">calendar_month</span>
                            Jadwal & Riwayat BK
                        </a>
                        <a href="{{ route('student.follow_ups') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('student.follow_ups') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('student.follow_ups') ? 'text-primary-600' : 'text-slate-400' }}">task_alt</span>
                            Rencana Tindak Lanjut
                        </a>

                    @elseif(auth()->user()->isCounselor())
                        <a href="{{ route('counselor.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('counselor.dashboard') ? 'bg-teal-50 text-teal-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('counselor.dashboard') ? 'text-teal-600' : 'text-slate-400' }}">dashboard</span>
                            Dashboard Guru BK
                        </a>
                        <a href="{{ route('counselor.appointments') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('counselor.appointments*') ? 'bg-teal-50 text-teal-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('counselor.appointments*') ? 'text-teal-600' : 'text-slate-400' }}">assignment</span>
                            Permohonan Siswa
                        </a>
                        <a href="{{ route('counselor.schedules') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('counselor.schedules*') ? 'bg-teal-50 text-teal-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('counselor.schedules*') ? 'text-teal-600' : 'text-slate-400' }}">more_time</span>
                            Atur Slot Jadwal BK
                        </a>

                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.dashboard') ? 'text-purple-600' : 'text-slate-400' }}">insights</span>
                            Laporan & Analitik Sekolah
                        </a>
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.users') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.users') ? 'text-purple-600' : 'text-slate-400' }}">group</span>
                            Kelola Siswa & Guru BK
                        </a>
                        <a href="{{ route('admin.services') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.services') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.services') ? 'text-purple-600' : 'text-slate-400' }}">category</span>
                            Katalog Layanan BK
                        </a>
                        <a href="{{ route('admin.audit_logs') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.audit_logs') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.audit_logs') ? 'text-purple-600' : 'text-slate-400' }}">security</span>
                            Audit Log Sistem
                        </a>
                    @endif
                </nav>

                <div class="mt-8 pt-4 border-t border-slate-100">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                        <div class="text-xs font-semibold text-slate-700 flex items-center gap-1.5 mb-1">
                            <span class="material-symbols-outlined text-base text-primary-600">lock</span>
                            Asas Kerahasiaan BK Terjaga
                        </div>
                        <div class="text-[11px] text-slate-500 leading-relaxed">
                            Catatan sesi siswa dilindungi kode etik kerahasiaan Guru BK dan kontrol hak akses server.
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Body -->
        <main class="flex-1 min-w-0">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-xs">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 shadow-xs">
                    <span class="material-symbols-outlined text-rose-600">error</span>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs">
                    <div class="flex items-center gap-2 font-medium text-sm mb-1">
                        <span class="material-symbols-outlined text-rose-600">warning</span>
                        Perhatian: Terdapat beberapa kesalahan dalam pengisian:
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 pl-6">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
