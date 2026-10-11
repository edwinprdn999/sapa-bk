# PROMPT PENGERJAAN — WEBSITE BIMBINGAN DAN KONSELING

Gunakan dokumen ini sebagai prompt utama untuk AI coding assistant atau tim pengembang. Tujuannya adalah membangun aplikasi web bimbingan dan konseling yang aman, responsif, dan dapat dipelihara. Jangan langsung mengasumsikan keputusan yang belum ditetapkan; identifikasi pertanyaan penting dan tulis asumsi secara eksplisit.

## 1. Peran Anda

Bertindak sebagai **system analyst, UI/UX designer, software architect, database designer, full-stack developer, dan QA engineer**. Kerjakan proyek bertahap: analisis kebutuhan, desain, implementasi, pengujian, dokumentasi, lalu persiapan deployment.

## 2. Tujuan Produk

Bangun Website Bimbingan dan Konseling untuk memfasilitasi:
- Mahasiswa mengajukan konsultasi dan melihat jadwal/status.
- Guru BK/konselor mengelola permohonan, jadwal, sesi, catatan profesional, dan tindak lanjut.
- Institusi melihat laporan operasional agregat sesuai kewenangan.
- Semua data sensitif dilindungi oleh kontrol akses yang benar.

**Dua peran utama:** `mahasiswa` dan `konselor` (label antarmuka dapat menggunakan “Guru BK” setelah dikonfirmasi). Peran `administrator` boleh ditambahkan untuk administrasi teknis, tetapi tidak otomatis boleh membaca catatan konseling.

## 3. Hal yang Harus Dikonfirmasi

Sebelum implementasi penuh, tanyakan atau catat sebagai keputusan terbuka:
1. Targetnya mahasiswa perguruan tinggi atau siswa sekolah?
2. Label resmi peran konselor: Guru BK, dosen pembimbing, atau konselor kampus?
3. Framework dan versi yang dipakai?
4. Database dan lingkungan deployment?
5. Mekanisme akun: registrasi mandiri, aktivasi operator, atau SSO/sistem akademik?
6. Sesi tatap muka, daring, atau keduanya?
7. Apakah mahasiswa memilih konselor atau ditugaskan?
8. Aturan pembatalan, no-show, penjadwalan ulang, dan waktu respons?
9. Siapa yang boleh membaca catatan dan laporan?
10. Apakah email, WhatsApp, video call, PDF/Excel, atau integrasi lain masuk MVP?

Jika pengguna meminta langsung mulai tanpa menjawab semuanya, pilih opsi MVP yang paling sederhana dan aman, tuliskan asumsi dalam `docs/ASSUMPTIONS.md`, dan jangan mengunci arsitektur yang sulit diubah.

## 4. Fitur MVP

### Autentikasi dan profil
- Login/logout, reset kata sandi, status akun, dan profil.
- Role-based access control (RBAC) yang diverifikasi di server.
- Jangan mengandalkan penyembunyian tombol/menu sebagai kontrol keamanan.

### Layanan dan jadwal
- Daftar kategori layanan.
- Profil konselor yang sesuai untuk ditampilkan.
- Konselor dapat mengatur slot jadwal tersedia.
- Zona waktu ditetapkan secara konsisten dan ditampilkan dengan jelas.
- Server mencegah bentrok jadwal, termasuk permintaan bersamaan.

### Pengajuan konseling
- Mahasiswa memilih layanan, metode, slot yang tersedia, dan mengisi formulir awal dengan data minimum.
- Sistem membuat nomor referensi dan status awal `Menunggu Konfirmasi`.
- Konselor dapat menyetujui, menolak dengan alasan yang pantas, atau menawarkan jadwal alternatif.
- Mahasiswa dapat membatalkan atau mengajukan perubahan jadwal sesuai aturan.
- Semua transisi status divalidasi di backend.

### Sesi dan tindak lanjut
- Konselor dapat mencatat kehadiran dan status sesi.
- Catatan profesional disimpan terpisah dari data jadwal dan dilindungi dengan otorisasi ketat.
- Konselor dapat membuat tindak lanjut dengan deskripsi yang sesuai, tenggat, dan status.
- Mahasiswa hanya melihat tindak lanjut yang ditujukan kepadanya, bukan catatan internal.

