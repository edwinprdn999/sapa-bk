# RANCANGAN BASIS DATA (DATABASE SCHEMA & ERD) — SAPA-BK

Sistem SAPA-BK menggunakan basis data relasional **MySQL 8.0** ternormalisasi dengan integritas referensial (foreign keys), indeks query, dan pemisahan tabel untuk perlindungan privasi.

---

## 1. Daftar Entitas & Tabel

### 1.1 `roles`
Menyimpan peran pengguna sistem.
- `id` (bigint, PK)
- `name` (varchar 50, unique) -> `mahasiswa`, `konselor`, `administrator`
- `display_name` (varchar 100)
- `created_at`, `updated_at`

### 1.2 `users`
Akun login dan profil dasar.
- `id` (bigint, PK)
- `role_id` (bigint, FK -> roles.id)
- `name` (varchar 255)
- `email` (varchar 255, unique)
- `password` (varchar 255)
- `phone` (varchar 30, nullable)
- `avatar` (varchar 255, nullable)
- `status` (enum: 'active', 'inactive') default 'active'
- `remember_token`, `timestamps`

### 1.3 `students`
Data spesifik profil mahasiswa.
- `id` (bigint, PK)
- `user_id` (bigint, FK -> users.id, cascade delete)
- `student_number` (varchar 50, unique / NIM)
- `study_program` (varchar 100)
- `faculty` (varchar 100, nullable)
- `academic_year` (varchar 20)
- `emergency_contact` (varchar 100, nullable)
- `timestamps`

### 1.4 `counselors`
Data spesifik profil konselor / Guru BK.
- `id` (bigint, PK)
- `user_id` (bigint, FK -> users.id, cascade delete)
- `employee_number` (varchar 50, unique / NIP)
- `specialization` (varchar 150)
- `room_location` (varchar 150, nullable)
- `bio` (text, nullable)
- `status` (enum: 'available', 'busy', 'leave') default 'available'
- `timestamps`

### 1.5 `counseling_services`
Katalog layanan konseling yang disediakan lembaga.
- `id` (bigint, PK)
- `name` (varchar 150)
- `slug` (varchar 150, unique)
- `category` (varchar 100) -> 'Akademik', 'Karir', 'Pribadi & Sosial', 'Keluarga'
- `description` (text)
- `icon` (varchar 100, nullable)
- `is_active` (boolean) default true
- `timestamps`

### 1.6 `counselor_schedules`
Slot ketersediaan waktu konselor untuk menerima konsultasi.
- `id` (bigint, PK)
- `counselor_id` (bigint, FK -> counselors.id, cascade delete)
- `date` (date)
- `start_time` (time)
- `end_time` (time)
- `is_booked` (boolean) default false
- `is_active` (boolean) default true
- `timestamps`
- *Unique Constraint: (counselor_id, date, start_time)*

### 1.7 `appointments`
Permohonan pengajuan konseling mahasiswa.
- `id` (bigint, PK)
- `appointment_code` (varchar 30, unique) -> e.g. BK-20261010-001
- `student_id` (bigint, FK -> students.id)
- `counselor_id` (bigint, FK -> counselors.id)
- `service_id` (bigint, FK -> counseling_services.id)
- `schedule_id` (bigint, nullable, FK -> counselor_schedules.id)
- `consultation_date` (date)
- `start_time` (time)
- `end_time` (time)
- `method` (enum: 'offline', 'online')
- `initial_concern` (text)
- `status` (enum: 'pending', 'approved', 'reschedule_requested', 'rejected', 'cancelled', 'in_progress', 'completed', 'no_show')
- `counselor_notes_or_reason` (text, nullable)
- `meeting_link_or_room` (varchar 255, nullable)
- `timestamps`

### 1.8 `counseling_sessions`
Pencatatan sesi pelaksanaan konseling.
- `id` (bigint, PK)
- `appointment_id` (bigint, FK -> appointments.id, unique)
- `counselor_id` (bigint, FK -> counselors.id)
- `student_id` (bigint, FK -> students.id)
- `started_at` (datetime, nullable)
- `ended_at` (datetime, nullable)
- `attendance_status` (enum: 'attended', 'no_show') default 'attended'
- `public_summary` (text, nullable)
- `timestamps`

### 1.9 `counseling_notes` (Strictly Confidential)
Catatan klinis/konseling internal yang dilindungi khusus.
- `id` (bigint, PK)
- `session_id` (bigint, FK -> counseling_sessions.id, unique)
- `author_id` (bigint, FK -> users.id)
- `confidential_note` (text)
- `evaluation_summary` (text, nullable)
- `timestamps`

### 1.10 `follow_ups`
Rencana tindak lanjut paska sesi konseling.
- `id` (bigint, PK)
- `session_id` (bigint, FK -> counseling_sessions.id)
- `student_id` (bigint, FK -> students.id)
- `counselor_id` (bigint, FK -> counselors.id)
- `action_plan` (text)
- `due_date` (date, nullable)
- `status` (enum: 'pending', 'in_progress', 'completed') default 'pending'
- `timestamps`

### 1.11 `notifications`
Pemberitahuan in-app sistem.
- `id` (bigint, PK)
- `user_id` (bigint, FK -> users.id, cascade delete)
- `title` (varchar 255)
- `message` (text)
- `type` (varchar 50)
- `link` (varchar 255, nullable)
- `read_at` (timestamp, nullable)
- `timestamps`

### 1.12 `audit_logs`
Pencatatan riwayat aktivitas operasional penting.
- `id` (bigint, PK)
- `user_id` (bigint, nullable, FK -> users.id)
- `action` (varchar 100)
- `entity_type` (varchar 100)
- `entity_id` (bigint, nullable)
- `ip_address` (varchar 45, nullable)
- `user_agent` (text, nullable)
- `created_at` (timestamp)
