<?php
require_once __DIR__ . '/Model.php';

class MahasiswaModel extends Model
{
    // Mengambil semua data mahasiswa, digabung dengan nama prodi (relasi FK prodi_id)
    public function all(): array
    {
        $sql = "SELECT mahasiswa.*, prodi.nama AS prodi_nama
                FROM mahasiswa
                JOIN prodi ON mahasiswa.prodi_id = prodi.id
                ORDER BY mahasiswa.id ASC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
