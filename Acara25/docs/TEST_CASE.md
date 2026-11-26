# Laporan Pengujian API - Acara 23

Pengujian manual terhadap `AuthController` dan `MahasiswaController` menggunakan Postman dan teknik *equivalence partitioning*.

- **Base URL:** `http://localhost/Code/Acara23/public`
- **Alat uji:** Postman (Collection Runner)
- **Catatan:** token JWT berlaku 5 menit; ID dikirim lewat query string (`?id=`)

## 1. Ruang Lingkup Pengujian

| Method | Endpoint | Auth |
|---|---|---|
| POST | /register | Tidak |
| POST | /login | Tidak |
| GET | /mahasiswa dan /mahasiswa?id={id} | Tidak |
| POST | /mahasiswa | Ya |
| PUT | /mahasiswa?id={id} | Ya |
| DELETE | /mahasiswa?id={id} | Ya |

## 2. Data Uji Awal

- User penguji: `penguji` / `rahasia123` (dibuat lewat `POST /register`)
- Mahasiswa A, B, C dibuat lewat `POST /mahasiswa` dengan NIM acak 9 digit. A dan B dipakai untuk uji update, C khusus untuk uji hapus.

## 3. Hasil Pengujian

### 3.1 Test case dari modul (TC-01 s/d TC-06)

| ID | Skenario | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| TC-01 | NIM valid dan lengkap (`MahasiswaController::store`) | nim 9 digit valid, nama, prodi terisi | 201 Created | 201 Created | Pass |
| TC-02 | NIM kosong (partisi tidak valid) | nim="" | 400/422 | 400 Bad Request | Pass |
| TC-03 | NIM duplikat (partisi tidak valid) | nim milik data lain | 422 Unprocessable | 409 Conflict | **Fail** → Pass setelah perbaikan |
| TC-04 | Login kredensial benar (`AuthController::login`) | username dan password benar | 200 OK, token diterima | 200 OK, token diterima | Pass |
| TC-05 | Login kredensial salah | password salah | 401 Unauthorized | 401 Unauthorized | Pass |
| TC-06 | Akses store tanpa token (`Auth::requireAuth`) | tanpa header Authorization | 401 Unauthorized | 401 Unauthorized | Pass |

### 3.2 Test case baru: update dan destroy (Tugas Mandiri)

| ID | Skenario | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| TC-07 | Update data valid (`::update`) | PUT `?id={A}`, nim valid milik A, nama diubah | 200 OK | 200 OK | Pass |
| TC-08 | Update, ID tidak ditemukan | PUT `?id=99999`, body valid | 404 Not Found | 404 Not Found | Pass |
| TC-09 | Update, format ID tidak valid | PUT `?id=abc`, body valid | 400 Bad Request | 400 Bad Request | Pass |
| TC-10 | Update, NIM kosong | PUT `?id={A}`, nim="" | 400 Bad Request | 400 Bad Request | Pass |
| TC-11 | Update, format NIM salah | PUT `?id={A}`, nim="2201001" (7 digit) | 422 Unprocessable | 422 Unprocessable | Pass |
| TC-12 | Update, NIM duplikat milik mahasiswa lain | PUT `?id={A}`, nim milik B | 422 Unprocessable | 409 Conflict | **Fail** → Pass setelah perbaikan |
| TC-13 | Update tanpa token | PUT `?id={A}`, tanpa header Authorization | 401 Unauthorized | 401 Unauthorized | Pass |
| TC-14 | Destroy, ID valid (`::destroy`) | DELETE `?id={C}` | 200 OK | 200 OK | Pass |
| TC-15 | Destroy, ID sudah tidak ada | DELETE `?id={C}` (diulang) | 404 Not Found | 404 Not Found | Pass |
| TC-16 | Destroy, format ID tidak valid | DELETE `?id=abc` | 400 Bad Request | 400 Bad Request | Pass |
| TC-17 | Destroy tanpa token | DELETE `?id={A}`, tanpa header Authorization | 401 Unauthorized | 401 Unauthorized | Pass |

## 4. Rekap

| Tahap | Total | Pass | Fail |
|---|---|---|---|
| Pengujian awal | 17 | 15 | 2 (TC-03, TC-12) |
| Retest setelah perbaikan | 17 | 17 | 0 |

## 5. Analisis dan Perbaikan

### TC-03 dan TC-12: kode status NIM duplikat

- **Temuan:** respons NIM duplikat adalah `409 Conflict`, sedangkan hasil yang diharapkan pada modul adalah `422 Unprocessable`.
- **Penyebab:** `MahasiswaController::store()` dan `::update()` memakai kode 409 saat `nimExists()` bernilai true. Padahal kegagalan validasi di controller yang sama sudah memakai 422.
- **Lapisan yang diperbaiki:** Controller (`app/controllers/MahasiswaController.php`).

**Sebelum:**

```php
// store()
Response::json(409, false, 'NIM sudah terdaftar');

// update()
Response::json(409, false, 'NIM sudah digunakan mahasiswa lain');
```

**Sesudah:**

```php
// store()
Response::json(422, false, 'NIM sudah terdaftar');

// update()
Response::json(422, false, 'NIM sudah digunakan mahasiswa lain');
```

- **Retest:** TC-03 dan TC-12 dijalankan ulang bersama seluruh collection. Hasilnya 422, status **Pass**. Seluruh test case lain tetap Pass, sehingga tidak ada regresi.

## 6. Kesimpulan

Dari 17 test case, 2 di antaranya Fail pada pengujian awal karena kode status duplikasi NIM tidak sesuai ketentuan (409 dan seharusnya 422). Setelah controller diperbaiki dan dilakukan retest, seluruh 17 test case berstatus Pass.