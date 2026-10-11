# KEBUTUHAN SISTEM (REQUIREMENTS) — SAPA-BK (Siswa & Guru BK)

## 1. Kebutuhan Fungsional (Functional Requirements)

| Kode | Kebutuhan | Deskripsi | Peran |
|---|---|---|---|
| FR-01 | Autentikasi Pengguna | Login, logout, registrasi mandiri siswa (NISN, kelas/rombel, peminatan), verifikasi password terenkripsi, proteksi multi-login | Semua |
| FR-02 | Manajemen Profil | Mengubah kontak, foto, informasi kelas bagi siswa, dan bidang keahlian/ruang BK bagi Guru BK | Siswa, Guru BK |
| FR-03 | Katalog Layanan Bimbingan | Menampilkan daftar layanan bimbingan sekolah (Belajar, Pribadi, Karir & Studi Lanjut, Sosial) | Publik, Siswa |
| FR-04 | Manajemen Slot Jadwal | Guru BK mengatur ketersediaan tanggal & jam konseling di sekolah, status aktif/tutup | Guru BK |
| FR-05 | Pengajuan Permohonan | Siswa memilih layanan, metode (tatap muka di Ruang BK / daring), slot jadwal, dan deskripsi kebutuhan awal | Siswa |
| FR-06 | Konfirmasi & Disposisi | Guru BK dapat Menyetujui (Disetujui), Menolak (dengan alasan edukatif), atau Mengajukan Penjadwalan Ulang | Guru BK |
| FR-07 | Pencegahan Double-Booking | Sistem backend memblokir dua siswa mendaftar pada slot waktu Guru BK yang sama secara atomik | Sistem |
| FR-08 | Pelaksanaan Sesi Konseling | Guru BK mencatat kehadiran (Hadir, Tidak Hadir/No-Show), waktu mulai & selesai sesi | Guru BK |
| FR-09 | Catatan Konseling Rahasia | Penyimpanan catatan sesi profesional dalam tabel terpisah dengan kontrol otorisasi tingkat data | Guru BK Berwenang |
| FR-10 | Rencana Tindak Lanjut | Penyusunan langkah tindak lanjut (Action Plan) dengan batas waktu dan status realisasi | Guru BK, Siswa |
| FR-11 | Riwayat & Rekap Kasus | Siswa melihat riwayat pengajuan mandiri; Guru BK melihat riwayat kasus binaannya | Siswa, Guru BK |
| FR-12 | Statistik & Laporan Operasional | Dashboard visual agregat (jumlah sesi, status, kategori terbanyak, filter periode) | Guru BK, Admin |
| FR-13 | Audit Log Keamanan | Pencatatan otomatis aktivitas sensitif (akses catatan, perubahan status permohonan) | Administrator |

---

## 2. Kebutuhan Nonfungsional (Non-Functional Requirements)

1. **Keamanan (Security):**
   - Hashing Bcrypt/Argon2.
   - Server-side authorization check (Laravel Gates/Policies) pada setiap request endpoint data.
   - Proteksi XSS (Blade auto-escaping), CSRF token verification, and SQL Injection prevention via Eloquent ORM parameterized queries.
2. **Kerahasiaan Data (Data Privacy):**
   - Siswa A diisolasi penuh dari data Siswa B.
   - Catatan konseling internal terisolasi dari Siswa dan Administrator.
3. **Estetika & Pengalaman Pengguna (UI/UX):**
   - Menggunakan estetika modern Tailwind CSS bertema Material Design (Surface Cards, elevation shadows, Google Roboto typography, Material Symbols Icons, palet warna indigo/teal).
   - Indikator status berupa teks eksplisit dan chip badge berwarna kontras ramah aksesibilitas.
   - Tampilan responsif sempurna pada ponsel pintar (mobile) maupun laptop/desktop.
4. **Kinerja & Kompatibilitas Server:**
   - Kompatibel penuh dengan lingkungan server Laragon lokal (Apache 2.4 + PHP 8.1 + MySQL 8).
