<div align="center">

# 📊 SPJ ARKAS
### Administrasi BOSP Sekolah

Sistem Administrasi SPJ, BKU BOSP,<br>
Rekap Transaksi, & Generator Kwitansi<br>
SMP Muhammadiyah Tonjong

[![Laravel](https://img.shields.io/badge/Laravel-v12.x-FF2D20)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-v5.3-7952B3)](https://getbootstrap.com)

</div>

---

## 📌 Tentang Project

**SPJ ARKAS** adalah aplikasi web internal
untuk pencatatan transaksi Keuangan Sekolah,
rekapitulasi Buku Kas Pembantu (BKP),
cetak kwitansi bundle, serta pengarsipan
berkas BOSP di **SMP Muhammadiyah Tonjong**.

---

## 🔥 Fitur Utama

- 📈 **Dashboard BOSP:** Analytics real-time
  saldo kas 4 sumber dana & progress bulanan.
- 💰 **Multi-Sumber Dana:** BKU terpisah untuk
  BOS Reguler, Afirmasi, Kinerja, & Daerah.
- 🧮 **Buku Kas Pembantu:** Hitung otomatis
  penerimaan, pengeluaran, & saldo berjalan.
- 🖨️ **Generator Kwitansi:** Cetak otomatis
  Kwitansi A2 & Kwitansi Umum Sekolah.
- 📊 **Export Excel:** Unduh rekap BKU
  langsung ke `.xlsx` tanpa beban server.
- 📁 **Download Template:** Pusat kelola file
  administrasi rutin (SK, Monev, SPTJM).
- 🌙 **Dark Mode:** Mode gelap kontras tinggi.

---

## 🏗️ Alur Sistem

1. **Dashboard:** Monitoring saldo kas.
2. **Penganggaran SPJ:** Input BKU.
3. **Export & Print:** Download Excel & PDF.
4. **Template Manager:** Kelola file.

---

## 🛠️ Tech Stack

- **Core Engine:** PHP 8.2+ & Laravel 12.x
- **Database:** MySQL / SQLite
- **Frontend UI:** Bootstrap 5.3 & Icons
- **Spreadsheet:** SheetJS (XLSX Engine)

---

## 🚀 Panduan Instalasi

```bash
# 1. Clone repository
git clone https://github.com/smpmuhtonjong/spj-arkas.git
cd spj-arkas

# 2. Install dependency
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Migrasi & storage link
php artisan migrate
php artisan storage:link

# 5. Clear cache & serve
php artisan view:clear
php artisan route:clear
php artisan serve
```

Akses aplikasi di: `http://127.0.0.1:8000`

---

## 📂 Struktur Direktori

- `app/Http/Controllers/`
  Logika Dashboard, SPJ, & Template.
- `app/Models/`
  Model Data SPJ & Template File.
- `database/migrations/`
  Skema tabel database.
- `resources/views/`
  Tampilan Blade UI & Layout.
- `storage/app/public/`
  Penyimpanan PDF & Upload Template.

---

## 📞 Kontak & Pengembang

**Tim IT SMP Muhammadiyah Tonjong**

- 🏫 **Sekolah:** SMP Muhammadiyah Tonjong
- 📍 **Alamat:** Jl. Raya Linggapura No.46
- ✉️ **Email:** smpmuhitonjong@gmail.com
- 🌐 **Web:** [smpmuhtonjong.sch.id](https://smpmuhtonjong.sch.id)

---

<div align="center">

**© 2026 Tim IT SMP Muhammadiyah Tonjong**

</div>