# KAS-RT Digital Management System 🚀
Aplikasi Manajemen Lingkungan Terpadu (Iuran & Kegiatan Warga)

## 📋 Deskripsi Proyek
**KAS-RT Digital** adalah platform ekosistem warga yang mendigitalisasi pengelolaan iuran lingkungan dan transparansi dana RT. Sistem ini dirancang untuk meningkatkan transparansi, memudahkan administrasi, dan mempererat interaksi sosial antar warga melalui integrasi sistem keuangan dan kegiatan.

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
| **Pak RT** | Monitoring Laporan Kas Global RT & Manajemen Kebijakan Lingkungan. |
| **Bendahara RT** | Manajemen Iuran Warga (Sampah/Keamanan), Validasi Pembayaran & Kas Umum. |
| **Warga** | Pembayaran Iuran, Pembaruan Data Keluarga, & Partisipasi Kegiatan. |

---

## 🚀 Fitur Utama & Progres

### 1. Portal Warga (Dashboard Utama)
* **Status Iuran Real-time:** Notifikasi lunas/nunggak bulan berjalan.
* **Pembayaran Terintegrasi:** Unggah struk bukti transfer dengan aman (IDOR Protected).
* **Data Keluarga:** Manajemen profil pribadi & Sub-KK secara mandiri.
* **Info Kegiatan:** Akses jadwal kegiatan dan laporan keuangan terkini.

### 2. Modul Pengurus & Bendahara
* **Validasi Satu Pintu:** Sistem verifikasi pembayaran iuran warga yang cepat dan efisien.
* **Manajemen Warga (Anti Ganda):** Validasi *Unique Constraint* mencegah nomor rumah kembar.
* **Master Iuran:** Pengaturan nominal jenis iuran secara dinamis.
* **Payment Settings:** Pengaturan mandiri Nomor Rekening & Barcode QRIS tujuan pembayaran.

### 3. Dashboard Laporan (Sekretariat RT)
* **Consolidated Balance:** Ringkasan total Pemasukan vs Pengeluaran kas RT.
* **Grafik Analitik:** Visualisasi data laporan 12 bulan terakhir (Anti N+1 Query Problem).
* **Agenda Manager:** Manajemen jadwal kegiatan warga & pencatatan realisasi dana/nota.

---

## 📱 Filosofi Desain: Mobile-First DNA (Nyaman di HP)
KAS-RT Digital dirancang dengan memprioritaskan pengalaman visual yang premium dan mantap saat diakses melalui *smartphone* (HP):
* **Anti Geser Samping (*No Horizontal Scroll*)**: Tidak ada lagi tabel data kaku yang mengharuskan warga menggeser layar ke samping.
* **DNA *Accordion* (Kartu Lipat Cerdas)**: Data kompleks seperti Anggota Keluarga, Riwayat Kegiatan, dan Laporan Kas disulap menjadi wujud "Kartu" ringkas. Cukup disentuh, kartu akan melipat terbuka ke bawah untuk menyajikan detail (anti-sumpek & elegan).
* **DNA *Timeline Ledger***: Status tagihan 12 bulan tidak lagi berwujud kotak raksasa, melainkan dirangkum menjadi garis waktu (*timeline*) tipis yang menawan layaknya riwayat transaksi bank kelas atas.
* **Bunglon Responsif**: Walaupun sangat memanjakan pengguna HP, jika Pengurus RT mengaksesnya via Laptop/PC, maka "Tabel Besar" yang utuh otomatis kembali ditampilkan demi kelancaran bekerja.

---

## 💡 Inovasi & Roadmap (Future Plan)

* [ ] **Inventaris RT:** Digitalisasi peminjaman aset (Tenda, Kursi, Sound System).
* [ ] **Pasar Tetangga:** Etalase UMKM warga untuk mendukung ekonomi lokal.
* [ ] **E-Voting:** Pemungutan suara digital untuk musyawarah & pemilihan ketua RT.
* [ ] **Dana Darurat:** Crowdfunding otomatis saat ada warga tertimpa musibah.
* [ ] **WA Gateway:** Notifikasi otomatis & Reminder iuran via WhatsApp.

---

## 🗄️ Skema Database Utama (High Level)
* `users`: Data autentikasi, Role, & Profil (termasuk Blok & No Rumah dengan *Unique Constraint*).
* `family_members`: Data profil anggota keluarga & pengelompokan sub-KK.
* `billings`: Data tagihan iuran bulanan warga & bukti transfer.
* `agendas`: Manajemen jadwal kegiatan RT, status pelaksanaan, & realisasi dana/pengeluaran.
* `iuran_masters`: Referensi master dinamis untuk jenis dan besaran iuran RT.
* `payment_settings`: Pengaturan rekening pembayaran terpusat & integrasi barcode QRIS.

---
**Developed with ❤️ for a Better Community.**