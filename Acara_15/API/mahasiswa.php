<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Helpers/Logger.php';

function respond(int $status, bool $success, string $message, $data = null): void
{
    http_response_code($status);
    echo json_encode(
        ['success' => $success, 'message' => $message, 'data' => $data],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
}

function rapikan(array $row): array
{
    $row['id'] = (int) $row['id'];
    $row['prodi_id'] = (int) $row['prodi_id'];
    $row['angkatan'] = (int) $row['angkatan'];
    return $row;
}

try {
    $pdo = Database::getInstance()->getConnection();
    $method = $_SERVER['REQUEST_METHOD'];

    // ===== GET: daftar mahasiswa atau satu mahasiswa (?id=1) =====
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            if ($id <= 0) {
                respond(400, false, 'Parameter id tidak valid');
            }

            $stmt = $pdo->prepare(
                'SELECT id, nim, nama, email, prodi_id, angkatan, status
                 FROM mahasiswa WHERE id = :id'
            );
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                respond(404, false, 'Data mahasiswa tidak ditemukan');
            }
            respond(200, true, 'Data berhasil diambil', rapikan($row));
        }

        $stmt = $pdo->query(
            'SELECT id, nim, nama, email, prodi_id, angkatan, status
             FROM mahasiswa ORDER BY id ASC'
        );
        $data = array_map('rapikan', $stmt->fetchAll(PDO::FETCH_ASSOC));
        respond(200, true, 'Data berhasil diambil', $data);
    }

    // ===== POST: tambah mahasiswa (body JSON) =====
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
            respond(400, false, 'Body harus berupa JSON yang valid');
        }

        $nim = trim($input['nim'] ?? '');
        $nama = trim($input['nama'] ?? '');
        $email = trim($input['email'] ?? '');
        $prodiId = (int) ($input['prodi_id'] ?? 1);
        $angkatan = (int) ($input['angkatan'] ?? date('Y'));
        $status = $input['status'] ?? 'aktif';

        $errors = [];
        if ($nim === '') {
            $errors['nim'] = 'NIM wajib diisi';
        } elseif (!ctype_digit($nim)) {
            $errors['nim'] = 'NIM harus berupa angka';
        } else {
            $cek = $pdo->prepare('SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim');
            $cek->execute(['nim' => $nim]);
            if ((int) $cek->fetchColumn() > 0) {
                $errors['nim'] = 'NIM sudah terdaftar';
            }
        }
        if ($nama === '') {
            $errors['nama'] = 'Nama wajib diisi';
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid';
        }
        $cekProdi = $pdo->prepare('SELECT COUNT(*) FROM prodi WHERE id = :id');
        $cekProdi->execute(['id' => $prodiId]);
        if ($prodiId <= 0 || (int) $cekProdi->fetchColumn() === 0) {
            $errors['prodi_id'] = 'Program studi tidak tersedia';
        }
        if ($angkatan < 2000 || $angkatan > (int) date('Y')) {
            $errors['angkatan'] = 'Angkatan tidak valid';
        }
        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            $errors['status'] = 'Status tidak valid';
        }

        if (!empty($errors)) {
            respond(422, false, 'Data tidak valid', $errors);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)'
        );
        $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => $prodiId,
            'angkatan' => $angkatan,
            'status' => $status,
        ]);

        respond(201, true, 'Data mahasiswa berhasil ditambahkan', [
            'id' => (int) $pdo->lastInsertId(),
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
        ]);
    }

    respond(405, false, 'Method tidak diizinkan');
} catch (Throwable $e) {
    Logger::error('api/mahasiswa.php', $e);
    respond(500, false, 'Terjadi kesalahan pada server');
}