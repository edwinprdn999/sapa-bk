# RENCANA PENGUJIAN (TEST PLAN) — SAPA-BK

Dokumen ini mendefinisikan matriks pengujian fungsional dan keamanan untuk sistem SAPA-BK.

---

## 1. Matriks Pengujian Hak Akses & Isolasi Data

| Kasus Uji | Skenario | Hasil yang Diharapkan |
|---|---|---|
| TC-SEC-01 | Mahasiswa A mencoba mengakses detail permohonan Mahasiswa B via ID URL | Server mengembalikan HTTP 403 Forbidden |
| TC-SEC-02 | Mahasiswa mencoba mengakses rute catatan konseling (`/counselor/sessions/{id}/notes`) | Server mengembalikan HTTP 403 Forbidden |
| TC-SEC-03 | Konselor A mencoba mengedit catatan sesi Konselor B | Server mengembalikan HTTP 403 Forbidden |
| TC-SEC-04 | Administrator mencoba membuka endpoint catatan konseling rahasia | Server menolak akses (HTTP 403) |
| TC-SEC-05 | Pengguna non-login mencoba mengakses dashboard | Diredirect ke halaman login (HTTP 302) |

---

## 2. Pengujian Logika Bisnis & Penjadwalan

| Kasus Uji | Skenario | Hasil yang Diharapkan |
|---|---|---|
| TC-BIZ-01 | Dua mahasiswa memesan slot jadwal konselor yang sama secara bersamaan | Permohonan pertama berhasil, permohonan kedua ditolak dengan pesan slot tidak tersedia |
| TC-BIZ-02 | Konselor menyetujui permohonan status `pending` | Status berubah menjadi `approved` dan slot jadwal ditandai `is_booked = true` |
| TC-BIZ-03 | Konselor menolak permohonan tanpa mengisi alasan | Validasi form gagal dan meminta alasan penolakan wajib diisi |
| TC-BIZ-04 | Mahasiswa membatalkan permohonan yang berstatus `pending` | Status menjadi `cancelled` dan slot jadwal dibuka kembali |
| TC-BIZ-05 | Konselor menyelesaikan sesi dan menyimpan catatan rahasia | Sesi tersimpan, catatan tersimpan di `counseling_notes`, audit log tercatat |

---

## 3. Pengujian UI/UX & Tampilan

| Kasus Uji | Skenario | Hasil yang Diharapkan |
|---|---|---|
| TC-UI-01 | Tampilan responsif pada ukuran viewport mobile (375px - 414px) | Navigasi mobile menu berfungsi, card dan tabel tidak meluap (overflow aman) |
| TC-UI-02 | Visual indikator status | Setiap status menampilkan teks eksplisit dan warna chip yang kontras (Material theme) |
| TC-UI-03 | Validasi input form | Pesan error validasi tampil di bawah setiap field input yang tidak sesuai |
