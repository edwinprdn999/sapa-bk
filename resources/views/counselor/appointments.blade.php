@extends('layouts.app')

@section('title', 'Kelola Permohonan Bimbingan Siswa')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">Kelola Permohonan Bimbingan Siswa</h1>
            <p class="text-xs text-slate-500">Daftar permohonan konsultasi siswa yang ditujukan kepada Anda.</p>
        </div>
    </div>

    <!-- Filter Status Chips -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <a href="{{ route('counselor.appointments') }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ empty($status) ? 'bg-teal-700 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua
        </a>
        <a href="{{ route('counselor.appointments', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ $status === 'pending' ? 'bg-amber-600 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Perlu Konfirmasi (Pending)
        </a>
        <a href="{{ route('counselor.appointments', ['status' => 'approved']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ $status === 'approved' ? 'bg-emerald-600 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Terkonfirmasi (Approved)
        </a>
        <a href="{{ route('counselor.appointments', ['status' => 'completed']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ $status === 'completed' ? 'bg-indigo-600 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Selesai Dibimbing
        </a>
        <a href="{{ route('counselor.appointments', ['status' => 'reschedule_requested']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ $status === 'reschedule_requested' ? 'bg-orange-600 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Perlu Reschedule
        </a>
    </div>

    <!-- Appointments List -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        @if($appointments->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($appointments as $apt)
                    <div class="p-5 hover:bg-slate-50/60 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1.5 max-w-xl">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-slate-900">{{ $apt->appointment_code }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $apt->status_color_class }}">
                                    {{ $apt->status_label }}
                                </span>
                            </div>

                            <h3 class="font-bold text-slate-800 text-sm">
                                {{ $apt->student->user->name ?? 'Siswa' }}
                                <span class="font-normal text-xs text-slate-500">(NISN: {{ $apt->student->student_number ?? '-' }} &bull; {{ $apt->student->study_program ?? '' }})</span>
                            </h3>

                            <p class="text-xs text-slate-600 line-clamp-1 italic">
                                "{{ $apt->initial_concern }}"
                            </p>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 pt-1">
                                <span class="flex items-center gap-1 font-medium text-slate-700">
                                    <span class="material-symbols-outlined text-sm text-teal-600">psychology</span>
                                    {{ $apt->service->name ?? 'Layanan' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-slate-400">calendar_month</span>
                                    {{ \Carbon\Carbon::parse($apt->consultation_date)->translatedFormat('d F Y') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-slate-400">schedule</span>
                                    {{ substr($apt->start_time, 0, 5) }} - {{ substr($apt->end_time, 0, 5) }} WIB
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-slate-400">
                                        {{ $apt->method === 'online' ? 'videocam' : 'location_on' }}
                                    </span>
                                    {{ $apt->method === 'online' ? 'Daring' : 'Ruang BK' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-center">
                            @if(in_array($apt->status, ['approved', 'completed', 'in_progress']))
                                <a href="{{ route('counselor.session.view', $apt->id) }}" class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl bg-teal-50 text-teal-700 hover:bg-teal-100 text-xs font-semibold transition-colors">
                                    <span class="material-symbols-outlined text-base">edit_note</span>
                                    Catatan Sesi
                                </a>
                            @endif

                            <a href="{{ route('counselor.appointment.detail', $apt->id) }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-xl bg-primary-50 text-primary-700 hover:bg-primary-100 text-xs font-semibold transition-colors">
                                Tinjau
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <p class="text-sm font-medium text-slate-700">Tidak ada permohonan siswa pada filter ini.</p>
            </div>
        @endif
    </div>

</div>
@endsection
