# Aplikasi Layanan Ekspedisi

Aplikasi CRUD sederhana menggunakan Laravel 12, PHP 8.2+, dan MySQL. Pengguna dapat melihat, mencari, menambah, mengedit, dan menghapus layanan ekspedisi. Data yang sama juga tersedia melalui REST API.

## Menjalankan aplikasi

1. Pastikan PHP 8.2+ dan Composer tersedia.
2. Dari folder proyek, pasang dependensi:

   ```sh
   composer install
   ```

3. Salin `.env.example` menjadi `.env` (di PowerShell: `Copy-Item .env.example .env`). Setup awal menggunakan SQLite di `database/database.sqlite` dan tidak memerlukan MySQL. Jika `.env` sudah ada, pastikan `DB_CONNECTION=sqlite` dan `DB_DATABASE=database/database.sqlite`.
4. Buat application key dan tabel:

   ```sh
   php artisan key:generate
   php artisan migrate --seed
   ```

5. Jalankan server:

   ```sh
   php artisan serve
   ```

6. Buka `http://127.0.0.1:8000`.

Seeder menambahkan contoh layanan JNE dan SiCepat jika tabel masih kosong.

### Menggunakan MySQL

Nyalakan MySQL melalui XAMPP atau layanan lain dan buat database bernama `e-commerce`. Pada `.env`, ubah `DB_CONNECTION=sqlite` menjadi `DB_CONNECTION=mysql`, lalu isi `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`. Setelah itu jalankan `php artisan migrate --seed`.

### Database lama

Jika tabel `layanan` sudah dibuat menggunakan `database.sql`, migrasi akan mengenali tabel tersebut dan tidak membuat tabel duplikat. Model Laravel menggunakan kolom yang sama dengan tabel lama.

## Struktur Laravel

- `app/Models/Layanan.php`: model untuk tabel layanan.
- `app/Http/Controllers/`: logika CRUD halaman dan API.
- `database/migrations/`: struktur tabel.
- `database/seeders/`: contoh data awal.
- `resources/views/`: halaman Blade.
- `routes/web.php`: route halaman web.
- `routes/api.php`: route REST API.

## REST API

Base URL API: `http://127.0.0.1:8000/api`

| Method | Endpoint | Fungsi |
| --- | --- | --- |
| GET | `/layanan` | Daftar layanan |
| GET | `/layanan/{id}` | Detail layanan |
| POST | `/layanan` | Tambah layanan |
| PUT/PATCH | `/layanan/{id}` | Edit layanan |
| DELETE | `/layanan/{id}` | Hapus layanan |

Contoh body JSON:

```json
{
  "nama_ekspedisi": "Kurir Baru",
  "jenis_layanan": "Reguler",
  "zonasi": "Zona 1",
  "tarif": 10000,
  "estimasi": "2-3 Hari",
  "status": "ON"
}
```

Nama ekspedisi otomatis disimpan dalam huruf kapital. Field `status` dapat dihilangkan saat membuat layanan API; nilai awalnya `ON`. Import `postman_collection.json` untuk mencoba endpoint.
