<?php
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(array $data): string {
        if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password, role)
             VALUES (:username, :email, :password, :role)'
        );
        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':role' => $data['role'] ?? 'user',
        ]);
        return $this->db->lastInsertId();
    }

    public function findByUsername(string $username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    public function exists(string $username, string $email): bool {
        $stmt = $this->db->prepare(
            'SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1'
        );
        $stmt->execute([':username' => $username, ':email' => $email]);
        return (bool) $stmt->fetch();
    }
}
