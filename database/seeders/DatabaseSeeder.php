<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\CounselingNote;
use App\Models\CounselingService;
use App\Models\CounselingSession;
use App\Models\Counselor;
use App\Models\CounselorSchedule;
use App\Models\FollowUp;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles (Siswa, Guru BK, Administrator)
        $roleAdmin = Role::create([
            'name' => 'administrator',
            'display_name' => 'Administrator Sekolah',
            'description' => 'Pengelola teknis konfigurasi akun dan laporan operasional bimbingan konseling',
        ]);

        $roleGuruBk = Role::create([
            'name' => 'guru_bk',
            'display_name' => 'Guru BK',
            'description' => 'Guru Bimbingan dan Konseling pengelola layanan konsultasi dan pendampingan siswa',
        ]);

        $roleSiswa = Role::create([
            'name' => 'siswa',
            'display_name' => 'Siswa',
            'description' => 'Siswa sekolah yang memanfaatkan layanan bimbingan konseling',
        ]);

        // 2. Layanan Bimbingan & Konseling Sekolah
        $services = [
            [
                'name' => 'Bimbingan Belajar & Kesulitan Belajar',
                'slug' => 'bimbingan-belajar',
                'category' => 'Belajar',
                'description' => 'Membantu siswa mengatasi kesulitan memahami pelajaran di kelas, penurunan nilai rapor, manajemen waktu belajar, dan persiapan menghadapi ujian sekolah/tryout.',
                'icon' => 'school',
                'is_active' => true,
            ],
            [
                'name' => 'Bimbingan Pribadi & Regulasi Emosi Remaja',
                'slug' => 'bimbingan-pribadi',
                'category' => 'Pribadi & Emosional',
                'description' => 'Ruang aman dan rahasia bagi siswa untuk menceritakan rasa cemas, kejenuhan belajar, penyesuaian diri masa remaja, serta menumbuhkan rasa percaya diri.',
                'icon' => 'psychology',
                'is_active' => true,
            ],
            [
                'name' => 'Bimbingan Karir & Studi Lanjut (Kuliah/Kerja)',
                'slug' => 'bimbingan-karir',
                'category' => 'Karir & Studi Lanjut',
                'description' => 'Pendampingan dalam eksplorasi minat bakat, konsultasi pemilihan jurusan perguruan tinggi (SNBP/SNBT/Kedinasan), serta persiapan magang/kerja.',
                'icon' => 'work',
                'is_active' => true,
            ],
            [
                'name' => 'Bimbingan Sosial & Hubungan Teman Sebaya',
                'slug' => 'bimbingan-sosial',
                'category' => 'Sosial',
                'description' => 'Pendampingan dalam menjaga keharmonisan pergaulan teman sekelas, resolusi konflik antarsiswa, dan edukasi pencegahan perundungan (anti-bullying).',
                'icon' => 'groups',
                'is_active' => true,
            ],
        ];

        $createdServices = [];
        foreach ($services as $srv) {
            $createdServices[] = CounselingService::create($srv);
        }

        // 3. User & Profile Admin Sekolah
        User::create([
            'role_id' => $roleAdmin->id,
            'name' => 'Admin SAPA-BK Sekolah',
            'email' => 'admin@sapa-bk.sch.id',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'status' => 'active',
        ]);

        // 4. User & Profile Guru BK
        $userG1 = User::create([
            'role_id' => $roleGuruBk->id,
            'name' => 'Dra. Hj. Sarah Amelia, M.Pd., Kons.',
            'email' => 'gurubk.sarah@sapa-bk.sch.id',
            'password' => Hash::make('password123'),
            'phone' => '081198765432',
            'status' => 'active',
        ]);

        $counselor1 = Counselor::create([
            'user_id' => $userG1->id,
            'employee_number' => '198504122010122001', // NIP
            'specialization' => 'Bimbingan Pribadi-Sosial & Regulasi Emosi Remaja',
            'room_location' => 'Ruang BK Gedung Utama Lt. 1',
            'bio' => 'Guru BK bersertifikasi konselor profesional berpengalaman dalam pendampingan psikososial siswa, pengembangan disiplin positif, dan komunikasi empatik.',
            'status' => 'available',
        ]);

        $userG2 = User::create([
            'role_id' => $roleGuruBk->id,
            'name' => 'Budi Santoso, S.Pd., Gr.',
            'email' => 'gurubk.budi@sapa-bk.sch.id',
            'password' => Hash::make('password123'),
            'phone' => '081377889900',
            'status' => 'active',
        ]);

        $counselor2 = Counselor::create([
            'user_id' => $userG2->id,
            'employee_number' => '198207192008011003', // NUPTK
            'specialization' => 'Bimbingan Karir, Minat Bakat & Studi Lanjut Perguruan Tinggi',
            'room_location' => 'Ruang BK Sayap Barat',
            'bio' => 'Fokus mendampingi siswa dalam asesmen potensi diri, pemetaan jurusan kuliah/karir kerja masa depan, dan motivasi belajar efektif.',
            'status' => 'available',
        ]);

        // 5. User & Profile Siswa
        $userS1 = User::create([
            'role_id' => $roleSiswa->id,
            'name' => 'Dimas Pratama',
            'email' => 'siswa.dimas@sapa-bk.sch.id',
            'password' => Hash::make('password123'),
            'phone' => '082155443322',
            'status' => 'active',
        ]);

        $student1 = Student::create([
            'user_id' => $userS1->id,
            'student_number' => '0061234567', // NISN
            'study_program' => 'Kelas XI RPL 1', // Kelas
            'faculty' => 'Rekayasa Perangkat Lunak', // Jurusan / Peminatan
            'academic_year' => '2024/2025',
            'emergency_contact' => '081233445566 (Bpk. Suryanto - Orang Tua)',
        ]);

        $userS2 = User::create([
            'role_id' => $roleSiswa->id,
            'name' => 'Anisa Rahmawati',
            'email' => 'siswa.anisa@sapa-bk.sch.id',
            'password' => Hash::make('password123'),
            'phone' => '085211223344',
            'status' => 'active',
        ]);

        $student2 = Student::create([
            'user_id' => $userS2->id,
            'student_number' => '0079876543', // NISN
            'study_program' => 'Kelas X MIPA 2', // Kelas
            'faculty' => 'MIPA', // Peminatan
            'academic_year' => '2024/2025',
            'emergency_contact' => '085299887766 (Ibu Fatimah - Wali)',
        ]);

        // 6. Jadwal Ketersediaan Slot Guru BK
        $today = Carbon::today();
        $schedules = [
            // Guru BK Sarah - Hari Ini
            [
                'counselor_id' => $counselor1->id,
                'date' => $today->copy()->format('Y-m-d'),
                'start_time' => '08:30:00',
                'end_time' => '09:30:00',
                'is_booked' => true,
                'is_active' => true,
            ],
            [
                'counselor_id' => $counselor1->id,
                'date' => $today->copy()->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'is_booked' => true,
                'is_active' => true,
            ],
            [
                'counselor_id' => $counselor1->id,
                'date' => $today->copy()->format('Y-m-d'),
                'start_time' => '13:00:00',
                'end_time' => '14:00:00',
                'is_booked' => false,
                'is_active' => true,
            ],
            // Guru BK Sarah - Besok
            [
                'counselor_id' => $counselor1->id,
                'date' => $today->copy()->addDays(1)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'is_booked' => false,
                'is_active' => true,
            ],
            // Guru BK Budi - Hari Ini
            [
                'counselor_id' => $counselor2->id,
                'date' => $today->copy()->format('Y-m-d'),
                'start_time' => '09:30:00',
                'end_time' => '10:30:00',
                'is_booked' => false,
                'is_active' => true,
            ],
            [
                'counselor_id' => $counselor2->id,
                'date' => $today->copy()->format('Y-m-d'),
                'start_time' => '13:30:00',
                'end_time' => '14:30:00',
                'is_booked' => false,
                'is_active' => true,
            ],
            // Guru BK Budi - Besok
            [
                'counselor_id' => $counselor2->id,
                'date' => $today->copy()->addDays(1)->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'is_booked' => false,
                'is_active' => true,
            ],
        ];

        $createdSchedules = [];
        foreach ($schedules as $sch) {
            $createdSchedules[] = CounselorSchedule::create($sch);
        }

        // 7. Permohonan Konseling (Appointments)
        // Appointment 1: Selesai (Completed)
        $apt1 = Appointment::create([
            'appointment_code' => 'BK-' . $today->format('Ymd') . '-001',
            'student_id' => $student1->id,
            'counselor_id' => $counselor1->id,
            'service_id' => $createdServices[0]->id, // Bimbingan Belajar
            'schedule_id' => $createdSchedules[0]->id,
            'consultation_date' => $today->copy()->subDays(2)->format('Y-m-d'),
            'start_time' => '08:30:00',
            'end_time' => '09:30:00',
            'method' => 'offline',
            'initial_concern' => 'Merasa kesulitan membagi waktu antara tugas kejuruan coding di sekolah dengan belajar mata pelajaran umum, nilai tryout matematika sempat turun.',
            'status' => 'completed',
            'counselor_response_reason' => 'Disetujui. Silakan hadir di Ruang BK Gedung Utama saat jam istirahat pertama.',
            'meeting_link_or_room' => 'Ruang BK Gedung Utama Lt. 1',
        ]);

        // Sesi Konseling untuk Apt 1
        $session1 = CounselingSession::create([
            'appointment_id' => $apt1->id,
            'counselor_id' => $counselor1->id,
            'student_id' => $student1->id,
            'started_at' => $today->copy()->subDays(2)->setTime(8, 30),
            'ended_at' => $today->copy()->subDays(2)->setTime(9, 25),
            'attendance_status' => 'attended',
            'public_summary' => 'Sesi bimbingan berjalan kondusif. Siswa menyepakati jadwal belajar mandiri 45 menit setiap sore dan membuat skala prioritas tugas sekolah.',
        ]);

        // Catatan Rahasia Guru BK
        CounselingNote::create([
            'session_id' => $session1->id,
            'author_id' => $userG1->id,
            'confidential_note' => 'Siswa menunjukkan motivasi berprestasi tinggi namun rentan overthinking saat tugas menumpuk. Diberikan bimbingan manajemen waktu dan teknik afirmasi positif. Siswa sangat kooperatif.',
            'evaluation_summary' => 'Prognosis sangat baik. Siswa terbuka dan siap mempraktikkan manajemen jadwal harian.',
            'is_encrypted' => false,
        ]);

        // Tindak Lanjut untuk Siswa 1
        FollowUp::create([
            'session_id' => $session1->id,
            'student_id' => $student1->id,
            'counselor_id' => $counselor1->id,
            'action_plan' => 'Menempelkan jadwal belajar prioritas di meja belajar rumah dan membatasi penggunaan gawai maksimal 1 jam sebelum tidur.',
            'due_date' => $today->copy()->addDays(5)->format('Y-m-d'),
            'status' => 'in_progress',
            'student_notes' => 'Sudah mulai membuat jadwal prioritas dan tidur lebih awal.',
        ]);

        // Appointment 2: Disetujui (Approved) - Mendatang
        Appointment::create([
            'appointment_code' => 'BK-' . $today->format('Ymd') . '-002',
            'student_id' => $student1->id,
            'counselor_id' => $counselor1->id,
            'service_id' => $createdServices[1]->id, // Pribadi
            'schedule_id' => $createdSchedules[1]->id,
            'consultation_date' => $today->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'method' => 'offline',
            'initial_concern' => 'Sesi tindak lanjut evaluasi jadwal belajar harian bersama Ibu Sarah.',
            'status' => 'approved',
            'counselor_response_reason' => 'Disetujui untuk sesi follow-up.',
            'meeting_link_or_room' => 'Ruang BK Gedung Utama Lt. 1',
        ]);

        // Appointment 3: Menunggu Konfirmasi (Pending)
        Appointment::create([
            'appointment_code' => 'BK-' . $today->format('Ymd') . '-003',
            'student_id' => $student2->id,
            'counselor_id' => $counselor2->id,
            'service_id' => $createdServices[2]->id, // Karir & Studi Lanjut
            'schedule_id' => null,
            'consultation_date' => $today->copy()->addDays(1)->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'method' => 'offline',
            'initial_concern' => 'Ingin konsultasi pemilihan peminatan jurusan kuliah dan persiapan jalur prestasi SNBP setelah lulus.',
            'status' => 'pending',
            'counselor_response_reason' => null,
            'meeting_link_or_room' => null,
        ]);

        // 8. In-App Notifications
        Notification::create([
            'user_id' => $userS1->id,
            'title' => 'Permohonan Konseling Disetujui',
            'message' => 'Permohonan bimbingan Anda dengan Dra. Hj. Sarah Amelia telah disetujui untuk hari ini pukul 10:00 WIB.',
            'type' => 'success',
            'link' => '/student/appointments',
            'read_at' => null,
        ]);

        Notification::create([
            'user_id' => $userG2->id,
            'title' => 'Permohonan Bimbingan Siswa Baru',
            'message' => 'Siswa Anisa Rahmawati (X MIPA 2) mengajukan permohonan bimbingan karir dan studi lanjut.',
            'type' => 'info',
            'link' => '/counselor/appointments',
            'read_at' => null,
        ]);

        // 9. Audit Logs
        AuditLog::create([
            'user_id' => $userG1->id,
            'action' => 'access_counseling_note',
            'entity_type' => 'CounselingNote',
            'entity_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'created_at' => now(),
        ]);
    }
}
