# KEPUTUSAN DAN ASUMSI AWAL PROYEK SAPA-BK (MVP)

Dokumen ini mencatat asumsi sementara dan keputusan desain awal untuk sistem **SAPA-BK** (Sistem Aplikasi Pelayanan Bimbingan dan Konseling) sesuai dengan panduan pengerjaan Tahap 1.

---

## 1. Pengguna Sasaran (Target Audience) — Revisi Lingkungan Sekolah
- **Sasaran Utama:** **Siswa Sekolah Menengah (SMA / SMK / MA)** dan **Guru Bimbingan dan Konseling (Guru BK)**.
- **Atribut Identitas Siswa:** Menggunakan **NISN** (Nomor Induk Siswa Nasional), **Kelas / Rombel** (misal XI RPL 1, X MIPA 2), dan **Peminatan / Jurusan**.
- **Guru BK:** Menggunakan label fungsional **Guru BK**, data NIP / NUPTK, spesialisasi layanan bimbingan, dan lokasi Ruang BK Sekolah.

## 2. Mekanisme Akun & Otentikasi
- **Registrasi Mandiri:** Siswa dapat mendaftar mandiri dengan email sekolah/pribadi, NISN, kelas/rombel, dan nomor kontak.
- **Akun Guru BK & Administrator:** Dikelola secara administratif/seeder demi menjaga integritas data dan peran fungsional pembimbing.
- **Autentikasi:** Menggunakan Laravel Session Auth dengan RBAC (Role-Based Access Control) multi-level (`siswa` [kompatibel `mahasiswa`], `guru_bk` [kompatibel `konselor`], `administrator`).
- **Keamanan:** Proteksi terhadap brute-force (rate limiting), hashing Argon2/Bcrypt, proteksi CSRF di seluruh form, dan session fixation protection.

## 3. Metode Konseling & Penjadwalan
- **Metode Layanan:** Mendukung 2 moda:
  1. **Tatap Muka (Offline)** di Ruang BK / Konseling Kampus.
  2. **Daring (Online)** via tautan konferensi video (Google Meet / Zoom).
- **Pemilihan Konselor:** Mahasiswa dapat memilih konselor yang tersedia berdasarkan kategori layanan dan slot jadwal terbuka.
- **Pencegahan Bentrok (Double Booking):** Menggunakan validasi server-side dan database constraint/transaction locking pada slot waktu konselor.

## 4. Keamanan dan Kerahasiaan Catatan Konseling (Privacy & Access Control)
- **Tabel Terisolasi:** Catatan sesi sensitif (`counseling_notes`) dipisahkan secara fisik dari tabel jadwal (`appointments`) dan log umum.
- **Hak Akses Ketat:**
  - Mahasiswa **dilarang keras** melihat isi `counseling_notes`. Mahasiswa hanya dapat melihat ringkasan tindak lanjut publik (`follow_ups` yang ditujukan kepadanya).
  - Konselor hanya dapat mengakses catatan konseling dari sesi yang ditanganinya.
  - Administrator mengelola akun dan konfigurasi sistem teknis, **tidak diberikan akses membaca isi catatan konseling rahasia** demi menjaga asas kerahasiaan profesi bimbingan konseling.
- **Audit Logging:** Setiap pembacaan dan pembaruan catatan konseling dicatat ke dalam `audit_logs` tanpa menyalin isi catatan sensitif ke dalam log.

## 5. Notifikasi & Komunikasi
- **MVP Scope:** Notifikasi sistem berbasis in-app notification internal (lonceng notifikasi di dashboard dan alert status).
- **Pesan Bersih:** Pesan notifikasi tidak memuat detail rahasia masalah konseling, hanya memuat status perubahan permohonan dan pengingat waktu sesi.

## 6. Penyesuaian Lingkungan Laragon & Laravel
- **PHP Version:** PHP 8.1.10 (Bawaan Laragon yang aktif dan stabil di laptop).
- **Laravel Framework:** Laravel 10.x LTS (arsitektur modern dengan Controllers, Eloquent ORM, Blade, Vite, Tailwind CSS Material UI).
- **Database:** MySQL 8.0.30 via Laragon (`sapa_bk`).
- **Web Server:** Apache 2.4.54 via Laragon port 80 (`http://localhost/sapa-bk/public`).
