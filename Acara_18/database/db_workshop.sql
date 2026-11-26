CREATE DATABASE IF NOT EXISTS db_workshop_acara18;
USE db_workshop_acara18;

CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(15) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tugas Praktik Mandiri 1: resource lain (buku)
CREATE TABLE buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    tahun_terbit SMALLINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
