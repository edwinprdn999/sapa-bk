@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">Manajemen Pengguna</h1>
            <p class="text-xs text-slate-500">Kelola akun siswa, guru BK, dan administrator sistem.</p>
        </div>
    </div>

    <!-- Filter Role -->
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.users') }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ empty($roleFilter) ? 'bg-primary-600 text-white font-semibold' : 'bg-white border border-slate-200 text-slate-600' }}">Semua Peran</a>
        <a href="{{ route('admin.users', ['role' => 'siswa']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ in_array($roleFilter, ['siswa', 'mahasiswa']) ? 'bg-primary-600 text-white font-semibold' : 'bg-white border border-slate-200 text-slate-600' }}">Siswa</a>
        <a href="{{ route('admin.users', ['role' => 'guru_bk']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ in_array($roleFilter, ['guru_bk', 'konselor']) ? 'bg-primary-600 text-white font-semibold' : 'bg-white border border-slate-200 text-slate-600' }}">Guru BK</a>
        <a href="{{ route('admin.users', ['role' => 'administrator']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ $roleFilter === 'administrator' ? 'bg-primary-600 text-white font-semibold' : 'bg-white border border-slate-200 text-slate-600' }}">Administrator</a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-semibold">
                        <th class="p-4">Pengguna</th>
                        <th class="p-4">Peran (Role)</th>
                        <th class="p-4">Identitas Khusus</th>
                        <th class="p-4">Status Akun</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $u)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $u->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $u->email }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $u->role->display_name ?? '-' }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-600">
                                @if($u->student)
                                    NISN: {{ $u->student->student_number }} ({{ $u->student->study_program }})
                                @elseif($u->counselor)
                                    NIP/NUPTK: {{ $u->counselor->employee_number }} ({{ $u->counselor->specialization }})
                                @else
                                    Akun Sistem
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $u->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ ucfirst($u->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                @if($u->id !== auth()->id())
                                    <form action="{{ route('admin.user.toggle', $u->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-lg text-xs font-medium border border-slate-200 hover:bg-slate-50 text-slate-700 transition-colors cursor-pointer">
                                            {{ $u->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
