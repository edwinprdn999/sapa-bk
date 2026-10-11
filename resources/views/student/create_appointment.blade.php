@extends('layouts.app')

@section('title', 'Ajukan Bimbingan Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('student.dashboard') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
        </a>
        <div>
            <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">Formulir Pengajuan Bimbingan Siswa</h1>
            <p class="text-xs text-slate-500">Pilih Guru BK dan tentukan waktu konsultasi. Cerita Anda terlindungi asas kerahasiaan BK.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm">
        <form action="{{ route('student.appointment.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Pilih Layanan -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    1. Pilih Bidang Bimbingan <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($services as $srv)
                        <label class="relative flex items-start gap-3 p-4 rounded-2xl border border-slate-200 hover:border-primary-400 cursor-pointer transition-all has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50/40">
                            <input type="radio" name="service_id" value="{{ $srv->id }}" required
                                class="mt-1 text-primary-600 focus:ring-primary-500"
                                {{ (old('service_id') == $srv->id || $selectedService == $srv->id) ? 'checked' : '' }}>
                            <div>
                                <div class="font-semibold text-sm text-slate-900 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-primary-600 text-lg">{{ $srv->icon ?? 'psychology' }}</span>
                                    {{ $srv->name }}
                                </div>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $srv->description }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 2. Pilih Guru BK -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    2. Pilih Guru BK yang Dituju <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($counselors as $csl)
                        <label class="relative flex items-center gap-3 p-4 rounded-2xl border border-slate-200 hover:border-primary-400 cursor-pointer transition-all has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50/40">
                            <input type="radio" name="counselor_id" value="{{ $csl->id }}" required
                                class="text-primary-600 focus:ring-primary-500"
                                {{ (old('counselor_id') == $csl->id || $selectedCounselor == $csl->id) ? 'checked' : '' }}
                                onchange="onCounselorChange(this.value)">
                            <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($csl->user->name ?? 'G', 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-sm text-slate-900">{{ $csl->user->name ?? 'Guru BK' }}</div>
                                <div class="text-xs text-teal-700 font-medium">{{ $csl->specialization }}</div>
                                <div class="text-[11px] text-slate-400">Ruang: {{ $csl->room_location ?? 'Ruang BK Sekolah' }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 3. Jadwal & Waktu -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                    3. Waktu Bimbingan yang Diharapkan <span class="text-rose-500">*</span>
                </label>

                <!-- Available Slots (Jika ada) -->
                @if(count($availableSchedules) > 0)
                    <div>
                        <div class="text-xs text-slate-600 font-medium mb-2">Pilih Slot Ketersediaan Terbuka dari Guru BK:</div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($availableSchedules as $sch)
                                <label class="p-3 rounded-xl border border-slate-200 hover:border-indigo-400 cursor-pointer text-center text-xs transition-all has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50">
                                    <input type="radio" name="schedule_id" value="{{ $sch->id }}"
                                        class="sr-only"
                                        onchange="applySlot('{{ $sch->date->format('Y-m-d') }}', '{{ substr($sch->start_time, 0, 5) }}', '{{ substr($sch->end_time, 0, 5) }}')">
                                    <div class="font-semibold text-slate-800">{{ $sch->date->translatedFormat('d M Y') }}</div>
                                    <div class="text-primary-600 font-medium">{{ substr($sch->start_time, 0, 5) }} - {{ substr($sch->end_time, 0, 5) }} WIB</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Bimbingan</label>
                        <input type="date" id="consultation_date" name="consultation_date" required min="{{ date('Y-m-d') }}"
                            value="{{ old('consultation_date', date('Y-m-d')) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Jam Mulai (WIB)</label>
                        <input type="time" id="start_time" name="start_time" required value="{{ old('start_time', '09:00') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Jam Selesai (WIB)</label>
                        <input type="time" id="end_time" name="end_time" required value="{{ old('end_time', '10:00') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600">
                    </div>
                </div>
            </div>

            <!-- 4. Metode Konseling -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    4. Tempat / Metode Pertemuan <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 hover:border-primary-400 cursor-pointer has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50/40">
                        <input type="radio" name="method" value="offline" required checked class="text-primary-600">
                        <div>
                            <div class="font-semibold text-sm text-slate-900 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary-600 text-base">meeting_room</span>
                                Tatap Muka di Ruang BK Sekolah
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">Pertemuan langsung di ruang konseling sekolah</div>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 hover:border-primary-400 cursor-pointer has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50/40">
                        <input type="radio" name="method" value="online" required class="text-primary-600">
                        <div>
                            <div class="font-semibold text-sm text-slate-900 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary-600 text-base">videocam</span>
                                Bimbingan Daring (Online)
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">Tautan video call dikirimkan Guru BK di portal</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 5. Ringkasan Topik / Masalah Awal -->
            <div class="pt-4 border-t border-slate-100">
                <label for="initial_concern" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    5. Cerita Singkat Hal yang Ingin Dibicarakan <span class="text-rose-500">*</span>
                </label>
                <p class="text-xs text-slate-500 mb-2">Tuliskan gambaran umum kendala belajar, karir, atau pribadi Anda (minimal 15 karakter). Informasi ini dilindungi kerahasiaannya.</p>
                <textarea id="initial_concern" name="initial_concern" rows="4" required minlength="15" maxlength="1500"
                    class="w-full p-4 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 placeholder:text-slate-400"
                    placeholder="Contoh: Saya merasa kesulitan membagi waktu antara tugas kejuruan di sekolah dengan belajar mata pelajaran umum, ingin berkonsultasi mengenai strategi belajar efektif...">{{ old('initial_concern') }}</textarea>
            </div>

            <!-- Jaminan Privasi -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3 text-xs text-slate-600">
                <span class="material-symbols-outlined text-primary-600 mt-0.5">security</span>
                <div>
                    <span class="font-semibold text-slate-800">Asas Kerahasiaan Bimbingan Konseling:</span>
                    Permohonan ini hanya dapat dibaca oleh Guru BK yang Anda pilih. Teman sekelas atau pihak lain tidak dapat melihat isi cerita Anda.
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('student.dashboard') }}" class="px-5 py-2.5 rounded-full border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold shadow-md shadow-primary-500/25 active:scale-95 transition-all cursor-pointer">
                    Kirim Permohonan Bimbingan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function onCounselorChange(counselorId) {
        const url = new URL(window.location.href);
        url.searchParams.set('counselor_id', counselorId);
        window.location.href = url.toString();
    }

    function applySlot(date, start, end) {
        document.getElementById('consultation_date').value = date;
        document.getElementById('start_time').value = start;
        document.getElementById('end_time').value = end;
    }
</script>
@endsection
