@extends('layouts.guest')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full bg-white p-8 sm:p-10 rounded-3xl border border-slate-100 shadow-xl space-y-6">
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-primary-600 text-white flex items-center justify-center mx-auto mb-4 shadow-md shadow-primary-500/20">
                <span class="material-symbols-outlined text-2xl">person_add</span>
            </div>
            <h2 class="text-2xl font-heading font-bold text-slate-900">Pendaftaran Akun Siswa</h2>
            <p class="mt-1 text-xs text-slate-500">
                Daftarkan diri Anda untuk mengakses layanan Bimbingan dan Konseling di sekolah
            </p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600"
                    placeholder="Contoh: Dimas Pratama">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="student_number" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Induk Siswa Nasional (NISN) <span class="text-rose-500">*</span></label>
                    <input id="student_number" name="student_number" type="text" required value="{{ old('student_number') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600"
                        placeholder="Contoh: 0061234567">
                </div>
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600"
                        placeholder="dimas@sapa-bk.sch.id">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="study_program" class="block text-xs font-semibold text-slate-700 mb-1">Kelas & Rombel <span class="text-rose-500">*</span></label>
                    <input id="study_program" name="study_program" type="text" required value="{{ old('study_program') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600"
                        placeholder="Contoh: XI RPL 1 atau X MIPA 2">
                </div>
                <div>
                    <label for="faculty" class="block text-xs font-semibold text-slate-700 mb-1">Jurusan / Peminatan</label>
                    <input id="faculty" name="faculty" type="text" value="{{ old('faculty') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600"
                        placeholder="Contoh: Rekayasa Perangkat Lunak / MIPA">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp Siswa / Orang Tua</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600"
                    placeholder="081234567890">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi (Min. 8 Karakter)</label>
                    <input id="password" name="password" type="password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600"
                        placeholder="••••••••">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-900 text-xs">
                Dengan mendaftar, data Anda akan diverifikasi untuk keperluan administrasi layanan Bimbingan Konseling dan dilindungi oleh kode etik kerahasiaan Guru BK di sekolah.
            </div>

            <button type="submit" class="w-full py-3 rounded-full bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm shadow-md shadow-primary-500/25 active:scale-95 transition-all cursor-pointer">
                Daftar Akun Siswa
            </button>
        </form>

        <div class="text-center pt-2 text-xs text-slate-500">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
