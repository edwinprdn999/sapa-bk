# ALUR PENGGUNA (USER FLOWS) & STATE MACHINE — SAPA-BK

## 1. Alur Mahasiswa (Student Flow)

```
[Buka Website] ──> [Login / Register Akun]
       │
       ▼
[Dashboard Mahasiswa]
       │
       ├──> [Lihat Riwayat & Status Pengajuan Aktif]
       ├──> [Lihat Rencana Tindak Lanjut Mandiri]
       │
       ▼
[Pilih Jenis Layanan & Konselor]
       │
       ▼
[Pilih Slot Waktu Ketersediaan Konselor]
       │
       ▼
[Isi Formulir Pengajuan (Metode: Online/Offline, Deskripsi Awal)]
       │
       ▼
[Submit Pengajuan] ──> Status: 'pending' (Menunggu Konfirmasi)
       │
       ├──> [Jika Disetujui] ──> Status: 'approved' ──> Mengikuti Sesi
       ├──> [Jika Penjadwalan Ulang] ──> Memilih Slot Baru
       └──> [Jika Dibatalkan Mahasiswa] ──> Status: 'cancelled'
```

---

## 2. Alur Konselor (Counselor Flow)

```
[Login Konselor]
       │
       ▼
[Dashboard Konselor]
       │
       ├──> [Atur Jadwal & Ketersediaan Slot Waktu]
       ├──> [Lihat Daftar Permohonan Masuk]
       │         │
       │         ├──> [Setujui Permohonan] ──> Status: 'approved'
       │         ├──> [Tolak Permohonan + Berikan Alasan] ──> Status: 'rejected'
       │         └──> [Minta Reschedule] ──> Status: 'reschedule_requested'
       │
       ├──> [Pelaksanaan Sesi Konseling]
       │         │
       │         ├──> Absensi: Hadir / Tidak Hadir (no_show)
       │         ├──> Waktu Mulai & Waktu Selesai
       │         ├──> Simpan Catatan Rahasia Konseling (Tabel Terproteksi)
       │         └──> Buat Rencana Tindak Lanjut (Follow-Up)
       │
       └──> [Lihat Laporan & Rekap Kegiatan Operasional]
```

---

## 3. Diagram State Machine Status Permohonan

```
                       ┌──────────────┐
                       │    DRAFT     │
                       └──────┬───────┘
                              │ submit
                              ▼
                       ┌──────────────┐
         ┌────────────>│   PENDING    ├────────────┐
         │             └──────┬───────┘            │
         │                    │                    │
  reschedule                  │ approve            │ reject / cancel
         │                    ▼                    ▼
         │             ┌──────────────┐     ┌──────────────┐
         └─────────────┤   APPROVED   │     │   REJECTED   │
                       └──────┬───────┘     │  CANCELLED   │
                              │             └──────────────┘
                       mulai  │
                              ▼
                       ┌──────────────┐
                       │ IN_PROGRESS  │
                       └──────┬───────┘
                              │
                 ┌────────────┴────────────┐
                 │ selesai                 │ no-show
                 ▼                         ▼
          ┌──────────────┐          ┌──────────────┐
          │  COMPLETED   │          │   NO_SHOW    │
          └──────────────┘          └──────────────┘
```
