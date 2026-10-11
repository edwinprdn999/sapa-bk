@extends('layouts.guest')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50/60 via-white to-slate-50 pt-16 pb-20 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-100/80 text-primary-700 text-xs font-semibold tracking-wide uppercase">
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    Layanan Bimbingan & Konseling Sekolah Resmi
                </div>

                <h1 class="text-4xl sm:text-5xl font-heading font-bold text-slate-900 leading-tight">
                    Ruang Konseling & Cerita Siswa Bersama Guru BK
                </h1>

                <p class="text-base sm:text-lg text-slate-600 max-w-2xl leading-relaxed">
                    SAPA-BK hadir untuk membantu siswa mengatasi kendala belajar di kelas, regulasi emosi remaja, perencanaan studi lanjut & karir, serta relasi sosial sekolah. Bersama Guru BK, aman dan rahasia.
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ auth()->check() ? route('student.appointment.create') : route('login') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-primary-600 text-white font-semibold text-sm shadow-lg shadow-primary-500/25 hover:bg-primary-700 active:scale-95 transition-all">
                        <span class="material-symbols-outlined">event_available</span>
                        Konsultasi dengan Guru BK
                    </a>
                    <a href="#layanan" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-white text-slate-700 font-semibold text-sm border border-slate-200 hover:bg-slate-50 active:scale-95 transition-all shadow-xs">
                        <span class="material-symbols-outlined">explore</span>
                        Lihat Layanan BK
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-6 pt-6 border-t border-slate-200/70 max-w-lg">
                    <div>
                        <div class="text-2xl font-bold font-heading text-slate-900">100%</div>
                        <div class="text-xs text-slate-500">Asas Kerahasiaan BK</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold font-heading text-slate-900">Guru BK</div>
                        <div class="text-xs text-slate-500">Konselor Sekolah</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold font-heading text-slate-900">Tatap Muka</div>
                        <div class="text-xs text-slate-500">Di Ruang BK Sekolah</div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="relative bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-indigo-50">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center font-bold">
                                <span class="material-symbols-outlined">volunteer_activism</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm">Prinsip Bimbingan Konseling Sekolah</h3>
                                <p class="text-xs text-slate-400">Kode Etik Guru BK</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-semibold">Aktif</span>
                    </div>

                    <div class="space-y-4 my-6">
                        <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50">
                            <span class="material-symbols-outlined text-primary-600 mt-0.5">lock</span>
                            <div>
                                <h4 class="text-xs font-semibold text-slate-800">Asas Kerahasiaan Penuh</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Cerita dan catatan sesi siswa dijamin rahasia dan hanya diketahui oleh Guru BK yang membimbing.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50">
                            <span class="material-symbols-outlined text-teal-600 mt-0.5">sentiment_satisfied</span>
                            <div>
                                <h4 class="text-xs font-semibold text-slate-800">Ruang Aman Tanpa Menghakimi</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Guru BK siap mendengarkan dengan penuh empati, membantu menyelesaikan masalah tanpa memberi sanksi.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50">
                            <span class="material-symbols-outlined text-indigo-600 mt-0.5">schedule</span>
                            <div>
                                <h4 class="text-xs font-semibold text-slate-800">Jadwal Teratur Tanpa Ganggu Jam Belajar</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Pilih jam istirahat atau slot khusus agar kegiatan belajar mengajar di kelas tetap tertib.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs">
                        <div class="font-bold flex items-center gap-1.5 mb-1">
                            <span class="material-symbols-outlined text-base text-amber-700">info</span>
                            Layanan Terbuka untuk Seluruh Siswa
                        </div>
                        Setiap siswa berhak mendapatkan bimbingan pribadi, belajar, sosial, dan karir secara gratis di sekolah.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Layanan Konseling -->
