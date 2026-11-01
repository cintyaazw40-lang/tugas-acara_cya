# Dokumentasi Endpoint - Acara 20 (API Authentication dan Error Handling)

Base URL: `{{base_url}}` = `http://localhost/tugas%20acara_cya/Acara_20/public`

## Endpoint
| Method | Endpoint | Fungsi | Status |
|---|---|---|---|
| POST | /register | Registrasi pengguna | 201, 400, 409, 422 |
| POST | /login | Login, menghasilkan token JWT | 200, 400, 401 |
| GET | /mahasiswa | Daftar mahasiswa (publik) | 200 |
| POST | /mahasiswa | Tambah mahasiswa (butuh token) | 201, 401, 400, 409, 422 |
| PUT | /mahasiswa?id=1 | Ubah mahasiswa (butuh token) | 200, 401, 404 |
| DELETE | /mahasiswa?id=1 | Hapus mahasiswa (butuh token) | 200, 401, 404 |

## Cara memakai token
1. `POST /login` dengan `{ "username": "...", "password": "..." }`.
2. Salin `data.token` dari response.
3. Kirim header `Authorization: Bearer <token>` pada POST, PUT, dan DELETE.

Token berlaku **5 menit** (`Auth::TOKEN_TTL = 300`, Tugas Mandiri 2). Token yang diubah atau kedaluwarsa dijawab **401**.

## Mengapa JWT stateless tidak punya logout di sisi server? (Tugas Mandiri 1)
JWT bersifat *stateless*: server tidak menyimpan data sesi, hanya memverifikasi tanda tangan dan masa berlaku token pada setiap request. Karena tidak ada catatan token di server, server tidak punya apa pun untuk "dihapus" ketika pengguna logout, sehingga token tetap sah sampai `exp`. Logout hanya berarti klien membuang token. Agar token dapat dicabut sebelum kedaluwarsa dibutuhkan mekanisme tambahan, misalnya *blacklist* token (tabel berisi token yang dicabut yang diperiksa setiap request), masa berlaku yang pendek, atau *refresh token*. Semua itu mengembalikan sebagian keadaan (state) ke server.
