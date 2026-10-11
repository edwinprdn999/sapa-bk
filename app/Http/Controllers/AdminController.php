<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\CounselingService;
use App\Models\Counselor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_appointments' => Appointment::count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'approved' => Appointment::where('status', 'approved')->count(),
            'students_count' => Student::count(),
            'counselors_count' => Counselor::count(),
        ];

        // Breakdown by service category (Aggregate Only - Zero Confidentiality Leak)
        $serviceBreakdown = CounselingService::withCount('appointments')->get();

        $recentAppointments = Appointment::with(['student.user', 'counselor.user', 'service'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $recentAudits = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'serviceBreakdown', 'recentAppointments', 'recentAudits'));
    }

    public function users(Request $request)
    {
        $roleFilter = $request->query('role');
        $query = User::with(['role', 'student', 'counselor'])->orderBy('created_at', 'desc');

        if ($roleFilter) {
            $roles = match ($roleFilter) {
                'siswa', 'mahasiswa' => ['siswa', 'mahasiswa'],
                'guru_bk', 'konselor' => ['guru_bk', 'konselor'],
                default => [$roleFilter],
            };
            $query->whereHas('role', function ($q) use ($roles) {
                $q->whereIn('name', $roles);
            });
        }

        $users = $query->paginate(15);
        return view('admin.users', compact('users', 'roleFilter'));
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        AuditLog::log('toggle_user_status', 'User', $user->id);

        return back()->with('success', 'Status pengguna ' . $user->name . ' berhasil diubah menjadi ' . $user->status . '.');
    }

    public function services()
    {
        $services = CounselingService::withCount('appointments')->get();
        return view('admin.services', compact('services'));
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
        ]);

        CounselingService::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'category' => $validated['category'],
            'description' => $validated['description'],
            'icon' => $validated['icon'] ?? 'psychology',
            'is_active' => true,
        ]);

        return back()->with('success', 'Layanan bimbingan konseling berhasil ditambahkan.');
    }

    public function auditLogs(Request $request)
    {
        $logs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.audit_logs', compact('logs'));
    }
}
