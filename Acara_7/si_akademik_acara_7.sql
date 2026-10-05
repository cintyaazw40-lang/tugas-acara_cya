-- ============================================
-- Database Acara 7: si_akademik
-- Import file ini lewat phpMyAdmin > Import
-- ============================================

CREATE DATABASE IF NOT EXISTS si_akademik_acara7;
USE si_akademik_acara7;

-- ============================================
-- Tabel prodi
-- ============================================
CREATE TABLE prodi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- Tabel mahasiswa
-- ============================================
CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    prodi_id INT NOT NULL,
    angkatan YEAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_mahasiswa_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ============================================
-- Tabel matakuliah
-- ============================================
CREATE TABLE matakuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(150) NOT NULL,
    sks TINYINT NOT NULL,
    prodi_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_matakuliah_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ============================================
-- Data awal (seeding)
-- ============================================
INSERT INTO prodi (kode, nama) VALUES
('TI', 'Teknik Informatika'),
('SI', 'Sistem Informasi'),
('TK', 'Teknik Komputer');

INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan) VALUES
('2401001', 'Budi Santoso', 'budi@email.com', 1, 2024),
('2401002', 'Ani Wijaya', 'ani@email.com', 1, 2024),
('2402001', 'Citra Lestari', 'citra@email.com', 2, 2024);

INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES
('TI101', 'Pemrograman Dasar', 3, 1),
('TI102', 'Basis Data', 3, 1),
('SI101', 'Pengantar SI', 2, 2);

-- ============================================
-- TUGAS MANDIRI:
-- Tambah kolom status (ENUM: aktif, cuti, lulus), default 'aktif',
-- lalu update data yang sudah ada.
-- ============================================
ALTER TABLE mahasiswa
    ADD COLUMN status ENUM('aktif', 'cuti', 'lulus') NOT NULL DEFAULT 'aktif' AFTER angkatan;

UPDATE mahasiswa SET status = 'aktif' WHERE status IS NULL OR status = '';
