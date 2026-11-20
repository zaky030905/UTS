# Medical Inventory System — UMPKU

Aplikasi CRUD inventaris alat elektromedis menggunakan PHP Native dan MySQL/MariaDB. Antarmuka diperbarui dengan inspirasi visual dari template CardioCare ThemeWagon (tema healthcare modern), disesuaikan menjadi dashboard pengelolaan aset alat medis.

## Identitas Mahasiswa
- **Nama:** Muhammad Zaky Alfiansyah
- **NIM:** 1202405029
- **Program Studi:** D4 Elektromedis
- **Institusi:** Universitas Muhammadiyah PKU Surakarta

## Fitur
1. **Create** — menambahkan data alat elektromedis.
2. **Read** — melihat daftar inventaris dan ringkasan total alat, tahun pengadaan, dan merek.
3. **Update** — memperbarui data berdasarkan ID.
4. **Delete** — menghapus data setelah konfirmasi.
5. Pencarian langsung pada tabel.
6. Login dan registrasi akun pengguna.
7. Logout dan proteksi halaman menggunakan session.
8. Password disimpan dengan password_hash/password_verify.
9. Tampilan responsif untuk desktop dan perangkat seluler.

## Teknologi
- PHP Native
- MySQL / MariaDB
- HTML5, CSS3, JavaScript
- XAMPP
- Visual Studio Code

## Struktur Database
- Database: `db_alat_elektromedis`
- Tabel: `alat_elektromedis`
- Kolom: `id`, `nama_alat`, `tahun`, `merek`, `lokasi`

## Cara Menjalankan
1. Ekstrak folder proyek ke `C:\xampp\htdocs\`.
2. Jalankan **Apache** dan **MySQL** pada XAMPP.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih menu **Import**, lalu pilih `database.sql` dan jalankan import.
5. Buka `http://localhost/MEDICAL_INVENTORY_ZAKY/`.

## Catatan
- Konfigurasi database berada di `config.php`.
- Default XAMPP: host `localhost`, user `root`, password kosong.
- Nama database pada `config.php` dan `database.sql` sudah disamakan menjadi `db_alat_elektromedis`.
- Jika database lama sudah berisi data penting, lakukan backup sebelum import.
- `login.php` — halaman login.
- `register.php` — pendaftaran akun baru.
- `logout.php` — proses keluar dari akun.
- `auth.sql` — pembuatan tabel pengguna untuk database yang sudah ada.
- `test.php` dapat digunakan untuk memeriksa koneksi database setelah login. Hapus file tersebut dari server publik setelah pengujian.

## Mengaktifkan Login dan Register pada database yang sudah ada
Jika database `db_alat_elektromedis` sudah pernah dibuat sebelumnya, buka phpMyAdmin, pilih database tersebut, buka tab **Import**, lalu import file `auth.sql`. File ini hanya membuat tabel `users` jika belum tersedia dan tidak menghapus data inventaris.

Setelah tabel dibuat, buka `http://localhost/MEDICAL_INVENTORY_ZAKY/register.php` untuk membuat akun pertama. Setelah pendaftaran berhasil, login melalui `login.php`. Dashboard dan halaman CRUD akan meminta login terlebih dahulu.

**Catatan:** Setiap orang yang dapat membuka halaman Register dapat membuat akun. Untuk penggunaan produksi, batasi pendaftaran atau tambahkan persetujuan admin serta gunakan HTTPS.


## Tampilan Autentikasi
- Halaman Login dan Register memakai layout healthcare dua kolom yang responsif.
- Tombol lihat/sembunyikan password tersedia pada formulir autentikasi.
- Halaman Logout menampilkan konfirmasi akun aktif sebelum sesi diakhiri.
- Setelah logout berhasil, pengguna diarahkan kembali ke Login dengan pesan konfirmasi.
