# Acara 23 — Manual Testing dan Black-box Testing

Melanjutkan Acara22 dengan rencana uji equivalence partitioning di
[`docs/TEST_CASE.md`](docs/TEST_CASE.md) dan skenario yang dapat dijalankan
berurutan melalui Postman.

## Menjalankan dan menguji

1. Aktifkan Apache/MySQL di Laragon dan import `si-akademik/db_workshop.sql`.
2. Import `Acara23.postman_collection.json` serta environment Local.
3. Jalankan collection berurutan. Register/login menyimpan token; request
   CRUD menyimpan ID sementara. Beberapa skenario invalid sengaja menghasilkan
   4xx dan test Postman memeriksa status tersebut sebagai hasil yang benar.
4. Catat status aktual di `docs/TEST_CASE.md`; semua skenario harus Pass.

## Praktik Mandiri

Test tambahan update/delete menguji ID hilang, format ID salah, data tidak ada,
body/field salah, token hilang, operasi berhasil, serta NIM duplikat. NIM
duplikat ditolak `422` baik pada insert maupun update. SQL menambahkan unique
index jika data lama tidak memiliki duplikat; jika data lama sudah terduplikasi,
migration membiarkan data tersebut tetap utuh dan API tetap memvalidasi NIM.
