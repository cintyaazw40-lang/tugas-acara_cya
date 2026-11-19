# Acara 25 — Authentication, Authorization, dan Dashboard Admin

Melanjutkan Acara24 dengan login UI, dashboard ringkasan, dan RBAC. Admin dapat
melakukan CRUD; role `user` hanya dapat membaca daftar. Aturan otorisasi
ditegakkan di backend dan juga tercermin pada tombol frontend.

## Menjalankan

1. Aktifkan Apache/MySQL di Laragon dan import `si-akademik/db_workshop.sql`.
2. Buka `http://localhost/bkpm/Acara25/si-akademik/public/views/login.html`.
3. Akun admin bawaan: `admin` / `admin123`. Ganti password untuk pemakaian
   selain praktikum; akun biasa dapat dibuat melalui endpoint `/register`.
4. Import collection dan environment Local; gunakan request RBAC untuk
   memastikan role user memperoleh `403` saat mencoba mengubah data.

## Praktik Mandiri

- Dashboard menyediakan Logout yang menghapus token/role dari localStorage.
- Tombol Tambah/Ubah/Hapus hanya terlihat bagi admin; server tetap wajib
  menolak operasi non-admin dengan `403`.
- Dashboard memuat jumlah mahasiswa dari API dan menyediakan alur CRUD admin.
- Collection Postman menguji CRUD admin, serta penolakan user.

JWT stateless yang dihapus dari browser tidak otomatis dicabut di server; token
tetap berlaku sampai lima menit kecuali blacklist/stateful ditambahkan.
