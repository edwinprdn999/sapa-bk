@extends('layouts.app')

@section('title', 'Audit Log Sistem')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">Jejak Aktivitas & Audit Log Sistem</h1>
            <p class="text-xs text-slate-500">Merekam riwayat akses, autentikasi, dan manipulasi data tanpa mengungkap detail catatan konseling.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-semibold">
                        <th class="p-4">Waktu</th>
                        <th class="p-4">Pengguna (Aktor)</th>
                        <th class="p-4">Aktivitas / Aksi</th>
                        <th class="p-4">Target Entitas</th>
                        <th class="p-4">Alamat IP</th>
                        <th class="p-4">User Agent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-[11px]">
                    @foreach($logs as $log)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-4 text-slate-500">
                                {{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') : '-' }}
                            </td>
                            <td class="p-4 font-sans font-semibold text-slate-900">
                                {{ $log->user->name ?? 'Tamu / Sistem' }}
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-primary-700 border border-indigo-100 font-mono">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $log->entity_type }} #{{ $log->entity_id ?? '-' }}
                            </td>
                            <td class="p-4 text-slate-500">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                            <td class="p-4 text-slate-400 font-sans max-w-xs truncate" title="{{ $log->user_agent }}">
                                {{ $log->user_agent ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>

</div>
@endsection
