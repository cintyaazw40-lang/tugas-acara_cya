CREATE DATABASE IF NOT EXISTS db_workshop;
USE db_workshop;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(15) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_mahasiswa_nim (nim)
);

-- Tambahkan unique index juga bila database telah dibuat oleh Acara19–22.
SET @nim_index_exists = (
    SELECT COUNT(*)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'mahasiswa'
      AND index_name = 'uq_mahasiswa_nim'
);
SET @nim_duplicates_exist = (
    SELECT COUNT(*)
    FROM (
        SELECT nim
        FROM mahasiswa
        GROUP BY nim
        HAVING COUNT(*) > 1
    ) AS duplicate_nim
);
SET @nim_index_sql = IF(
    @nim_index_exists = 0 AND @nim_duplicates_exist = 0,
    'ALTER TABLE mahasiswa ADD UNIQUE INDEX uq_mahasiswa_nim (nim)',
    'SELECT 1'
);
PREPARE nim_index_stmt FROM @nim_index_sql;
EXECUTE nim_index_stmt;
DEALLOCATE PREPARE nim_index_stmt;

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

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$0FsnpIAa8zJwX5XlwKuGvekjR3Ao2/XAKf8CcqlPimIxUnv3MsYwG', 'admin')
ON DUPLICATE KEY UPDATE
    email = VALUES(email),
    password = VALUES(password),
    role = 'admin';
