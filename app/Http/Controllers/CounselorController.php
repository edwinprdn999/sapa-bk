<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\CounselingNote;
use App\Models\CounselingSession;
use App\Models\Counselor;
use App\Models\CounselorSchedule;
use App\Models\FollowUp;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CounselorController extends Controller
{
    private function getCounselor()
    {
        $counselor = Auth::user()->counselor;
        if (!$counselor) {
            abort(403, 'Profil konselor tidak ditemukan.');
        }
        return $counselor;
    }

    public function dashboard()
    {
        $counselor = $this->getCounselor();
        $today = Carbon::today();

        $todayAppointments = Appointment::with(['student.user', 'service'])
            ->where('counselor_id', $counselor->id)
            ->where('consultation_date', $today->format('Y-m-d'))
            ->whereIn('status', ['approved', 'in_progress'])
            ->orderBy('start_time', 'asc')
            ->get();

        $pendingRequests = Appointment::with(['student.user', 'service'])
            ->where('counselor_id', $counselor->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        $stats = [
            'pending_count' => Appointment::where('counselor_id', $counselor->id)->where('status', 'pending')->count(),
            'today_count' => Appointment::where('counselor_id', $counselor->id)->where('consultation_date', $today->format('Y-m-d'))->where('status', 'approved')->count(),
            'completed_count' => Appointment::where('counselor_id', $counselor->id)->where('status', 'completed')->count(),
            'total_clients' => Appointment::where('counselor_id', $counselor->id)->distinct('student_id')->count('student_id'),
        ];

        $pendingFollowUps = FollowUp::with(['student.user'])
            ->where('counselor_id', $counselor->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        return view('counselor.dashboard', compact(
            'counselor',
            'todayAppointments',
            'pendingRequests',
            'stats',
            'pendingFollowUps'
        ));
    }

    public function schedules()
    {
        $counselor = $this->getCounselor();

        $schedules = CounselorSchedule::where('counselor_id', $counselor->id)
            ->where('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        return view('counselor.schedules', compact('schedules'));
    }

    public function storeSchedule(Request $request)
    {
        $counselor = $this->getCounselor();

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $exists = CounselorSchedule::where('counselor_id', $counselor->id)
            ->where('date', $validated['date'])
            ->where('start_time', $validated['start_time'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['start_time' => 'Slot waktu pada jam tersebut sudah pernah dibuat sebelumnya.'])->withInput();
        }

        CounselorSchedule::create([
            'counselor_id' => $counselor->id,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_booked' => false,
            'is_active' => true,
        ]);

        return back()->with('success', 'Slot jadwal berhasil ditambahkan.');
    }

    public function deleteSchedule($id)
    {
        $counselor = $this->getCounselor();

        $schedule = CounselorSchedule::where('id', $id)
            ->where('counselor_id', $counselor->id)
            ->firstOrFail();

        if ($schedule->is_booked) {
            return back()->with('error', 'Slot jadwal tidak dapat dihapus karena sudah dipesan mahasiswa.');
        }

        $schedule->delete();
        return back()->with('success', 'Slot jadwal berhasil dihapus.');
    }

    public function appointments(Request $request)
    {
        $counselor = $this->getCounselor();
        $status = $request->query('status');

        $query = Appointment::with(['student.user', 'service'])
            ->where('counselor_id', $counselor->id)
            ->orderBy('consultation_date', 'desc')
            ->orderBy('start_time', 'asc');

        if ($status) {
            $query->where('status', $status);
        }

        $appointments = $query->paginate(10);

        return view('counselor.appointments', compact('appointments', 'status'));
    }

    public function appointmentDetail($id)
    {
        $counselor = $this->getCounselor();

        // Strict authorization: only counselor assigned to this appointment
        $appointment = Appointment::with(['student.user', 'service', 'session.confidentialNote', 'session.followUps'])
            ->where('id', $id)
            ->where('counselor_id', $counselor->id)
            ->firstOrFail();

        return view('counselor.appointment_detail', compact('appointment'));
    }

    public function updateAppointmentStatus(Request $request, $id)
    {
        $counselor = $this->getCounselor();

        $appointment = Appointment::where('id', $id)
            ->where('counselor_id', $counselor->id)
            ->firstOrFail();

        $validated = $request->validate([
            'action' => 'required|in:approve,reject,reschedule',
            'reason' => 'nullable|string|max:1000',
            'meeting_link_or_room' => 'nullable|string|max:255',
        ]);

        if ($validated['action'] === 'reject' && empty($validated['reason'])) {
            return back()->withErrors(['reason' => 'Alasan penolakan wajib diisi untuk edukasi dan kejelasan mahasiswa.']);
        }

        DB::transaction(function () use ($appointment, $validated) {
            if ($validated['action'] === 'approve') {
                $appointment->update([
                    'status' => 'approved',
                    'counselor_response_reason' => $validated['reason'] ?? 'Permohonan disetujui oleh konselor.',
                    'meeting_link_or_room' => $validated['meeting_link_or_room'] ?? ($appointment->method === 'online' ? 'Link Google Meet akan diinfokan sebelum sesi' : 'Ruang BK Konseling'),
                ]);

                // Create placeholder CounselingSession
                CounselingSession::firstOrCreate([
                    'appointment_id' => $appointment->id,
                ], [
                    'counselor_id' => $appointment->counselor_id,
                    'student_id' => $appointment->student_id,
                    'attendance_status' => 'attended',
                ]);

                // Notify student
                Notification::create([
                    'user_id' => $appointment->student->user_id,
                    'title' => 'Permohonan Konseling Disetujui',
                    'message' => 'Permohonan konseling Anda (' . $appointment->appointment_code . ') telah disetujui.',
                    'type' => 'success',
                    'link' => route('student.appointment.detail', $appointment->id),
                ]);

            } elseif ($validated['action'] === 'reject') {
                $appointment->update([
                    'status' => 'rejected',
                    'counselor_response_reason' => $validated['reason'],
                ]);

                // Release schedule if any
                if ($appointment->schedule_id) {
                    CounselorSchedule::where('id', $appointment->schedule_id)->update(['is_booked' => false]);
                }

                Notification::create([
                    'user_id' => $appointment->student->user_id,
                    'title' => 'Pemberitahuan Permohonan Konseling',
                    'message' => 'Permohonan konseling Anda (' . $appointment->appointment_code . ') belum dapat disetujui. Alasan: ' . $validated['reason'],
                    'type' => 'warning',
                    'link' => route('student.appointment.detail', $appointment->id),
                ]);

            } elseif ($validated['action'] === 'reschedule') {
                $appointment->update([
                    'status' => 'reschedule_requested',
                    'counselor_response_reason' => $validated['reason'] ?? 'Konselor menyarankan penjadwalan ulang.',
                ]);

                if ($appointment->schedule_id) {
                    CounselorSchedule::where('id', $appointment->schedule_id)->update(['is_booked' => false]);
                }

                Notification::create([
                    'user_id' => $appointment->student->user_id,
                    'title' => 'Permintaan Penjadwalan Ulang',
                    'message' => 'Konselor meminta penjadwalan ulang untuk permohonan (' . $appointment->appointment_code . ').',
                    'type' => 'info',
                    'link' => route('student.appointment.detail', $appointment->id),
                ]);
            }

            AuditLog::log('status_change_' . $validated['action'], 'Appointment', $appointment->id);
        });

        return back()->with('success', 'Status permohonan berhasil diperbarui.');
    }

    public function sessionView($appointmentId)
    {
        $counselor = $this->getCounselor();

        $appointment = Appointment::with(['student.user', 'service', 'session.confidentialNote', 'session.followUps'])
            ->where('id', $appointmentId)
            ->where('counselor_id', $counselor->id)
            ->firstOrFail();

        $session = $appointment->session ?? CounselingSession::create([
            'appointment_id' => $appointment->id,
            'counselor_id' => $counselor->id,
            'student_id' => $appointment->student_id,
            'attendance_status' => 'attended',
        ]);

        // Audit log for accessing sensitive session/confidential record
        AuditLog::log('access_counseling_session', 'CounselingSession', $session->id);

        return view('counselor.session', compact('appointment', 'session'));
    }

    public function updateSession(Request $request, $sessionId)
    {
        $counselor = $this->getCounselor();

        $session = CounselingSession::with('appointment')
            ->where('id', $sessionId)
            ->where('counselor_id', $counselor->id)
            ->firstOrFail();

        $validated = $request->validate([
            'attendance_status' => 'required|in:attended,no_show',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date',
            'public_summary' => 'nullable|string|max:2000',
            // Confidential note fields (Strictly Protected)
            'confidential_note' => 'required|string|min:10',
            'evaluation_summary' => 'nullable|string|max:1000',
            // Follow-up fields
            'create_follow_up' => 'nullable|boolean',
            'action_plan' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($session, $validated) {
            $session->update([
                'attendance_status' => $validated['attendance_status'],
                'started_at' => $validated['started_at'] ?? now(),
                'ended_at' => $validated['ended_at'] ?? now(),
                'public_summary' => $validated['public_summary'] ?? null,
            ]);

            // Save strictly protected confidential note in isolated table
            CounselingNote::updateOrCreate(
                ['session_id' => $session->id],
                [
                    'author_id' => Auth::id(),
                    'confidential_note' => $validated['confidential_note'],
                    'evaluation_summary' => $validated['evaluation_summary'] ?? null,
                    'is_encrypted' => false,
                ]
            );

            // Update parent appointment status
            $newStatus = $validated['attendance_status'] === 'no_show' ? 'no_show' : 'completed';
            $session->appointment->update(['status' => $newStatus]);

            // Create follow-up if requested
            if (!empty($validated['create_follow_up']) && !empty($validated['action_plan'])) {
                FollowUp::create([
                    'session_id' => $session->id,
                    'student_id' => $session->student_id,
                    'counselor_id' => $session->counselor_id,
                    'action_plan' => $validated['action_plan'],
                    'due_date' => $validated['due_date'] ?? null,
                    'status' => 'pending',
                ]);

                Notification::create([
                    'user_id' => $session->student->user_id,
                    'title' => 'Rencana Tindak Lanjut Konseling',
                    'message' => 'Konselor telah menambahkan rencana tindak lanjut untuk Anda.',
                    'type' => 'info',
                    'link' => route('student.follow_ups'),
                ]);
            }

            AuditLog::log('update_counseling_session_notes', 'CounselingSession', $session->id);
        });

        return redirect()->route('counselor.appointment.detail', $session->appointment_id)
            ->with('success', 'Catatan sesi dan tindak lanjut berhasil disimpan secara aman.');
    }
}
