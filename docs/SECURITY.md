# KEAMANAN, OTORISASI DAN PRIVASI (SECURITY) — SAPA-BK

## 1. Prinsip Least Privilege & RBAC (Role-Based Access Control)

Sistem SAPA-BK menerapkan kontrol akses berbasis peran yang diverifikasi pada level server (middleware dan gate/policy Laravel):

1. **Mahasiswa (`mahasiswa`):**
   - Hanya dapat membuat permohonan konseling atas namanya sendiri.
   - Hanya dapat membaca dan membatalkan jadwal permohonan miliknya (`student_id == auth()->user()->student->id`).
   - Tidak dapat melihat daftar atau profil detail permohonan mahasiswa lain (mitigasi IDOR).
   - **Dilarang mengakses** catatan internal konselor (`counseling_notes`).
   - Hanya dapat membaca rencana tindak lanjut (`follow_ups`) yang ditujukan secara spesifik kepadanya.

2. **Konselor / Guru BK (`konselor`):**
   - Mengelola jadwal ketersediaan mandiri.
   - Meninjau, menyetujui, menolak permohonan yang ditujukan kepadanya.
   - Mencatat absensi sesi, membuat catatan rahasia konseling, dan menetapkan rencana tindak lanjut.
   - Hanya dapat membaca dan mengedit catatan konseling dari sesi yang ditanganinya.

3. **Administrator (`administrator`):**
   - Mengelola master data: pengguna, layanan konseling, akun konselor.
   - Melihat metrik operasional agregat (jumlah sesi, status tanpa membuka masalah sensitif).
   - Memantau jejak keamanan pada `audit_logs`.
   - **Tidak memiliki akses baca ke `counseling_notes`** guna menjamin kode etik kerahasiaan konselor.

---

## 2. Mitigasi Risiko Teknis

- **Pencegahan Double Booking:**
  Transaksi database (`DB::transaction`) dan locking baris (`lockForUpdate`) digunakan saat konfirmasi permohonan untuk menjamin slot waktu konselor tidak dapat dipesan ganda oleh permintaan bersamaan.
- **Injeksi SQL:**
  Menggunakan query builder dan Eloquent ORM dengan prepared statements berparameter.
- **Cross-Site Scripting (XSS):**
  Blade template engine secara default meng-escape seluruh output (`{{ $data }}`). Konten sensitif dibersihkan sebelum disimpan.
- **Cross-Site Request Forgery (CSRF):**
  Seluruh form POST, PUT, DELETE diverifikasi oleh middleware `VerifyCsrfToken`.
- **Privasi Notifikasi:**
  Isi pesan notifikasi dan log audit dihindarkan dari memuat detail masalah pribadi mahasiswa.
