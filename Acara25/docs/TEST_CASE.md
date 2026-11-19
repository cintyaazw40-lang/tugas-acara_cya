# Pengujian RBAC — Acara 25

Import `Acara25.postman_collection.json`, pilih environment Local, lalu
jalankan dari atas ke bawah. SQL membuat atau memperbarui akun admin praktikum.

| Skenario | Hasil yang diharapkan | Aktual |
|---|---|---:|
| Login `admin` / `admin123` | HTTP 200; JWT dan role `admin` | 200 |
| Login user yang terdaftar | HTTP 200; role `user` | 200 |
| User membaca `GET /mahasiswa` | HTTP 200 | 200 |
| User menambah data | HTTP 403 | 403 |
| User mengubah data | HTTP 403 | 403 |
| User menghapus data | HTTP 403 | 403 |
| Admin menambah/mengubah/menghapus data | HTTP 201/200/200 | 201/200/200 |
| Membaca data setelah dihapus | HTTP 404 | 404 |
| Dashboard admin | Total data dan kontrol CRUD tampil | Verified in browser |

HTTP RBAC diverifikasi langsung pada Apache/PHP/MySQL Laragon. Koleksi Postman
menyediakan pengujian ulang; hasil browser admin menampilkan dashboard dan
kontrol CRUD. Logout, redirect tanpa token, dan penyembunyian kontrol pada
role user juga dapat diverifikasi dari browser sesuai skenario di atas.
