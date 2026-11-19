# Dokumentasi API — Acara 22

Base URL: `http://localhost/bkpm/Acara22/si-akademik/public`

## Peta Endpoint dan Controller

| Resource | Method | Controller/Aksi | Auth | Fungsi |
|---|---|---|---|---|
| `/register` | POST | `AuthController::register()` | Publik | Registrasi akun |
| `/login` | POST | `AuthController::login()` | Publik | Login dan penerbitan JWT |
| `/mahasiswa` | GET | `MahasiswaController::index()` | Publik | Membaca semua data |
| `/mahasiswa?id={id}` | GET | `MahasiswaController::index()` | Publik | Membaca satu data |
| `/mahasiswa` | POST | `MahasiswaController::store()` | Bearer JWT | Menambah data |
| `/mahasiswa?id={id}` | PUT | `MahasiswaController::update()` | Bearer JWT | Memperbarui data |
| `/mahasiswa?id={id}` | DELETE | `MahasiswaController::destroy()` | Bearer JWT | Menghapus data |

Semua response API memakai envelope `success`, `message`, `data`, `errors`,
dan `timestamp`. Sertakan `Content-Type: application/json` untuk request JSON.

## Autentikasi

### POST `/register`

Request:

```json
{"username":"linda","email":"linda@example.com","password":"rahasia123"}
```

`201` berisi ID pengguna. Email tidak valid menghasilkan `422`; username/email
terpakai menghasilkan `409`.

### POST `/login`

Request: `{"username":"linda","password":"rahasia123"}`

Sukses `200` mengembalikan `data.token`, `data.expires_in` (300 detik), dan
`data.role`. Kredensial salah menghasilkan `401`.

## Mahasiswa

### GET `/mahasiswa[?id={id}]`

Publik. Tanpa ID mengembalikan array; dengan ID mengembalikan satu objek.
Format ID tidak valid menghasilkan `400`; ID yang tidak ditemukan `404`.

### POST `/mahasiswa`

Bearer JWT diperlukan. Body:
`{"nim":"240101019","nama":"Mahasiswa API","prodi":"Teknik Informatika"}`.
Sukses `201`; body/field tidak lengkap `400`; format atau panjang salah `422`;
token hilang/tidak valid `401`.

### PUT `/mahasiswa?id={id}`

Bearer JWT diperlukan. Menggunakan body yang sama dengan POST. Sukses `200`;
ID tidak valid `400`; data tidak ditemukan `404`; validasi mengikuti POST.

### DELETE `/mahasiswa?id={id}`

Bearer JWT diperlukan. Sukses `200`; ID tidak valid `400`; data tidak
ditemukan `404`; token hilang/tidak valid `401`.

## Contoh Envelope Error

```json
{
  "success": false,
  "message": "Validasi data gagal.",
  "data": null,
  "errors": {"nim":["Format NIM tidak valid, harus 9 digit."]},
  "timestamp": "2026-11-18T12:00:00+00:00"
}
```

Postman collection berisi contoh request dan test status otomatis, sehingga
dapat dipakai untuk membuat/meninjau dokumentasi Collection Postman.
