<?php
require_once __DIR__ . '/../Core/Database.php';

class ProdiRepository
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM prodi ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function exists(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM prodi WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }
}