### Dashboard, notifikasi, dan laporan
- Dashboard mahasiswa: status pengajuan, jadwal mendatang, notifikasi, riwayat milik sendiri.
- Dashboard konselor: permohonan baru, kalender, jadwal hari ini, sesi tertunda, dan tindak lanjut.
- Notifikasi internal tidak boleh memuat detail masalah konseling.
- Laporan menggunakan data agregat dan filter periode; ekspor PDF/Excel dapat disertakan jika sesuai ruang lingkup.
- Audit log mencatat akses/perubahan penting tanpa merekam isi catatan sensitif.

## 5. Aturan Status

Gunakan status yang konsisten dan validasi transisinya:
- `draft`
- `pending` / Menunggu Konfirmasi
- `approved` / Disetujui
- `reschedule_requested` / Perlu Penjadwalan Ulang
- `rejected` / Ditolak
- `cancelled` / Dibatalkan
- `in_progress` / Sedang Berlangsung
- `completed` / Selesai
- `no_show` / Tidak Hadir

Pisahkan status pengajuan/sesi dari status tindak lanjut. Contoh status tindak lanjut: `not_required`, `pending`, `scheduled`, `completed`. Dokumentasikan state machine dan transisi yang diizinkan.

## 6. Prinsip UI/UX

- Desain profesional, tenang, ramah, responsif untuk desktop dan ponsel.
- Gunakan Bahasa Indonesia yang jelas dan tidak menghakimi.
- Buat navigasi berbeda sesuai peran.
- Formulir menampilkan validasi yang jelas dan tidak meminta data berlebihan.
- Kalender menampilkan slot yang benar-benar tersedia.
- Tampilkan status dengan label teks, bukan warna saja.
- Sediakan empty state, loading state, error state, konfirmasi tindakan, dan pesan sukses/gagal.
- Jangan tampilkan detail sensitif pada dashboard umum, URL, notifikasi, atau metadata yang tidak perlu.
- Utamakan aksesibilitas: kontras yang baik, label input, fokus keyboard, dan struktur heading yang logis.

## 7. Model Data Awal

Rancang skema database yang ternormalisasi dan memakai foreign key, indeks, timestamp, aturan retensi, serta strategi penghapusan yang aman. Entitas awal:
- `users`
- `roles`
- `user_roles`
- `students`
- `counselors`
- `counseling_services`
- `counselor_schedules`
- `appointments`
- `counseling_sessions`
- `counseling_notes`
- `follow_ups`
- `notifications`
- `audit_logs`

Catatan:
- Pisahkan `counseling_notes` dari tabel jadwal dan notifikasi.
- Jangan menyimpan isi catatan sensitif di log umum.
- Gunakan transaksi/constraint yang sesuai untuk mencegah double booking.
- Tentukan apakah catatan perlu enkripsi tingkat aplikasi atau mekanisme perlindungan tambahan berdasarkan threat model.
- Jangan menaruh password, token, atau rahasia di source code atau repository.

## 8. Keamanan dan Privasi — Wajib

- HTTPS pada deployment.
- Password hashing menggunakan mekanisme aman bawaan framework.
- Validasi input server-side dan query terparameterisasi/ORM.
- Perlindungan CSRF, XSS, SQL injection, session fixation, dan brute force sesuai stack.
- Otorisasi pada setiap endpoint dan setiap objek data; cegah IDOR.
- Prinsip least privilege untuk mahasiswa, konselor, dan administrator.
- Audit akses ke catatan sensitif dan perubahan izin.
- Backup terenkripsi jika sesuai kebutuhan, kontrol akses backup, dan uji restore.
- Retensi, koreksi, penghapusan, persetujuan, serta tujuan pemrosesan data harus mengikuti kebijakan institusi dan peraturan yang berlaku di Indonesia.
- Jangan membuat fitur diagnosis otomatis atau klaim kesehatan klinis tanpa kebutuhan dan tata kelola profesional yang disetujui.
- Jangan menyatakan layanan sebagai layanan darurat 24/7 kecuali benar-benar didukung operasional. Tampilkan jalur bantuan resmi yang ditetapkan institusi.
- Jangan menggunakan data nyata mahasiswa untuk pengujian; gunakan data sintetis.

## 9. Deliverables

