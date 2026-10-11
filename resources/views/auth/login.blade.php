@extends('layouts.guest')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-100 shadow-xl">
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-primary-600 text-white flex items-center justify-center mx-auto mb-4 shadow-md shadow-primary-500/20">
                <span class="material-symbols-outlined text-2xl">lock</span>
            </div>
            <h2 class="text-2xl font-heading font-bold text-slate-900">Masuk ke SAPA-BK</h2>
            <p class="mt-2 text-xs text-slate-500">
                Gunakan akun Siswa, Guru BK, atau Administrator Sekolah
            </p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form class="mt-6 space-y-4" action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-3 text-slate-400 text-lg">mail</span>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 transition-all placeholder:text-slate-400"
                        placeholder="contoh@sapa-bk.sch.id">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-3 text-slate-400 text-lg">key</span>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 transition-all placeholder:text-slate-400"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center text-slate-600">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-slate-300">
                    <span class="ml-2">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 rounded-full bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm shadow-md shadow-primary-500/25 active:scale-95 transition-all cursor-pointer">
                Masuk ke Akun
            </button>
        </form>

        <!-- Quick Demo Fill Section -->
        <div class="pt-6 border-t border-slate-100">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center mb-3">
                Akun Demo Cepat (Klik untuk Isi Otomatis)
            </div>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="fillLogin('siswa.dimas@sapa-bk.sch.id', 'password123')" class="px-2 py-2 rounded-xl bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-primary-300 text-center text-xs font-medium text-slate-700 hover:text-primary-700 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-base block mb-0.5 text-indigo-500">school</span>
                    Siswa
                </button>
                <button type="button" onclick="fillLogin('gurubk.sarah@sapa-bk.sch.id', 'password123')" class="px-2 py-2 rounded-xl bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-secondary-300 text-center text-xs font-medium text-slate-700 hover:text-teal-700 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-base block mb-0.5 text-teal-500">psychology</span>
                    Guru BK
                </button>
                <button type="button" onclick="fillLogin('admin@sapa-bk.sch.id', 'password123')" class="px-2 py-2 rounded-xl bg-slate-50 hover:bg-purple-50 border border-slate-200 hover:border-purple-300 text-center text-xs font-medium text-slate-700 hover:text-purple-700 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-base block mb-0.5 text-purple-500">admin_panel_settings</span>
                    Admin
                </button>
            </div>
        </div>

        <div class="text-center pt-2 text-xs text-slate-500">
            Belum memiliki akun siswa?
            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">Daftar sekarang</a>
        </div>
    </div>
</div>

<script>
    function fillLogin(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
