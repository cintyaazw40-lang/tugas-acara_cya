# Test Case Black-box — Acara 23

Pengujian memakai equivalence partitioning melalui
`Acara23.postman_collection.json`. Jalankan seluruh request berurutan setelah
Apache/MySQL aktif dan SQL Acara23 dijalankan. Token dan ID uji disimpan
otomatis pada collection variable.

| ID | Operasi/skenario | Partisi/input | Diharapkan | Aktual | Hasil |
|---|---|---|---|---:|---|
| TC-01 | POST mahasiswa valid | NIM baru 9 digit, field lengkap, token tersedia | 201 | 201 | Pass |
| TC-02 | POST tanpa token | Header Authorization tidak ada | 401 | 401 | Pass |
| TC-03 | POST NIM duplikat | NIM sudah ada di database | 422, errors.nim | 422 | Pass |
| TC-04 | PUT valid | ID ada, body valid, NIM tidak dipakai data lain | 200 | 200 | Pass |
| TC-05 | PUT tanpa ID | Query `id` tidak dikirim | 400 | 400 | Pass |
| TC-06 | PUT ID salah format | `id=abc` | 400 | 400 | Pass |
| TC-07 | PUT ID tidak ditemukan | `id=999999` | 404 | 404 | Pass |
| TC-08 | PUT NIM duplikat | NIM milik baris lain | 422, errors.nim | 422 | Pass |
| TC-09 | PUT format NIM salah | NIM bukan 9 digit | 422, errors.nim | 422 | Pass |
| TC-10 | DELETE tanpa ID | Query `id` tidak dikirim | 400 | 400 | Pass |
| TC-11 | DELETE ID salah format | `id=abc` | 400 | 400 | Pass |
| TC-12 | DELETE ID tidak ditemukan | `id=999999` | 404 | 404 | Pass |
| TC-13 | DELETE valid | ID uji yang baru dibuat | 200 | 200 | Pass |
| TC-14 | GET setelah delete | ID yang sudah dihapus | 404 | 404 | Pass |

Hasil di atas diverifikasi terhadap API lokal Laragon. Collection memeriksa
status HTTP sebagai hasil uji (termasuk respons error yang memang diharapkan).
Perbaikan yang diuji: pemeriksaan NIM duplikat pada store/update dan unique
index `uq_mahasiswa_nim` sebagai perlindungan database bila data lama tidak
memiliki duplikat.
