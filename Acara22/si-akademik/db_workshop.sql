CREATE DATABASE IF NOT EXISTS db_workshop;
USE db_workshop;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(15) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data contoh idempotent untuk pengujian; NIM 9 digit sesuai validasi API.
INSERT INTO mahasiswa (nim, nama, prodi)
SELECT '240101001', 'Budi Santoso', 'Teknik Informatika'
WHERE NOT EXISTS (SELECT 1 FROM mahasiswa WHERE nim = '240101001');
INSERT INTO mahasiswa (nim, nama, prodi)
SELECT '240101002', 'Siti Aminah', 'Sistem Informasi'
WHERE NOT EXISTS (SELECT 1 FROM mahasiswa WHERE nim = '240101002');

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
