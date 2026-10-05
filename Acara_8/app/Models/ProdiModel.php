<?php
require_once __DIR__ . '/../Core/Database.php';

class ProdiModel
{
    public function all(): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM prodi ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM prodi WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)");
        $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
        ]);
    }

    public function update(int $id, array $data): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id");
        $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM prodi WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
