@extends('layouts.app')

@section('title', 'Atur Slot Jadwal Guru BK')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">Ketersediaan Jadwal Guru BK</h1>
            <p class="text-xs text-slate-500">Buka slot waktu bimbingan agar siswa dapat memilih waktu konsultasi pada jam kerja atau jam istirahat sekolah.</p>
        </div>
    </div>

    <!-- Form Tambah Slot Baru -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <h2 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-teal-600">add_circle</span>
            Buka Slot Jadwal Bimbingan Baru
        </h2>

        <form action="{{ route('counselor.schedule.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Bimbingan <span class="text-rose-500">*</span></label>
                <input type="date" name="date" required min="{{ date('Y-m-d') }}" value="{{ old('date', date('Y-m-d')) }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                <input type="time" name="start_time" required value="{{ old('start_time', '09:00') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Selesai <span class="text-rose-500">*</span></label>
                <input type="time" name="end_time" required value="{{ old('end_time', '10:00') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                    Simpan Slot Jadwal
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Slot Terbuka -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm">Daftar Slot Jadwal Bimbingan Mendatang</h3>
        </div>

        @if($schedules->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-semibold">
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Jam Konsultasi</th>
                            <th class="p-4">Status Pemesanan</th>
                            <th class="p-4 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($schedules as $sch)
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-4 font-semibold text-slate-900">
                                    {{ $sch->date->translatedFormat('l, d F Y') }}
                                </td>
                                <td class="p-4 text-slate-700 font-medium">
                                    {{ substr($sch->start_time, 0, 5) }} - {{ substr($sch->end_time, 0, 5) }} WIB
                                </td>
                                <td class="p-4">
                                    @if($sch->is_booked)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                            Sudah Dipilih Siswa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            Tersedia untuk Siswa
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    @if(!$sch->is_booked)
                                        <form action="{{ route('counselor.schedule.delete', $sch->id) }}" method="POST" onsubmit="return confirm('Hapus slot ketersediaan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Slot">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-[11px] italic">Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $schedules->links() }}
            </div>
        @else
            <div class="p-10 text-center text-slate-500 text-xs">
                Belum ada slot waktu yang dibuka. Silakan tambahkan jadwal ketersediaan di atas.
            </div>
        @endif
    </div>

</div>
@endsection
