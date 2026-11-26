<?php

class Mahasiswa
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        return $this->db
            ->query("SELECT * FROM mahasiswa ORDER BY id DESC")
            ->fetchAll();
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM mahasiswa WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    // BARU Acara 20: try-catch pada lapisan model
    public function create(array $data): int
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO mahasiswa (nim, nama, prodi)
                 VALUES (:nim, :nama, :prodi)"
            );

            $stmt->execute([
                'nim' => $data['nim'],
                'nama' => $data['nama'],
                'prodi' => $data['prodi'],
            ]);

            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE mahasiswa
             SET nim = :nim, nama = :nama, prodi = :prodi
             WHERE id = :id"
        );

        $stmt->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'prodi' => $data['prodi'],
            'id' => $id,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM mahasiswa WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function nimExists(string $nim, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim";

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->db->prepare($sql);
        $params = ['nim' => $nim];

        if ($excludeId !== null) {
            $params['id'] = $excludeId;
        }

        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }
}