# SAPA-BK — Sistem Aplikasi Pelayanan Bimbingan dan Konseling

**SAPA-BK** adalah platform sistem informasi bimbingan dan konseling berbasis web yang dirancang khusus untuk lingkungan sekolah (Siswa dan Guru BK) guna memfasilitasi siswa dalam mengajukan bimbingan dan konseling secara aman, tertata, dan rahasia, serta membantu Guru BK dalam mengelola jadwal ketersediaan, permohonan konseling, pelaksanaan sesi, catatan bimbingan profesional, dan rencana tindak lanjut.

Sistem dibangun berdasarkan panduan spesifikasi pada [Prompt_Pengerjaan_Website_Bimbingan_Konseling.md](file:///c:/laragon/www/sapa-bk/Prompt_Pengerjaan_Website_Bimbingan_Konseling.md) dan [Proposal_Website_Bimbingan_Konseling.md](file:///c:/laragon/www/sapa-bk/Proposal_Website_Bimbingan_Konseling.md) dengan penyesuaian domain untuk sekolah menengah (SMA/SMK/MA).

---

## 1. Arsitektur & Teknologi

- **Server Lingkungan:** Laragon (Windows)
  - **Web Server:** Apache 2.4.54 (Port 80)
  - **Bahasa Pemrograman:** PHP 8.1.10 (CLI & Apache Module)
  - **Basis Data:** MySQL 8.0.30 (`sapa_bk` on `127.0.0.1:3306`)
- **Framework Backend:** Laravel 10.x LTS (Modern MVC, Eloquent ORM, Gates/Middleware RBAC)
- **Frontend & Styling:** Tailwind CSS v3.4 + Material Design Tokens (Surface elevation, Roboto typography, Google Material Symbols, chip badges, ripple states)
- **Build Tool:** Vite v5

---

## 2. Struktur Deliverables & Dokumentasi Proyek

Dokumentasi arsitektur dan analisis lengkap tersedia di direktori `docs/`:
- [docs/ASSUMPTIONS.md](file:///c:/laragon/www/sapa-bk/docs/ASSUMPTIONS.md) — Keputusan desain dan asumsi sistem sekolah.
- [docs/REQUIREMENTS.md](file:///c:/laragon/www/sapa-bk/docs/REQUIREMENTS.md) — Kebutuhan fungsional (FR-01 s/d FR-13) dan nonfungsional untuk Siswa & Guru BK.
- [docs/USER_FLOWS.md](file:///c:/laragon/www/sapa-bk/docs/USER_FLOWS.md) — Alur pengguna siswa dan guru BK serta state machine status.
- [docs/DATABASE.md](file:///c:/laragon/www/sapa-bk/docs/DATABASE.md) — Kamus data, relasi tabel, indeks, dan pemisahan catatan rahasia.
- [docs/SECURITY.md](file:///c:/laragon/www/sapa-bk/docs/SECURITY.md) — Model RBAC, mitigasi IDOR, CSRF/XSS, dan proteksi kerahasiaan.
- [docs/TEST_PLAN.md](file:///c:/laragon/www/sapa-bk/docs/TEST_PLAN.md) — Matriks pengujian fungsional dan keamanan.

---

## 3. Akun Demo Sintetis (Kredensial Pengujian Siswa & Guru BK)

Aplikasi telah dilengkapi dengan data seeder sintetis sekolah dan fitur tombol **"Isi Cepat Demo Akun"** pada halaman login:

| Peran | Nama Pengguna | Alamat Email | Kata Sandi | Identitas Sekolah |
|---|---|---|---|---|
| **Siswa** | Dimas Pratama | `siswa.dimas@sapa-bk.sch.id` | `password123` | NISN: 0061234567, Kelas: XI RPL 1 |
| **Siswa** | Anisa Rahmawati | `siswa.anisa@sapa-bk.sch.id` | `password123` | NISN: 0079876543, Kelas: X MIPA 2 |
| **Guru BK** | Dra. Hj. Sarah Amelia, M.Pd., Kons. | `gurubk.sarah@sapa-bk.sch.id` | `password123` | NIP: 197805122003122001, Ruang BK 101 |
| **Guru BK** | Budi Santoso, S.Pd., Gr. | `gurubk.budi@sapa-bk.sch.id` | `password123` | NIP: 198503202010011015, Ruang BK 102 |
| **Administrator** | Administrator Sekolah | `admin@sapa-bk.sch.id` | `password123` | Pengelola teknis sistem & operasional |

---

## 4. Cara Instalasi & Menjalankan di Laragon

### Langkah 1: Pengaturan Basis Data & Environment
1. Salin `.env.example` ke `.env` (jika belum ada).
2. Pastikan konfigurasi database di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sapa_bk
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Langkah 2: Migrasi & Seeding Data
Jalankan perintah Artisan menggunakan PHP Laragon:
```powershell
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe artisan migrate:fresh --seed
```

### Langkah 3: Kompilasi Aset Frontend
```powershell
cmd.exe /c "npm run build"
```

### Langkah 4: Akses Aplikasi di Browser
Buka browser dan akses melalui salah satu URL berikut:
- **Melalui Apache Laragon:** [http://localhost/sapa-bk/public/](http://localhost/sapa-bk/public/)
- **Atau menggunakan Virtual Host Laragon:** [http://sapa-bk.test/](http://sapa-bk.test/) (jika diaktifkan di Laragon GUI)
- **Atau menjalankan Artisan Serve:**
  ```powershell
  C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe artisan serve
  ```
  Akses di: `http://127.0.0.1:8000`

---

## 5. Fitur Utama yang Telah Selesai Diimplementasikan

1. **Autentikasi & RBAC Multi-Peran:**
   - Registrasi mandiri siswa dengan NISN, kelas/rombel, dan peminatan/jurusan.
   - Login terproteksi dengan kontrol sesi dan role middleware di server.
2. **Katalog Layanan & Direktori Guru BK:**
   - 4 kategori layanan bimbingan terstruktur (Bimbingan Belajar, Bimbingan Pribadi & Regulasi Emosi Remaja, Bimbingan Karir & Studi Lanjut, Bimbingan Sosial & Anti-Bullying).
   - Profil profesional Guru BK, bidang bimbingan, dan lokasi ruang BK.
3. **Penjadwalan & Pencegahan Double-Booking:**
   - Guru BK dapat membuka slot waktu ketersediaan di sekolah.
   - Siswa memilih slot terbuka sesuai waktu luang belajar.
   - Proteksi transaksi database tingkat baris (`lockForUpdate`) mencegah bentrok jadwal saat konfirmasi bersamaan.
4. **Alur Permohonan & State Machine Lengkap:**
   - Status: `pending`, `approved`, `reschedule_requested`, `rejected`, `cancelled`, `completed`, `no_show`.
   - Validasi alasan edukatif saat Guru BK menolak permohonan.
5. **Pelaksanaan Sesi & Catatan Terproteksi (Confidential Notes):**
   - Absensi kehadiran dan durasi waktu sesi.
   - **Tabel Terisolasi:** Catatan psikologis/pedagogis internal Guru BK disimpan di tabel khusus `counseling_notes` dan dilindungi otorisasi ketat (Siswa dan Administrator ditolak akses).
   - Catatan refleksi publik terpisah untuk dibaca siswa binaan.
6. **Rencana Tindak Lanjut (Follow-Up / Action Plan):**
   - Guru BK menetapkan target tindakan dan batas waktu.
   - Siswa memantau dan mengisi catatan refleksi kemajuan.
7. **Dashboard Analitik & Audit Log:**
   - Metrik agregat tanpa pembocoran rahasia bimbingan.
   - Pencatatan seluruh akses data sensitif di `audit_logs`.

---

## 6. Menjalankan Pengujian Otomatis (Automated Tests)

Jalankan rangkaian tes keamanan dan logika bisnis dengan perintah:
```powershell
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe artisan test
```
Hasil uji memverifikasi:
- Isolasi IDOR antar siswa (Lulus)
- Pemblokiran siswa mengakses catatan rahasia sesi konselor (Lulus)
- Otorisasi kasus Guru BK (Lulus)
- Pencegahan pemesanan ganda / double booking (Lulus)
- Penolakan wajib alasan edukatif (Lulus)
- Pembatalan mandiri siswa (Lulus)
- Registrasi siswa sekolah dengan NISN dan rombel (Lulus)
- Isolasi catatan rahasia dari Administrator (Lulus)
- Proteksi tamu tanpa login (Lulus)
