# LaporFak — Sistem Layanan Pelaporan Fasilitas Kampus

LaporFak adalah aplikasi web untuk memudahkan mahasiswa dan staf kampus melaporkan kerusakan fasilitas. Aplikasi ini dibuat sebagai Tugas Project Akhir pada mata kuliah Pemrograman Web Lanjut.

## Ringkasan
- **Nama:** LaporFak
- **Tujuan:** Mempermudah pelaporan, tracking, dan manajemen perbaikan fasilitas kampus.
- **Backend Framework:** Laravel 11
- **Database:** Supabase (PostgreSQL)
- **Frontend:** Blade Templates + Bootstrap / Tailwind
- **Storage:** Local Storage atau Supabase Storage
- **API:** RESTful API dengan autentikasi token terenkripsi

## Fitur Utama
- **Autentikasi User:** Login aman menggunakan enkripsi.
- **Buat Laporan:** Kirim laporan kerusakan dengan detail lokasi (Gedung / Ruangan) dan lampiran foto.
- **Tracking Status:** Pantau status laporan (Dikirim, Diproses, Selesai).
- **Riwayat Laporan:** Lihat daftar laporan yang pernah dibuat.
- **RESTful API:** Endpoint aman (Token Based) untuk integrasi pihak ketiga.

## Teknologi
- Laravel 11
- Supabase (PostgreSQL)
- Blade Templates, Bootstrap atau Tailwind CSS
- JavaScript untuk interaksi sisi-klien

## Dokumentasi API
Dokumentasi API lengkap tersedia pada file: [API_DOCS.md](API_DOCS.md)


## Cara Instalasi
Ikuti langkah-langkah berikut untuk menjalankan aplikasi secara lokal.

1. Clone repository

```bash
git clone https://github.com/alvisdiy/lapor-fak.git
cd lapor-fak
```

2. Install dependencies

```bash
composer install
npm install && npm run build
```

3. Konfigurasi environment

Copy file `.env.example` menjadi `.env` lalu isi kredensial Supabase dan konfigurasi lain.

Contoh:

```
SUPABASE_URL=masukkan_url_supabase_disini
SUPABASE_KEY=masukkan_key_supabase_disini
DB_CONNECTION=pgsql
DB_HOST=<supabase_host>
DB_PORT=5432
DB_DATABASE=<supabase_db>
DB_USERNAME=<supabase_user>
DB_PASSWORD=<supabase_password>
```

1. Generate application key

```bash
php artisan key:generate
```

5. Jalankan migrasi (jika perlu)

```bash
php artisan migrate
```

6. (Opsional) Buat symbolic link ke direktorI storage

```bash
php artisan storage:link
```

7. Jalankan aplikasi

```bash
php artisan serve
```

Akses ke: http://localhost:8000

## Catatan Penting
- Pastikan environment memuat kredensial Supabase yang benar agar koneksi database dan storage berfungsi.
- API dilindungi dengan token; jangan membagikan `SUPABASE_KEY` secara publik.

## Anggota Kelompok
- Nama Ketua (NIM)
- Nama Anggota 1 (NIM)
- Nama Anggota 2 (NIM)
- Nama Anggota 3 (NIM)

---