<section id="layanan" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <h2 class="text-xs font-bold text-primary-600 tracking-wider uppercase mb-2">Katalog Layanan BK</h2>
            <p class="text-3xl font-heading font-bold text-slate-900">Bidang Bimbingan & Konseling Sekolah</p>
            <p class="text-sm text-slate-600 mt-3">Pilih bidang bimbingan yang ingin Anda konsultasikan bersama Guru BK.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($services as $service)
                <div class="p-6 rounded-3xl bg-slate-50/70 border border-slate-100 hover:border-primary-200 hover:bg-white hover:shadow-lg transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-primary-100 text-primary-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-2xl">{{ $service->icon ?? 'psychology' }}</span>
                        </div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-slate-200/70 text-slate-700 text-[11px] font-semibold mb-2">
                            {{ $service->category }}
                        </span>
                        <h3 class="font-bold text-slate-900 text-base mb-2 group-hover:text-primary-600 transition-colors">
                            {{ $service->name }}
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-6">
                            {{ $service->description }}
                        </p>
                    </div>
                    <a href="{{ auth()->check() ? route('student.appointment.create', ['service_id' => $service->id]) : route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-800">
                        Pilih Bimbingan Ini
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            @empty
                <div class="col-span-4 text-center py-10 text-slate-500">
                    Belum ada layanan yang ditambahkan.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Profil Guru BK -->
<section id="konselor" class="py-20 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <h2 class="text-xs font-bold text-primary-600 tracking-wider uppercase mb-2">Guru BK Sekolah</h2>
            <p class="text-3xl font-heading font-bold text-slate-900">Siap Mendampingi & Membimbing Siswa</p>
            <p class="text-sm text-slate-600 mt-3">Bapak dan Ibu Guru Bimbingan dan Konseling sekolah siap membantu setiap permasalahan Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            @forelse($counselors as $counselor)
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-xl">
                                {{ strtoupper(substr($counselor->user->name ?? 'G', 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">{{ $counselor->user->name ?? 'Guru BK' }}</h3>
                                <p class="text-xs text-teal-700 font-medium">{{ $counselor->specialization }}</p>
                                <p class="text-[11px] text-slate-400">NIP/NUPTK: {{ $counselor->employee_number }}</p>
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            {{ $counselor->bio }}
                        </p>

                        <div class="flex items-center gap-2 text-xs text-slate-500 mb-6 bg-slate-50 p-2.5 rounded-xl">
                            <span class="material-symbols-outlined text-slate-400 text-base">meeting_room</span>
                            <span>{{ $counselor->room_location ?? 'Ruang BK Sekolah' }}</span>
                        </div>
                    </div>

                    <a href="{{ auth()->check() ? route('student.appointment.create', ['counselor_id' => $counselor->id]) : route('login') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-teal-50 text-teal-800 text-xs font-semibold hover:bg-teal-100 transition-colors">
                        <span class="material-symbols-outlined text-sm">calendar_month</span>
                        Pilih Jadwal dengan {{ explode(' ', $counselor->user->name)[0] }}
                    </a>
                </div>
            @empty
                <div class="col-span-2 text-center py-10 text-slate-500">
                    Daftar Guru BK belum tersedia.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Alur Konseling Siswa -->
<section id="alur" class="py-20 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <h2 class="text-xs font-bold text-primary-600 tracking-wider uppercase mb-2">Panduan Siswa</h2>
            <p class="text-3xl font-heading font-bold text-slate-900">4 Langkah Mudah Konseling di Sekolah</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-6 rounded-3xl bg-slate-50 text-center">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto mb-4 text-base">1</div>
                <h4 class="font-bold text-slate-900 text-sm mb-2">Pilih Bidang & Guru BK</h4>
                <p class="text-xs text-slate-600">Tentukan topik yang ingin dibahas dan pilih Guru BK yang Anda tuju.</p>
            </div>
            <div class="p-6 rounded-3xl bg-slate-50 text-center">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto mb-4 text-base">2</div>
                <h4 class="font-bold text-slate-900 text-sm mb-2">Pilih Slot Waktu</h4>
                <p class="text-xs text-slate-600">Pilih jam istirahat atau slot terbuka dan ceritakan hal yang ingin didiskusikan.</p>
            </div>
            <div class="p-6 rounded-3xl bg-slate-50 text-center">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto mb-4 text-base">3</div>
                <h4 class="font-bold text-slate-900 text-sm mb-2">Konfirmasi Guru BK</h4>
                <p class="text-xs text-slate-600">Guru BK menyetujui jadwal dan Anda mendapatkan ruang temu di Ruang BK.</p>
            </div>
            <div class="p-6 rounded-3xl bg-slate-50 text-center">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto mb-4 text-base">4</div>
                <h4 class="font-bold text-slate-900 text-sm mb-2">Bimbingan & Tindak Lanjut</h4>
                <p class="text-xs text-slate-600">Hadir di Ruang BK dan jalani rencana pengembangan diri dengan bimbingan Guru BK.</p>
            </div>
        </div>
    </div>
</section>
@endsection