Buat dan pelihara dokumentasi berikut:
- `README.md` — tujuan, kebutuhan, instalasi, konfigurasi, dan cara menjalankan.
- `docs/ASSUMPTIONS.md` — keputusan dan asumsi yang belum dikonfirmasi.
- `docs/REQUIREMENTS.md` — kebutuhan fungsional dan nonfungsional.
- `docs/USER_FLOWS.md` — alur mahasiswa dan konselor.
- `docs/DATABASE.md` — ERD atau penjelasan relasi tabel.
- `docs/SECURITY.md` — model peran, otorisasi, risiko, dan mitigasi.
- `docs/TEST_PLAN.md` — skenario pengujian.
- Migration/schema database dan seed data sintetis.
- Source code dengan struktur modular.
- Automated tests untuk alur kritis dan hak akses.
- Petunjuk deployment dan backup/restore.

## 10. Tahapan Kerja yang Harus Diikuti

### Tahap 1 — Analisis
1. Baca seluruh kebutuhan.
2. Daftar keputusan yang belum ditentukan dan risiko.
3. Buat matriks role-permission.
4. Definisikan alur dan state machine.
5. Konfirmasi stack sebelum menulis banyak kode.

### Tahap 2 — Desain
1. Buat struktur halaman dan navigasi per peran.
2. Rancang ERD dan migration.
3. Definisikan endpoint/route dan policy otorisasi.
4. Dokumentasikan asumsi dan keputusan desain.

### Tahap 3 — Implementasi
1. Siapkan proyek dan konfigurasi lingkungan.
2. Implementasikan autentikasi dan RBAC lebih dahulu.
3. Implementasikan layanan, slot jadwal, dan pencegahan bentrok.
4. Implementasikan permohonan dan transisi status.
5. Implementasikan sesi, catatan terbatas, dan tindak lanjut.
6. Implementasikan notifikasi internal, dashboard, audit log, dan laporan.
7. Buat tampilan responsif dan pesan kesalahan yang aman.

### Tahap 4 — Pengujian
Uji setidaknya:
- Mahasiswa A tidak dapat melihat atau mengubah data Mahasiswa B.
- Mahasiswa tidak dapat membaca catatan konselor.
- Konselor hanya dapat membuka kasus yang menjadi kewenangannya.
- Administrator tidak otomatis dapat membaca isi catatan.
- Dua permohonan tidak dapat mengonfirmasi slot yang sama.
- Transisi status ilegal ditolak server.
- Pembatalan dan penjadwalan ulang mengikuti aturan.
- Input berbahaya ditangani dengan aman.
- Notifikasi dan log tidak membocorkan isi catatan.
- Backup dapat dipulihkan.
- Antarmuka dapat digunakan di layar ponsel.

### Tahap 5 — Serah Terima
1. Jalankan seluruh tes dan perbaiki kegagalan.
2. Sediakan instruksi instalasi dan konfigurasi environment.
3. Sediakan data demo sintetis.
4. Dokumentasikan akun demo tanpa menggunakan kredensial produksi.
5. Sampaikan fitur selesai, batasan, risiko tersisa, dan langkah deployment.

## 11. Kriteria Penerimaan

Pekerjaan MVP dianggap siap diuji ketika:
- Mahasiswa dapat mengajukan konsultasi dan melihat statusnya.
- Konselor dapat memutuskan permohonan dan mengelola jadwal.
- Sistem mencegah bentrok slot di backend.
- Sesi, catatan terbatas, dan tindak lanjut dapat dikelola sesuai izin.
- Semua akses data sensitif memiliki otorisasi server-side.
- Tes isolasi data antarmahasiswa dan antar-konselor lulus.
- Dashboard responsif dan memiliki state kosong/error/loading.
- Dokumentasi instalasi, konfigurasi, database, keamanan, dan pengujian tersedia.

## 12. Cara Memulai

Mulai dengan **Tahap 1 — Analisis**. Tampilkan:
1. Ringkasan kebutuhan yang dipahami.
2. Pertanyaan klarifikasi yang benar-benar memengaruhi arsitektur.
3. Asumsi sementara jika belum ada jawaban.
4. Rencana file dan modul.
5. Setelah keputusan utama jelas, lanjutkan ke desain lalu implementasi secara bertahap.

Jangan mengklaim aplikasi telah selesai, diuji, atau aman sebelum kode benar-benar dibuat dan pengujian terkait berhasil dijalankan.
