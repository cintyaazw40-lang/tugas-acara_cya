# Acara 24 — Integrasi API dengan Frontend

Melanjutkan Acara23. Halaman `public/views/index.html` mengambil daftar
mahasiswa dengan Fetch API dari backend PHP, menggunakan CORS untuk request
lintas-origin, dan menampilkan loading, error, serta keadaan data kosong.

## Menjalankan

1. Aktifkan Apache/MySQL di Laragon; import `si-akademik/db_workshop.sql`.
2. Buka `http://localhost/bkpm/Acara24/si-akademik/public/views/index.html`.
3. Import collection Postman dan environment Local untuk menguji API.
4. Untuk menguji state kosong, hapus seluruh data mahasiswa dan muat ulang.

## Praktik Mandiri

View menampilkan “Memuat data...” sebelum fetch selesai dan “Belum ada data”
ketika array data kosong. Response HTTP gagal dan masalah jaringan ditampilkan
sebagai pesan error. Semua row dibuat melalui DOM `textContent` agar data API
tidak diperlakukan sebagai HTML.
