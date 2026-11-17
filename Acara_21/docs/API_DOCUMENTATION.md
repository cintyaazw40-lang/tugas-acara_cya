# Dokumentasi API - Sistem Informasi Akademik

Dokumentasi endpoint REST API (PHP Native, arsitektur MVC) untuk Acara 21 - Workshop Sistem Informasi Web Server.

**Base URL:** `http://localhost/tugas%20acara_cya/Acara_21/public`  
**Format data:** JSON (`Content-Type: application/json`)  
**Autentikasi:** Bearer Token (JWT HS256)

## 1. Peta Router

| Resource (URL) | Controller | Keterangan |
|---|---|---|
| `/mahasiswa` | `MahasiswaController` | CRUD data mahasiswa (index/store/update/destroy) |
| `/login` | `AuthController::login` | Autentikasi pengguna, menghasilkan token |
| `/register` | `AuthController::register` | Pendaftaran pengguna baru |

## 2. Ringkasan Seluruh Endpoint

| Method | URL | Controller | Autentikasi | Keterangan |
|---|---|---|---|---|
| POST | `/register` | `AuthController::register()` | Publik | Registrasi pengguna |
| POST | `/login` | `AuthController::login()` | Publik | Login, menghasilkan token JWT |
| GET | `/mahasiswa` | `MahasiswaController::index()` | Publik | Ambil semua data mahasiswa |
| GET | `/mahasiswa?id={id}` | `MahasiswaController::index()` | Publik | Ambil satu mahasiswa |
| POST | `/mahasiswa` | `MahasiswaController::store()` | Bearer Token | Tambah mahasiswa |
| PUT | `/mahasiswa?id={id}` | `MahasiswaController::update()` | Bearer Token | Ubah mahasiswa |
| DELETE | `/mahasiswa?id={id}` | `MahasiswaController::destroy()` | Bearer Token | Hapus mahasiswa |

## 3. Format Response Standar

Seluruh response (sukses maupun gagal) dibentuk oleh `Response::json()` dengan struktur seragam:

| Field | Tipe | Keterangan |
|---|---|---|
| `success` | boolean | `true` jika berhasil, `false` jika gagal |
| `message` | string | Pesan hasil proses |
| `data` | object/array/null | Data hasil; `null` jika tidak ada |
| `errors` | object | Opsional, hanya pada error validasi: pesan per field |
| `timestamp` | string | Waktu response (ISO 8601) |

## 4. Autentikasi

1. Daftarkan akun melalui `POST /register`.
2. Login melalui `POST /login`, salin nilai `data.token`.
3. Kirim token pada header endpoint terproteksi:

```
Authorization: Bearer <token>
```

Token berisi payload `id`, `role`, dan `exp` (waktu kedaluwarsa). Token yang hilang, diubah, atau kedaluwarsa menghasilkan **401**.

## 5. Detail Endpoint

### 5.1 POST /register

**Controller:** `AuthController::register()`  
**Deskripsi:** Mendaftarkan pengguna baru. Password disimpan dalam bentuk hash (password_hash). Hanya menerima method POST.  
**Autentikasi:** Publik (tanpa token)

**Headers:**

- `Content-Type: application/json`

**Parameter / Field:**

| Nama | Tipe | Wajib | Keterangan |
|---|---|---|---|
| username | string | Ya | Unik, maks. 50 karakter |
| email | string | Ya | Unik, format email valid |
| password | string | Ya | Disimpan sebagai hash |

**Contoh Request Body:**

```json
{
    "username": "admin",
    "email": "admin@example.com",
    "password": "rahasia123"
}
```

**Contoh Response:**

*201 Created - Registrasi berhasil*

