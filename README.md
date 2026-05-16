# KAS-RT Digital Management System 🚀
Aplikasi Manajemen Lingkungan Terpadu (Iuran, Mesjid, & Koperasi)

## 📋 Deskripsi Proyek
**KAS-RT Digital** adalah platform ekosistem warga yang mengintegrasikan pengelolaan iuran lingkungan, transparansi dana mesjid, dan kemandirian ekonomi melalui koperasi warga. Sistem ini dirancang untuk meningkatkan transparansi, memudahkan administrasi, dan mempererat interaksi sosial warga.

---

## 🛠️ Tech Stack
* **Framework:** Laravel (PHP 8.2+)
* **Frontend:** Tailwind CSS, Alpine.js
* **Database:** MySQL
* **Charts:** ApexCharts (Visualisasi Transparansi)
* **Icons:** Heroicons / Lucide React

---

## 👥 Arsitektur Peran Pengguna (User Roles)

| Role | Tanggung Jawab Utama |
| :--- | :--- |
| **Superadmin** | Manajemen User, Audit Sistem, & Akses Penuh ke seluruh modul. |
| **Pak RT** | Monitoring Laporan Kas Global (RT, Mesjid, Koperasi) & Kebijakan Lingkungan. |
| **Bendahara RT** | Manajemen Iuran Warga (Sampah/Keamanan) & Kas Umum Lingkungan. |
| **Pengurus Mesjid** | Manajemen Infaq, Pengeluaran Operasional Mesjid, & Transparansi Dana Jemaah. |
| **Pengurus Koperasi** | Pengelolaan Simpanan (Pokok/Wajib/Sukarela) & Verifikasi Pinjaman Kasbon. |
| **Warga** | Pembayaran Iuran, Penunaian Infaq, & Manajemen Tabungan Koperasi. |

---

## 🚀 Fitur Utama & Progres

### 1. Portal Warga (Dashboard Utama)
* **Status Iuran Real-time:** Notifikasi lunas/nunggak bulan berjalan.
* **Smart Infaq Card:** Ringkasan total amal jariyah di Mesjid.
* **Koperasi Wallet:** Monitoring saldo tabungan dan status cicilan kasbon.
* **Chart Transparansi:** Perbandingan kontribusi pribadi vs total pengeluaran RT.

### 2. Modul Pengurus Mesjid
* **Logic "Loss" Infaq:** Infaq warga otomatis disetujui tanpa antrean verifikasi manual.
* **Expenditure Tracker:** Pencatatan biaya operasional (Listrik, Gaji Marbot) + Upload Bukti Nota.
* **Payment Settings:** Pengaturan mandiri Norek & Barcode QRIS Mesjid.
* **Layout 70:30:** Area riwayat di sisi kiri dan panduan bayar di sisi kanan.

### 3. Modul Pengurus Koperasi
* **Automated Installments:** Pembuatan jadwal cicilan otomatis saat kasbon disetujui.
* **Savings Analytics:** Visualisasi komposisi saldo (Pokok, Wajib, Sukarela) via Donut Chart.
* **Withdrawal System:** Manajemen pengajuan penarikan dana sukarela warga.
* **Payment Settings:** Pengaturan mandiri Norek & Barcode QRIS Koperasi.

### 4. Dashboard Global (Sekretariat RT) - *Development*
* **Consolidated Balance:** Ringkasan gabungan saldo Kas RT, Kas Mesjid, & Kas Koperasi.
* **Agenda Manager:** Manajemen jadwal kegiatan warga & kerja bakti.
* **Manajemen Anggota:** Verifikasi data warga tetap vs warga kontrak.

---

## 💡 Inovasi & Roadmap (Future Plan)

* [ ] **Inventaris RT:** Digitalisasi peminjaman aset (Tenda, Kursi, Sound System).
* [ ] **Pasar Tetangga:** Etalase UMKM warga untuk mendukung ekonomi lokal.
* [ ] **E-Voting:** Pemungutan suara digital untuk musyawarah & pemilihan ketua RT.
* [ ] **Dana Darurat:** Crowdfunding otomatis saat ada warga tertimpa musibah.
* [ ] **WA Gateway:** Notifikasi otomatis & Reminder iuran via WhatsApp.

---

## 🗄️ Skema Database Utama (High Level)
* `users`: Data autentikasi & role.
* `billings`: Data tagihan iuran bulanan warga.
* `infaqs`: Data transaksi infaq mesjid (user_id, nominal, status).
* `mesjid_payments`: Pengaturan rekening mesjid.
* `mesjid_expenditures`: Catatan pengeluaran operasional mesjid.
* `koperasi_accounts`: Data saldo tabungan warga.
* `koperasi_loans`: Data pinjaman/kasbon & cicilan.
* `koperasi_payments`: Pengaturan rekening koperasi.

---
**Developed with ❤️ for a Better Community.**