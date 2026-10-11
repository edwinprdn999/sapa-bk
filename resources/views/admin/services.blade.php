@extends('layouts.app')

@section('title', 'Katalog Layanan BK')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">Katalog Layanan Bimbingan & Konseling</h1>
            <p class="text-xs text-slate-500">Kelola kategori dan deskripsi layanan yang dapat dipilih siswa.</p>
        </div>
    </div>

    <!-- Tambah Layanan Baru -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <h2 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-600">add_circle</span>
            Tambah Layanan Konseling Baru
        </h2>

        <form action="{{ route('admin.service.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Layanan <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Bimbingan Adaptasi & Penyesuaian Diri Siswa Baru"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary-500/20">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="category" required placeholder="Contoh: Akademik / Karir / Pribadi"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary-500/20">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ikon Material (Opsional)</label>
                    <input type="text" name="icon" value="psychology" placeholder="school, work, groups, psychology"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Layanan <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="2" required placeholder="Jelaskan tujuan dan ruang lingkup bimbingan..."
                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary-500/20"></textarea>
            </div>

            <button type="submit" class="px-5 py-2.5 rounded-full bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                Simpan Layanan Baru
            </button>
        </form>
    </div>

    <!-- Daftar Layanan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($services as $srv)
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-primary-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">{{ $srv->icon ?? 'psychology' }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">{{ $srv->name }}</h3>
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-semibold">{{ $srv->category }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">{{ $srv->description }}</p>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-500 pt-3 border-t border-slate-100">
                    <span>Total Digunakan: <strong class="text-slate-800">{{ $srv->appointments_count }} Sesi</strong></span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