```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "id": "1"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*400 Bad Request - Body bukan JSON valid*

```json
{
    "success": false,
    "message": "Body harus berupa JSON yang valid",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*400 Bad Request - Ada field yang kosong*

```json
{
    "success": false,
    "message": "Username, email, dan password wajib diisi",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*422 Unprocessable Entity - Format email tidak valid*

```json
{
    "success": false,
    "message": "Format email tidak valid",
    "data": null,
    "errors": {
        "email": "Format email tidak valid"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*409 Conflict - Username atau email sudah terdaftar*

```json
{
    "success": false,
    "message": "Username atau email sudah terdaftar",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*500 Internal Server Error - Kegagalan database*

```json
{
    "success": false,
    "message": "Terjadi kesalahan pada server",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

### 5.2 POST /login

**Controller:** `AuthController::login()`  
**Deskripsi:** Memverifikasi username dan password, lalu menghasilkan token JWT (HS256). Hanya menerima method POST.  
**Autentikasi:** Publik (tanpa token)

**Headers:**

- `Content-Type: application/json`

**Parameter / Field:**

| Nama | Tipe | Wajib | Keterangan |
|---|---|---|---|
| username | string | Ya | Username terdaftar |
| password | string | Ya | Password asli (bukan hash) |

**Contoh Request Body:**

```json
{
    "username": "admin",
    "password": "rahasia123"
}
```

**Contoh Response:**

*200 OK - Login berhasil*

```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "token": "eyJhbGciOiJIUzI1NiIs...xxxxx",
        "role": "user"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*400 Bad Request - Username/password kosong atau body bukan JSON*

```json
{
    "success": false,
    "message": "Username dan password wajib diisi",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*401 Unauthorized - Username/password salah*

```json
{
    "success": false,
    "message": "Username atau password salah",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

### 5.3 GET /mahasiswa

**Controller:** `MahasiswaController::index()`  
**Deskripsi:** Menampilkan seluruh data mahasiswa (urut id terbaru). Tambahkan query ?id=<id> untuk satu data.  
**Autentikasi:** Publik (tanpa token)

**Headers:**

- `Accept: application/json`

**Parameter / Field:**

| Nama | Tipe | Wajib | Keterangan |
|---|---|---|---|
| id (query) | integer | Tidak | Jika diisi, hanya mengembalikan satu mahasiswa |

**Contoh Response:**

*200 OK - Semua data (tanpa ?id)*

```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": [
        {
            "id": 2,
            "nim": "220100124",
            "nama": "Siti Rahma",
            "prodi": "Teknik Informatika",
            "created_at": "2026-11-17 09:35:00"
        },
        {
            "id": 1,
            "nim": "220100123",
            "nama": "Andi Saputra",
            "prodi": "Teknik Informatika",
            "created_at": "2026-11-17 09:30:00"
        }
    ],
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*200 OK - Satu data (?id=1)*

```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": {
        "id": 1,
        "nim": "220100123",
        "nama": "Andi Saputra",
        "prodi": "Teknik Informatika",
        "created_at": "2026-11-17 09:30:00"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*404 Not Found - ?id tidak ditemukan*

```json
{
    "success": false,
    "message": "Data tidak ditemukan",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

### 5.4 POST /mahasiswa

**Controller:** `MahasiswaController::store()`  
**Deskripsi:** Menambahkan data mahasiswa baru. Token diperiksa lebih dulu, baru body divalidasi.  
**Autentikasi:** Terproteksi - Authorization: Bearer <token>

**Headers:**

- `Content-Type: application/json`
- `Authorization: Bearer <token>`

**Parameter / Field:**

| Nama | Tipe | Wajib | Keterangan |
|---|---|---|---|
| nim | string | Ya | Harus tepat 9 karakter, tidak boleh duplikat |
| nama | string | Ya | Maks. 100 karakter |
| prodi | string | Ya | Maks. 50 karakter |

**Contoh Request Body:**

```json
{
    "nim": "220100123",
    "nama": "Andi Saputra",
    "prodi": "Teknik Informatika"
}
```

**Contoh Response:**

*201 Created - Data berhasil dibuat*

```json
{
    "success": true,
    "message": "Data berhasil ditambahkan",
    "data": {
        "id": "1"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*401 Unauthorized - Token tidak ada*

```json
{
    "success": false,
    "message": "Token tidak ditemukan",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*401 Unauthorized - Token salah / kedaluwarsa*

```json
{
    "success": false,
    "message": "Token tidak valid atau kedaluwarsa",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*400 Bad Request - Body bukan JSON valid*

```json
{
    "success": false,
    "message": "Body harus berupa JSON yang valid",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*400 Bad Request - Field kosong*

```json
{
    "success": false,
    "message": "Field nim, nama, dan prodi wajib diisi",
    "data": null,
    "errors": {
        "nim": "Field nim wajib diisi"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*422 Unprocessable Entity - NIM bukan 9 digit*

```json
{
    "success": false,
    "message": "Format NIM tidak valid, harus 9 digit",
    "data": null,
    "errors": {
        "nim": "NIM harus terdiri dari 9 digit"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*409 Conflict - NIM sudah terdaftar*

```json
{
    "success": false,
    "message": "NIM sudah terdaftar",
    "data": null,
    "errors": {
        "nim": "NIM sudah terdaftar"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

### 5.5 PUT /mahasiswa?id={id}

**Controller:** `MahasiswaController::update($id)`  
**Deskripsi:** Memperbarui data mahasiswa berdasarkan id pada query string. Body divalidasi dengan aturan yang sama seperti POST.  
**Autentikasi:** Terproteksi - Authorization: Bearer <token>

**Headers:**

- `Content-Type: application/json`
- `Authorization: Bearer <token>`

**Parameter / Field:**

| Nama | Tipe | Wajib | Keterangan |
|---|---|---|---|
| id (query) | integer | Ya | Id mahasiswa yang diubah |
| nim | string | Ya | 9 karakter, tidak boleh duplikat |
| nama | string | Ya | Nama baru |
| prodi | string | Ya | Prodi baru |

**Contoh Request Body:**

```json
{
    "nim": "220100123",
    "nama": "Andi Saputra Putra",
    "prodi": "Sistem Informasi"
}
```

**Contoh Response:**

*200 OK - Berhasil diperbarui*

```json
{
    "success": true,
    "message": "Data berhasil diperbarui",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*401 Unauthorized - Token tidak ada*

```json
{
    "success": false,
    "message": "Token tidak ditemukan",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*401 Unauthorized - Token salah / kedaluwarsa*

```json
{
    "success": false,
    "message": "Token tidak valid atau kedaluwarsa",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*400 Bad Request - Parameter ?id tidak disertakan*

```json
{
    "success": false,
    "message": "Parameter id wajib disertakan (?id=)",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*404 Not Found - Id tidak ditemukan*

```json
{
    "success": false,
    "message": "Data tidak ditemukan",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*400 Bad Request - Body bukan JSON valid*

```json
{
    "success": false,
    "message": "Body harus berupa JSON yang valid",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*400 Bad Request - Field kosong*

```json
{
    "success": false,
    "message": "Field nim, nama, dan prodi wajib diisi",
    "data": null,
    "errors": {
        "nim": "Field nim wajib diisi"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*422 Unprocessable Entity - NIM bukan 9 digit*

```json
{
    "success": false,
    "message": "Format NIM tidak valid, harus 9 digit",
    "data": null,
    "errors": {
        "nim": "NIM harus terdiri dari 9 digit"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*409 Conflict - NIM sudah terdaftar*

```json
{
    "success": false,
    "message": "NIM sudah terdaftar",
    "data": null,
    "errors": {
        "nim": "NIM sudah terdaftar"
    },
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

### 5.6 DELETE /mahasiswa?id={id}

**Controller:** `MahasiswaController::destroy($id)`  
**Deskripsi:** Menghapus data mahasiswa berdasarkan id pada query string.  
**Autentikasi:** Terproteksi - Authorization: Bearer <token>

**Headers:**

- `Authorization: Bearer <token>`

**Parameter / Field:**

| Nama | Tipe | Wajib | Keterangan |
|---|---|---|---|
| id (query) | integer | Ya | Id mahasiswa yang dihapus |

**Contoh Response:**

*200 OK - Berhasil dihapus*

```json
{
    "success": true,
    "message": "Data berhasil dihapus",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*401 Unauthorized - Token tidak ada*

```json
{
    "success": false,
    "message": "Token tidak ditemukan",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*401 Unauthorized - Token salah / kedaluwarsa*

```json
{
    "success": false,
    "message": "Token tidak valid atau kedaluwarsa",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*400 Bad Request - Parameter ?id tidak disertakan*

```json
{
    "success": false,
    "message": "Parameter id wajib disertakan (?id=)",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*404 Not Found - Id tidak ditemukan*

```json
{
    "success": false,
    "message": "Data tidak ditemukan",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

## 6. Error dari Router (berlaku untuk semua endpoint)

*404 Not Found - Resource tidak ada di peta Router*

```json
{
    "success": false,
    "message": "Endpoint tidak ditemukan",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

*405 Method Not Allowed - Method tidak didukung (/login dan /register hanya POST)*

```json
{
    "success": false,
    "message": "Method tidak diizinkan",
    "data": null,
    "timestamp": "2026-11-17T10:00:00+07:00"
}
```

## 7. Ringkasan Kode Status HTTP

| Kode | Nama | Digunakan untuk |
|---|---|---|
| 200 | OK | GET/PUT/DELETE/login berhasil |
| 201 | Created | Data baru berhasil dibuat (POST) |
| 400 | Bad Request | Body bukan JSON valid, field wajib kosong, atau parameter ?id tidak ada |
| 401 | Unauthorized | Token tidak ada, tidak valid, atau kedaluwarsa; login gagal |
| 404 | Not Found | Data atau endpoint tidak ditemukan |
| 405 | Method Not Allowed | Method HTTP tidak didukung |
| 409 | Conflict | Data duplikat (NIM, username, atau email sudah ada) |
| 422 | Unprocessable Entity | Format data tidak lolos validasi (NIM bukan 9 digit, email tidak valid) |
| 500 | Internal Server Error | Kegagalan tak terduga di server |