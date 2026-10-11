<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\CounselingService;
use App\Models\Counselor;
use App\Models\CounselorSchedule;
use App\Models\FollowUp;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    private function getStudent()
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(403, 'Profil mahasiswa tidak ditemukan.');
        }
        return $student;
    }

    public function dashboard()
    {
        $student = $this->getStudent();

        $upcomingAppointments = Appointment::with(['counselor.user', 'service'])
            ->where('student_id', $student->id)
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('consultation_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        $recentHistory = Appointment::with(['counselor.user', 'service'])
            ->where('student_id', $student->id)
            ->whereIn('status', ['completed', 'rejected', 'cancelled', 'no_show'])
            ->orderBy('consultation_date', 'desc')
            ->take(5)
            ->get();

        $pendingFollowUps = FollowUp::with(['counselor.user'])
            ->where('student_id', $student->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('due_date', 'asc')
            ->get();

        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'total' => Appointment::where('student_id', $student->id)->count(),
            'pending' => Appointment::where('student_id', $student->id)->where('status', 'pending')->count(),
            'approved' => Appointment::where('student_id', $student->id)->where('status', 'approved')->count(),
            'completed' => Appointment::where('student_id', $student->id)->where('status', 'completed')->count(),
        ];

        return view('student.dashboard', compact(
            'student',
            'upcomingAppointments',
            'recentHistory',
            'pendingFollowUps',
            'notifications',
            'stats'
        ));
    }

    public function createAppointment(Request $request)
    {
        $student = $this->getStudent();
        $services = CounselingService::where('is_active', true)->get();
        $counselors = Counselor::with('user')->where('status', 'available')->get();

        $selectedService = $request->query('service_id');
        $selectedCounselor = $request->query('counselor_id');

        // Fetch available schedules for the chosen counselor (or all active schedules >= today)
        $availableSchedules = [];
        if ($selectedCounselor) {
            $availableSchedules = CounselorSchedule::where('counselor_id', $selectedCounselor)
                ->where('is_booked', false)
                ->where('is_active', true)
                ->where('date', '>=', Carbon::today())
                ->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc')
                ->get();
        }

        return view('student.create_appointment', compact(
            'services',
            'counselors',
            'selectedService',
            'selectedCounselor',
            'availableSchedules'
        ));
    }

    public function storeAppointment(Request $request)
    {
        $student = $this->getStudent();

        $validated = $request->validate([
            'service_id' => 'required|exists:counseling_services,id',
            'counselor_id' => 'required|exists:counselors,id',
            'schedule_id' => 'nullable|exists:counselor_schedules,id',
            'consultation_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'method' => 'required|in:offline,online',
            'initial_concern' => 'required|string|min:15|max:1500',
        ]);

        return DB::transaction(function () use ($validated, $student) {
            $schedule = null;
            if (!empty($validated['schedule_id'])) {
                // Double booking prevention using row-level locking
                $schedule = CounselorSchedule::where('id', $validated['schedule_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$schedule || $schedule->is_booked || !$schedule->is_active) {
                    return back()->withErrors(['schedule_id' => 'Maaf, slot jadwal yang Anda pilih baru saja dipesan oleh pengguna lain atau sudah tidak tersedia. Silakan pilih slot lain.'])->withInput();
                }
            } else {
                // Check if counselor already has an approved/pending appointment at same date & time
                $conflict = Appointment::where('counselor_id', $validated['counselor_id'])
                    ->where('consultation_date', $validated['consultation_date'])
                    ->where('start_time', $validated['start_time'])
                    ->whereIn('status', ['pending', 'approved', 'in_progress'])
                    ->lockForUpdate()
                    ->exists();

                if ($conflict) {
                    return back()->withErrors(['consultation_date' => 'Jadwal pada jam tersebut sudah terisi. Silakan pilih waktu yang lain.'])->withInput();
                }
            }

            // Generate unique appointment code: BK-YYYYMMDD-RANDOM
            $code = 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $appointment = Appointment::create([
                'appointment_code' => $code,
                'student_id' => $student->id,
                'counselor_id' => $validated['counselor_id'],
                'service_id' => $validated['service_id'],
                'schedule_id' => $schedule ? $schedule->id : null,
                'consultation_date' => $validated['consultation_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'method' => $validated['method'],
                'initial_concern' => $validated['initial_concern'],
                'status' => 'pending',
            ]);

            if ($schedule) {
                $schedule->update(['is_booked' => true]);
            }

            // In-app notification to counselor
            $counselor = Counselor::with('user')->find($validated['counselor_id']);
            if ($counselor && $counselor->user) {
                Notification::create([
                    'user_id' => $counselor->user->id,
                    'title' => 'Permohonan Konseling Baru (' . $code . ')',
                    'message' => 'Mahasiswa ' . $student->user->name . ' mengajukan jadwal konseling untuk tanggal ' . Carbon::parse($validated['consultation_date'])->translatedFormat('d F Y') . '.',
                    'type' => 'info',
                    'link' => route('counselor.appointment.detail', $appointment->id),
                ]);
            }

            AuditLog::log('create_appointment', 'Appointment', $appointment->id);

            return redirect()->route('student.appointments')->with('success', 'Permohonan konseling berhasil dikirim! Nomor referensi Anda: ' . $code);
        });
    }

    public function appointments(Request $request)
    {
        $student = $this->getStudent();
        $status = $request->query('status');

        $query = Appointment::with(['counselor.user', 'service'])
            ->where('student_id', $student->id)
            ->orderBy('consultation_date', 'desc')
            ->orderBy('start_time', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $appointments = $query->paginate(10);

        return view('student.appointments', compact('appointments', 'status'));
    }

    public function appointmentDetail($id)
    {
        $student = $this->getStudent();

        // Strict IDOR protection: only accessible if appointment belongs to current student
        $appointment = Appointment::with(['counselor.user', 'service', 'session.followUps'])
            ->where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        return view('student.appointment_detail', compact('appointment'));
    }

    public function cancelAppointment(Request $request, $id)
    {
        $student = $this->getStudent();

        $appointment = Appointment::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if (!in_array($appointment->status, ['pending', 'approved'])) {
            return back()->with('error', 'Permohonan dengan status ini tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($appointment, $request) {
            $appointment->update([
                'status' => 'cancelled',
                'counselor_response_reason' => 'Dibatalkan oleh mahasiswa: ' . ($request->reason ?? 'Tidak ada alasan khusus.'),
            ]);

            // Release schedule slot if any
            if ($appointment->schedule_id) {
                CounselorSchedule::where('id', $appointment->schedule_id)->update(['is_booked' => false]);
            }

            AuditLog::log('cancel_appointment', 'Appointment', $appointment->id);
        });

        return back()->with('success', 'Permohonan konseling berhasil dibatalkan.');
    }

    public function followUps()
    {
        $student = $this->getStudent();

        $followUps = FollowUp::with(['counselor.user', 'session.appointment.service'])
            ->where('student_id', $student->id)
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        return view('student.follow_ups', compact('followUps'));
    }

    public function updateFollowUpNotes(Request $request, $id)
    {
        $student = $this->getStudent();

        $followUp = FollowUp::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $validated = $request->validate([
            'student_notes' => 'required|string|max:1000',
            'status' => 'nullable|in:pending,in_progress,completed',
        ]);

        $followUp->update([
            'student_notes' => $validated['student_notes'],
            'status' => $validated['status'] ?? $followUp->status,
        ]);

        return back()->with('success', 'Catatan tindak lanjut berhasil diperbarui.');
    }
}
