@extends('layouts.app')

@section('title', 'Rencana Tindak Lanjut Siswa')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">Rencana Tindak Lanjut Siswa (Action Plan)</h1>
        <p class="text-xs text-slate-500">Tugas mandiri dan target pengembangan diri yang disepakati bersama Guru BK.</p>
    </div>

    <div class="space-y-4">
        @forelse($followUps as $fu)
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                            <span class="material-symbols-outlined">task_alt</span>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-slate-800">
                                Pembimbing: {{ $fu->counselor->user->name ?? 'Guru BK' }}
                            </div>
                            <div class="text-[11px] text-slate-400">
                                Bidang: {{ $fu->session->appointment->service->name ?? 'Bimbingan' }}
                            </div>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $fu->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $fu->status_label }}
                    </span>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Target / Rencana Tindakan Siswa:</h3>
                    <p class="text-sm font-semibold text-slate-800 leading-relaxed bg-slate-50 p-4 rounded-2xl">
                        {{ $fu->action_plan }}
                    </p>
                </div>

                @if($fu->due_date)
                    <div class="text-xs text-slate-500 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm text-slate-400">event</span>
                        Batas Waktu Pelaksanaan: <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($fu->due_date)->translatedFormat('d F Y') }}</span>
                    </div>
                @endif

                <!-- Form Catatan Perkembangan Siswa -->
                <form action="{{ route('student.follow_up.update', $fu->id) }}" method="POST" class="pt-3 border-t border-slate-100 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Catatan Perkembangan Siswa (Untuk Dilihat Guru BK):
                        </label>
                        <textarea name="student_notes" rows="2" required placeholder="Ceritakan bagaimana Anda menjalankan rencana ini di rumah atau di kelas..."
                            class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600">{{ $fu->student_notes }}</textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs">
                            <label class="text-slate-600">Status Tindak Lanjut:</label>
                            <select name="status" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs bg-white text-slate-800">
                                <option value="pending" {{ $fu->status === 'pending' ? 'selected' : '' }}>Belum Dimulai</option>
                                <option value="in_progress" {{ $fu->status === 'in_progress' ? 'selected' : '' }}>Sedang Berjalan</option>
                                <option value="completed" {{ $fu->status === 'completed' ? 'selected' : '' }}>Tuntas / Selesai</option>
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold transition-colors cursor-pointer shadow-xs">
                            Simpan Perkembangan
                        </button>
                    </div>
                </form>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-100">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl">checklist</span>
                </div>
                <p class="text-sm font-medium text-slate-700">Belum ada rencana tindak lanjut dari Guru BK.</p>
                <p class="text-xs text-slate-400 mt-1">Target tindakan akan muncul di sini setelah Anda menyelesaikan sesi bimbingan bersama Guru BK.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
