<?php
require_once __DIR__ . '/../Core/Database.php';

class MahasiswaModel
{
    // Tugas Mandiri: parameter $keyword untuk pencarian berdasarkan nama/NIM
    public function all(?string $keyword = null): array
    {
        $pdo = Database::getInstance();

        $sql = "SELECT mahasiswa.*, prodi.nama AS prodi_nama
                FROM mahasiswa
                JOIN prodi ON mahasiswa.prodi_id = prodi.id";

        if ($keyword !== null && $keyword !== '') {
            $sql .= " WHERE mahasiswa.nama LIKE :kw1 OR mahasiswa.nim LIKE :kw2";
        }

        $sql .= " ORDER BY mahasiswa.id ASC";

        $stmt = $pdo->prepare($sql);

        if ($keyword !== null && $keyword !== '') {
            $stmt->execute([
                'kw1' => '%' . $keyword . '%',
                'kw2' => '%' . $keyword . '%',
            ]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)"
        );
        $stmt->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status' => $data['status'],
        ]);
    }

    public function update(int $id, array $data): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            "UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id"
        );
        $stmt->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status' => $data['status'],
            'id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}