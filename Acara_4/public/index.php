<?php
require_once __DIR__ . '/../app/Models/Mahasiswa.php';

use App\Models\Mahasiswa;

// Membuat beberapa object Mahasiswa (data sementara, belum dari database)
$daftarMahasiswa = [
    new Mahasiswa("23001", "Budi Santoso", "Teknik Informatika"),
    new Mahasiswa("23002", "Siti Aminah", "Sistem Informasi"),
    new Mahasiswa("22003", "Agus Prasetyo", "Teknik Komputer"),
];

// Menentukan file View mana yang akan dijadikan konten
$content = __DIR__ . '/../app/Views/mahasiswa/index.php';

// Memuat layout utama (header, navbar, konten, footer)
require __DIR__ . '/../app/Views/layouts/main.php';