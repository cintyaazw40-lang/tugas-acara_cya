# Dokumentasi Endpoint - Acara 18 (JSON Encoding dan HTTP Status Code)

Base URL: `http://localhost/tugas%20acara_cya/Acara_18/public`

## Format response (envelope)
```json
{
    "success": false,
    "message": "Format NIM tidak valid, harus 9 digit",
    "data": null,
    "errors": { "nim": "NIM harus terdiri dari 9 digit" },
    "timestamp": "2026-11-01T14:18:14+00:00"
}
```
`errors` (Tugas Mandiri 1) berisi pesan kesalahan per field. `JSON_PRETTY_PRINT` hanya aktif bila `APP_ENV` = `development` (`app/config/app.php`, Tugas Mandiri 2).

## Endpoint
| Method | Endpoint | Fungsi | Status |
|---|---|---|---|
| GET | /mahasiswa | Semua mahasiswa | 200 |
| GET | /mahasiswa?id=1 | Satu mahasiswa | 200, 404 |
| POST | /mahasiswa | Tambah mahasiswa | 201, 400, 409, 422 |
| PUT | /mahasiswa?id=1 | Ubah mahasiswa | 200, 400, 404, 409, 422 |
| DELETE | /mahasiswa?id=1 | Hapus mahasiswa | 200, 404 |
| GET/POST/PUT/DELETE | /buku | CRUD buku | 200, 201, 400, 404, 422 |

## Kode status
| Kode | Kapan muncul |
|---|---|
| 200 / 201 | Berhasil / data dibuat |
| 400 | Field wajib kosong atau body bukan JSON |
| 404 | Id atau endpoint tidak ditemukan |
| 405 | Method HTTP tidak diizinkan |
| 409 | NIM duplikat |
| 422 | Format NIM bukan 9 digit |

> Catatan: contoh NIM pada Acara 17 (7 digit) tidak lagi valid di Acara 18, gunakan NIM 9 digit, misalnya `220100123`.
