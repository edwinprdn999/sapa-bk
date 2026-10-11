<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\CounselingService;
use App\Models\Counselor;
use App\Models\CounselorSchedule;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SapaBkSecurityAndBookingTest extends TestCase
{
    use DatabaseTransactions;

    private User $studentUser1;
    private User $studentUser2;
    private User $counselorUser1;
    private User $counselorUser2;
    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studentUser1 = User::where('email', 'siswa.dimas@sapa-bk.sch.id')->first()
            ?? User::where('email', 'mahasiswa.dimas@sapa-bk.ac.id')->first();
        $this->studentUser2 = User::where('email', 'siswa.anisa@sapa-bk.sch.id')->first()
            ?? User::where('email', 'mahasiswa.anisa@sapa-bk.ac.id')->first();
        $this->counselorUser1 = User::where('email', 'gurubk.sarah@sapa-bk.sch.id')->first()
            ?? User::where('email', 'konselor.sarah@sapa-bk.ac.id')->first();
        $this->counselorUser2 = User::where('email', 'gurubk.budi@sapa-bk.sch.id')->first()
            ?? User::where('email', 'konselor.budi@sapa-bk.ac.id')->first();
        $this->adminUser = User::where('email', 'admin@sapa-bk.sch.id')->first()
            ?? User::where('email', 'admin@sapa-bk.ac.id')->first();
    }

    /**
     * TC-SEC-01: Mahasiswa A tidak dapat melihat/mengakses permohonan Mahasiswa B (IDOR)
     */
    public function test_student_cannot_access_other_students_appointment(): void
    {
        // Temukan appointment milik Dimas (student 1)
        $aptDimas = Appointment::where('student_id', $this->studentUser1->student->id)->first();

        // Login sebagai Anisa (student 2) dan coba akses detail permohonan Dimas
        $response = $this->actingAs($this->studentUser2)->get('/student/appointments/' . $aptDimas->id);

        // Harus 404 (firstOrFail dengan student_id scope) atau 403
        $response->assertStatus(404);
    }

    /**
     * TC-SEC-02: Mahasiswa dilarang keras mengakses endpoint catatan rahasia sesi konselor
     */
    public function test_student_cannot_access_counselor_session_notes(): void
    {
        $apt = Appointment::first();

        $response = $this->actingAs($this->studentUser1)->get('/counselor/appointments/' . $apt->id . '/session');

        $response->assertStatus(403);
    }

    /**
     * TC-SEC-03: Konselor hanya dapat membuka permohonan yang ditugaskan kepadanya
     */
    public function test_counselor_cannot_access_other_counselors_appointment(): void
    {
        // Temukan appointment yang ditugaskan ke Konselor 1
        $aptSarah = Appointment::where('counselor_id', $this->counselorUser1->counselor->id)->first();

        // Login sebagai Konselor 2 (Budi) dan coba akses
        $response = $this->actingAs($this->counselorUser2)->get('/counselor/appointments/' . $aptSarah->id);

        $response->assertStatus(404);
    }

    /**
     * TC-SEC-05: Pengguna tanpa login dialihkan ke halaman login
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/student/dashboard');
        $response->assertRedirect('/login');

        $response2 = $this->get('/counselor/dashboard');
        $response2->assertRedirect('/login');

        $response3 = $this->get('/admin/dashboard');
        $response3->assertRedirect('/login');
    }

    /**
     * TC-BIZ-01: Pencegahan double booking jika slot jadwal yang sama dipesan ulang
     */
    public function test_double_booking_prevention_on_same_slot(): void
    {
        $service = CounselingService::first();
        $counselor = $this->counselorUser1->counselor;

        // Buat slot jadwal baru
        $schedule = CounselorSchedule::create([
            'counselor_id' => $counselor->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '11:00:00',
            'end_time' => '12:00:00',
            'is_booked' => false,
            'is_active' => true,
        ]);

        // Pemesanan pertama oleh Mahasiswa 1
        $payload = [
            'service_id' => $service->id,
            'counselor_id' => $counselor->id,
            'schedule_id' => $schedule->id,
            'consultation_date' => $schedule->date->format('Y-m-d'),
            'start_time' => '11:00',
            'end_time' => '12:00',
            'method' => 'offline',
            'initial_concern' => 'Uji coba booking slot konseling pertama berhasil.',
        ];

        $response1 = $this->actingAs($this->studentUser1)->post('/student/appointments', $payload);
        $response1->assertRedirect('/student/appointments');

        // Slot harus menjadi is_booked = true
        $schedule->refresh();
        $this->assertTrue($schedule->is_booked);

        // Pemesanan kedua oleh Mahasiswa 2 pada slot yang sama
        $response2 = $this->actingAs($this->studentUser2)->post('/student/appointments', $payload);
        $response2->assertSessionHasErrors('schedule_id');
    }

    /**
     * TC-BIZ-03: Penolakan wajib menyertakan alasan edukatif
     */
    public function test_counselor_cannot_reject_without_reason(): void
    {
        // Cari appointment pending milik Konselor 2
        $apt = Appointment::where('counselor_id', $this->counselorUser2->counselor->id)
            ->where('status', 'pending')
            ->first();

        if ($apt) {
            $response = $this->actingAs($this->counselorUser2)->post('/counselor/appointments/' . $apt->id . '/status', [
                'action' => 'reject',
                'reason' => '', // Kosong
            ]);

            $response->assertSessionHasErrors('reason');
        }
    }

    /**
     * TC-BIZ-04: Mahasiswa dapat membatalkan permohonan pending miliknya
     */
    public function test_student_can_cancel_own_pending_appointment(): void
    {
        $service = CounselingService::first();
        $counselor = $this->counselorUser2->counselor;

        // Buat appointment pending baru
        $apt = Appointment::create([
            'appointment_code' => 'BK-TEST-' . rand(1000, 9999),
            'student_id' => $this->studentUser1->student->id,
            'counselor_id' => $counselor->id,
            'service_id' => $service->id,
            'consultation_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
            'method' => 'offline',
            'initial_concern' => 'Permohonan uji pembatalan mandiri oleh mahasiswa.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->studentUser1)->post('/student/appointments/' . $apt->id . '/cancel', [
            'reason' => 'Ada bentrok jadwal kegiatan sekolah.',
        ]);

        $response->assertSessionHas('success');

        $apt->refresh();
        $this->assertEquals('cancelled', $apt->status);
    }

    /**
     * TC-REG-01: Pendaftaran Siswa Baru dengan NISN dan Rombel/Kelas
     */
    public function test_school_student_registration_creates_student_profile(): void
    {
        $uniqueNisn = '008' . rand(1000000, 9999999);
        $payload = [
            'name' => 'Fajar Pratama',
            'email' => 'siswa.fajar.' . rand(100, 999) . '@sapa-bk.sch.id',
            'student_number' => $uniqueNisn,
            'study_program' => 'X RPL 2',
            'faculty' => 'Teknik Komputer dan Informatika',
            'entry_year' => 2024,
            'phone' => '081299988877',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $payload);
        $response->assertRedirect('/student/dashboard');

        $this->assertDatabaseHas('students', [
            'student_number' => $uniqueNisn,
            'study_program' => 'X RPL 2',
        ]);
    }

    /**
     * TC-SEC-06: Administrator dilarang keras mengakses endpoint catatan rahasia sesi konseling
     */
    public function test_admin_cannot_access_counselor_session_notes(): void
    {
        $apt = Appointment::first();
        $response = $this->actingAs($this->adminUser)->get('/counselor/appointments/' . $apt->id . '/session');

        $response->assertStatus(403);
    }
}
