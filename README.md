# Tugas CRUD - E-Commerce

Website CRUD data layanan ekspedisi pengiriman menggunakan PHP dan MySQL, tugas individu minggu 2.
Ini bagian Individu C (Modul Ekspedisi Kirim) dari Studi Kasus 06: E-Commerce / Toko Online, yang mencakup master kurir/ekspedisi, jenis layanan (Reguler/Express/Cargo), dan zonasi.

Dibuat dengan PHP (PDO), MySQL, dan XAMPP.

## Yang bisa dilakukan

- Lihat daftar layanan ekspedisi dan zonasinya (index.php)
- Tambah layanan baru
- Edit data layanan
- Hapus data layanan (lewat REST API)
- Akses data lewat REST API (bisa dites pakai Postman)

## Isi file

- `index.php` : halaman utama, memanggil routes/web.php
- `config/database.php` : buat nyambung ke database (PDO)
- `controllers/layanan_controller.php` : logika CRUD layanan (web dan API)
- `controllers/zona_controller.php` : nampilin data berdasarkan zona
- `routes/web.php` : routing halaman web
- `routes/api.php` : routing REST API
- `src/` : file pendukung tampilan
- `style.css` : tampilan
- `database.sql` : file buat bikin tabel dan data awal
- `postman_collection.json` : koleksi buat tes API

## Cara Menjalankan

1. Nyalakan Apache dan MySQL di XAMPP.
2. Buka phpMyAdmin (localhost/phpmyadmin), buat database baru bernama `e-commerce`.
3. Masuk ke tab SQL pada database itu, lalu jalankan isi file `database.sql`.
4. Copy folder project ini ke `C:\xampp\htdocs\e-commerce`.
5. Buka `localhost/e-commerce/index.php` di browser.

Alternatif tanpa XAMPP (kalau PHP sudah terpasang): jalankan `php -S 127.0.0.1:8000` di folder project, lalu buka `http://127.0.0.1:8000`.

Koneksi database bisa diatur lewat environment variable `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`. Kalau tidak diatur, default-nya `127.0.0.1`, `e-commerce`, `root`, dan password kosong.

## Tabel layanan

Database: `e-commerce`

| Kolom | Keterangan |
|-------|------------|
| id (primary key) | auto increment |
| nama_ekspedisi | nama kurir, contoh JNE |
| jenis_layanan | Reguler / Express / Cargo |
| zonasi | Zona 1 sampai Zona 4 |
| tarif | tarif pengiriman |
| estimasi | pilihan estimasi pengiriman: 1 Hari, 2-3 Hari, 3-4 Hari, 3-5 Hari, 4-5 Hari, 5-7 Hari, atau 7-14 Hari |
| status | ON / OFF (default ON) |

## REST API

Base URL: `http://127.0.0.1:8000/routes/api.php`

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| GET | /layanan | semua layanan |
| GET | /layanan/{id} | detail satu layanan |
| POST | /layanan | tambah layanan |
| PUT | /layanan/{id} | edit layanan |
| DELETE | /layanan/{id} | hapus layanan |

Contoh body untuk POST dan PUT:

```json
{
  "nama_ekspedisi": "Kurir Baru",
  "jenis_layanan": "Reguler",
  "zonasi": "Zona 1",
  "tarif": 10000,
  "estimasi": "2-3 Hari"
}
```

Tes pakai Postman: import `postman_collection.json`, pastikan variable `baseUrl` sesuai alamat server.

## Dibuat oleh

DZAKY PRANANTYA VALOROUSA
102022530077
Kelas: (SI 49-03)