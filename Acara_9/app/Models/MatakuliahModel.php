<?php
require_once __DIR__ . '/../Core/Database.php';

class MatakuliahModel
{
    public function all(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT matakuliah.*, prodi.nama AS prodi_nama
                FROM matakuliah
                JOIN prodi ON matakuliah.prodi_id = prodi.id
                ORDER BY matakuliah.id ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM matakuliah WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): void
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES (:kode, :nama, :sks, :prodi_id)"
        );
        $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks' => $data['sks'],
            'prodi_id' => $data['prodi_id'],
        ]);
    }

    public function update(int $id, array $data): void
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare(
            "UPDATE matakuliah SET kode = :kode, nama = :nama, sks = :sks, prodi_id = :prodi_id WHERE id = :id"
        );
        $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks' => $data['sks'],
            'prodi_id' => $data['prodi_id'],
            'id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("DELETE FROM matakuliah WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
