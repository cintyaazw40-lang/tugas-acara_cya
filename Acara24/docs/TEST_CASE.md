# Pengujian Integrasi Frontend — Acara 24

Jalankan `public/views/index.html` melalui Apache Laragon dan gunakan
Postman collection untuk data API. Hasil yang terlihat:

| Skenario | Hasil yang diharapkan |
|---|---|
| Buka halaman saat request berjalan | Teks `Memuat data...` tampil |
| API mengembalikan mahasiswa | Baris NIM/nama/prodi ditampilkan |
| API mengembalikan array kosong | Teks `Belum ada data` tampil |
| API mengembalikan status error | Pesan error API tampil di halaman |
| Server/API tidak dapat dijangkau | Pesan gagal memuat data tampil |
| OPTIONS preflight ke API | HTTP 204 dan header CORS tersedia |

Baris tabel dibangun dengan DOM dan `textContent`; nilai dari API tidak
dieksekusi sebagai markup HTML. Tombol Muat ulang mengulangi request.

Verifikasi Laragon: halaman berhasil memuat lima baris dari API; preflight
`OPTIONS` mengembalikan `204` dengan header `Access-Control-Allow-Origin: *`.
State kosong tercakup sebagai skenario manual dengan mengosongkan tabel pada
database praktikum.
