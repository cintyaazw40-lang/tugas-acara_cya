# Acara 22 — Dokumentasi OpenAPI

Melanjutkan Acara21 dengan spesifikasi OpenAPI 3.0 di
[`docs/openapi.yaml`](docs/openapi.yaml). File memuat seluruh operasi API,
schema request/response, contoh status, dan skema Bearer JWT.

## Menjalankan dan melihat dokumentasi

1. Jalankan Apache/MySQL di Laragon dan import `si-akademik/db_workshop.sql`.
2. Buka `http://localhost/bkpm/Acara22/si-akademik/public`.
3. Import collection dan environment Local dari folder ini ke Postman.
4. Buka `docs/openapi.yaml` di Swagger Editor untuk melihat serta mencoba
   validasi spesifikasinya. Server lokal menjadi target `servers.url`.

## Praktik Mandiri

`openapi.yaml` melengkapi path `PUT`/`DELETE /mahasiswa` dan `/register`, dan
mendefinisikan `components.securitySchemes.bearerAuth` bertipe HTTP Bearer.
Path menggunakan URL resource router (`/mahasiswa`), bukan nama file PHP.
