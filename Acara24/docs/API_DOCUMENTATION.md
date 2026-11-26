# Dokumentasi API — Sistem Informasi Akademik

Base URL: `http://localhost/Code/Acara21/public`

## Format Response

Semua response berformat JSON (dihasilkan `Response::json`):

```json
{
  "success": true,
  "message": "...",
  "data": null,
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

- `data` selalu ada; bernilai `null` jika tidak ada data yang dikembalikan.
- `errors` hanya muncul jika ada kesalahan validasi per field.
- `timestamp` berformat ISO 8601.

## Peta Router

| Resource (URL) | Method | Controller                   | Keterangan                                        | Auth |
|----------------|--------|------------------------------|---------------------------------------------------|------|
| /register      | POST   | AuthController::register     | Pendaftaran pengguna baru                         | Tidak |
| /login         | POST   | AuthController::login        | Autentikasi pengguna, menghasilkan token          | Tidak |
| /mahasiswa     | GET    | MahasiswaController::index   | Seluruh data mahasiswa, atau satu data via `?id=` | Tidak |
| /mahasiswa     | POST   | MahasiswaController::store   | Menambah data mahasiswa                           | Ya |
| /mahasiswa?id= | PUT    | MahasiswaController::update  | Mengubah data mahasiswa                           | Ya |
| /mahasiswa?id= | DELETE | MahasiswaController::destroy | Menghapus data mahasiswa                          | Ya |

## Autentikasi

Endpoint dengan Auth "Ya" wajib mengirim header:

```
Authorization: Bearer <token>
```

Token (JWT HS256) diperoleh dari `POST /login` dan **berlaku 5 menit**. Jika gagal, `Auth::requireAuth()` mengembalikan:

| HTTP | Message                                  | Penyebab                              |
|------|------------------------------------------|---------------------------------------|
| 401  | Token tidak ditemukan                    | Header Authorization tidak ada/salah format |
| 401  | Token tidak valid atau kedaluwarsa       | Signature salah atau token sudah lewat 5 menit |

## Catatan Umum

- Parameter `id` dikirim lewat query string dan harus bilangan bulat >= 1, jika tidak: `400 ID tidak valid`.
- Resource tidak terdaftar: `404 Endpoint tidak ditemukan`.
- Metode HTTP selain GET, POST, PUT, DELETE: `405 Metode HTTP tidak diizinkan`.

---

## POST /register
Controller: `AuthController::register()`

**Headers:** `Content-Type: application/json`

**Request Body:**
```json
{ "username": "admin", "email": "admin@example.com", "password": "rahasia123" }
```

**Response 201 Created:**
```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": { "id": 1 },
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

**Response 400 Bad Request** (field kosong):
```json
{
  "success": false,
  "message": "Username, email, dan password wajib diisi",
  "data": null,
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

**Response 409 Conflict:** `Username atau email sudah terdaftar`

**Response 405:** `Gunakan metode POST untuk register` (jika bukan POST)

---

## POST /login
Controller: `AuthController::login()`

**Headers:** `Content-Type: application/json`

**Request Body:**
```json
{ "username": "admin", "password": "rahasia123" }
```

**Response 200 OK:**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": { "token": "eyJ...", "role": "admin" },
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

**Response 400 Bad Request:** `Username dan password wajib diisi`

**Response 401 Unauthorized:**
```json
{
  "success": false,
  "message": "Username atau password salah",
  "data": null,
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

**Response 405:** `Gunakan metode POST untuk login` (jika bukan POST)

---

## GET /mahasiswa
Controller: `MahasiswaController::index()`

Tanpa parameter menampilkan semua data. Dengan `?id=1` menampilkan satu data.

**Response 200 OK:**
```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [
    { "id": 1, "nim": "123456789", "nama": "Budi Santoso", "prodi": "Teknik Informatika" }
  ],
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

**Response gagal:** 400 `ID tidak valid` · 404 `Data tidak ditemukan` · 500 `Terjadi kesalahan server`

---

## POST /mahasiswa
Controller: `MahasiswaController::store()`

**Headers:**
- `Content-Type: application/json`
- `Authorization: Bearer <token>`

**Request Body:**
```json
{ "nim": "123456789", "nama": "Budi Santoso", "prodi": "Teknik Informatika" }
```

Aturan: `nim` wajib 9 digit angka, `nama` maksimal 100 karakter, `prodi` maksimal 50 karakter.

**Response 201 Created:**
```json
{
  "success": true,
  "message": "Data berhasil ditambahkan",
  "data": { "id": 1 },
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

**Response 400 Bad Request** (field wajib kosong):
```json
{
  "success": false,
  "message": "Data tidak lengkap",
  "data": null,
  "errors": { "nim": "Field nim wajib diisi dengan teks" },
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

**Response 422 Unprocessable Entity** (format salah):
```json
{
  "success": false,
  "message": "Validasi gagal",
  "data": null,
  "errors": { "nim": "Format NIM tidak valid, harus 9 digit" },
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

**Response 409 Conflict:** `NIM sudah terdaftar`

**Response 401 Unauthorized:**
```json
{
  "success": false,
  "message": "Token tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

---

## PUT /mahasiswa?id={id}
Controller: `MahasiswaController::update()`

**Headers:**
- `Content-Type: application/json`
- `Authorization: Bearer <token>`

**Request Body** (semua field wajib dikirim):
```json
{ "nim": "123456789", "nama": "Budi Santoso", "prodi": "Sistem Informasi" }
```

**Response 200 OK:**
```json
{
  "success": true,
  "message": "Data berhasil diperbarui",
  "data": null,
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

**Response gagal:**
- 400 `ID wajib untuk PUT` / `Data tidak lengkap`
- 401 `Token tidak ditemukan` / `Token tidak valid atau kedaluwarsa`
- 404 `Data tidak ditemukan`
- 409 `NIM sudah digunakan mahasiswa lain`
- 422 `Validasi gagal`

---

## DELETE /mahasiswa?id={id}
Controller: `MahasiswaController::destroy()`

**Headers:** `Authorization: Bearer <token>`

**Response 200 OK:**
```json
{
  "success": true,
  "message": "Data berhasil dihapus",
  "data": null,
  "timestamp": "2026-10-24T10:51:00+07:00"
}
```

**Response gagal:**
- 400 `ID wajib untuk DELETE`
- 401 `Token tidak ditemukan` / `Token tidak valid atau kedaluwarsa`
- 404 `Data tidak ditemukan`
- 500 `Gagal menghapus data`