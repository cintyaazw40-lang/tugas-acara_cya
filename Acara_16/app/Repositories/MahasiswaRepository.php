<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/Mahasiswa.php';

class MahasiswaRepository
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    public function all(?string $keyword = null): array
    {
        $sql = "SELECT mahasiswa.*, prodi.nama AS prodi_nama
                FROM mahasiswa
                JOIN prodi ON mahasiswa.prodi_id = prodi.id";

        if ($keyword !== null && $keyword !== '') {
            $sql .= " WHERE mahasiswa.nama LIKE :kw1 OR mahasiswa.nim LIKE :kw2";
        }
        $sql .= " ORDER BY mahasiswa.id ASC";

        $stmt = $this->pdo->prepare($sql);

        if ($keyword !== null && $keyword !== '') {
            $stmt->execute(['kw1' => '%' . $keyword . '%', 'kw2' => '%' . $keyword . '%']);
        } else {
            $stmt->execute();
        }

        $rows = $stmt->fetchAll();
        $result = [];

        foreach ($rows as $row) {
            $result[] = [
                'mahasiswa' => Mahasiswa::fromArray($row),
                'prodi_nama' => $row['prodi_nama'],
            ];
        }
        return $result;
    }

    public function find(int $id): ?Mahasiswa
    {
        $stmt = $this->pdo->prepare("SELECT * FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Mahasiswa::fromArray($row) : null;
    }

    public function existsByNim(string $nim, ?int $ignoreId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim";
        $params = ['nim' => $nim];

        if ($ignoreId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $ignoreId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(Mahasiswa $mahasiswa): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)"
        );
        $stmt->execute([
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'email' => $mahasiswa->getEmail(),
            'prodi_id' => $mahasiswa->getProdiId(),
            'angkatan' => $mahasiswa->getAngkatan(),
            'status' => $mahasiswa->getStatus(),
        ]);
    }

    public function update(int $id, Mahasiswa $mahasiswa): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id"
        );
        $stmt->execute([
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'email' => $mahasiswa->getEmail(),
            'prodi_id' => $mahasiswa->getProdiId(),
            'angkatan' => $mahasiswa->getAngkatan(),
            'status' => $mahasiswa->getStatus(),
            'id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}