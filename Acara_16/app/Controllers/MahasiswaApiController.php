<?php
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';
require_once __DIR__ . '/../Entities/Mahasiswa.php';
require_once __DIR__ . '/../Helpers/Logger.php';

class MahasiswaApiController
{
    private MahasiswaRepository $repo;
    private MahasiswaService $service;

    public function __construct(MahasiswaRepository $repo, MahasiswaService $service)
    {
        $this->repo = $repo;
        $this->service = $service;
    }

    private function json(int $status, bool $success, string $message, $data = null): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['success' => $success, 'message' => $message, 'data' => $data],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function format($row): array
    {
        $prodiNama = null;
        $mahasiswa = $row;

        if (is_array($row) && isset($row['mahasiswa'])) {
            $mahasiswa = $row['mahasiswa'];
            $prodiNama = $row['prodi_nama'] ?? null;
        }

        return [
            'id' => $mahasiswa->getId(),
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'email' => $mahasiswa->getEmail(),
            'prodi_id' => $mahasiswa->getProdiId(),
            'prodi_nama' => $prodiNama,
            'angkatan' => $mahasiswa->getAngkatan(),
            'status' => $mahasiswa->getStatus(),
        ];
    }

    private function readJson(): ?array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
            return null;
        }

        return $input;
    }

    // GET /api/mahasiswa
    public function index(): void
    {
        try {
            $keyword = trim($_GET['q'] ?? '');
            $rows = $this->repo->all($keyword !== '' ? $keyword : null);
            $data = array_map([$this, 'format'], $rows);

            $this->json(200, true, 'Data berhasil diambil', $data);
        } catch (Throwable $e) {
            Logger::error('MahasiswaApiController::index', $e);
            $this->json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    // GET /api/mahasiswa/{id}
    public function show(int $id): void
    {
        try {
            $mahasiswa = $this->repo->find($id);

            if ($mahasiswa === null) {
                $this->json(404, false, 'Data mahasiswa tidak ditemukan');
                return;
            }

            $this->json(200, true, 'Data berhasil diambil', $this->format($mahasiswa));
        } catch (Throwable $e) {
            Logger::error('MahasiswaApiController::show', $e);
            $this->json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    // POST /api/mahasiswa
    public function store(): void
    {
        try {
            $input = $this->readJson();

            if ($input === null) {
                $this->json(400, false, 'Body harus berupa JSON yang valid');
                return;
            }

            $input = array_merge([
                'prodi_id' => 1,
                'angkatan' => (int) date('Y'),
                'status' => 'aktif',
            ], $input);

            $result = $this->service->create($input);

            if ($result['success']) {
                $this->json(201, true, 'Data mahasiswa berhasil ditambahkan');
                return;
            }

            $this->json(422, false, 'Data tidak valid', $result['errors']);
        } catch (Throwable $e) {
            Logger::error('MahasiswaApiController::store', $e);
            $this->json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    // PUT /api/mahasiswa/{id}
    public function update(int $id): void
    {
        try {
            $mahasiswa = $this->repo->find($id);

            if ($mahasiswa === null) {
                $this->json(404, false, 'Data mahasiswa tidak ditemukan');
                return;
            }

            $input = $this->readJson();

            if ($input === null) {
                $this->json(400, false, 'Body harus berupa JSON yang valid');
                return;
            }

            $input = array_merge([
                'prodi_id' => $mahasiswa->getProdiId(),
                'angkatan' => $mahasiswa->getAngkatan(),
                'status' => $mahasiswa->getStatus(),
            ], $input);

            $result = $this->service->update($id, $input);

            if ($result['success']) {
                $this->json(200, true, 'Data mahasiswa berhasil diubah');
                return;
            }

            $this->json(422, false, 'Data tidak valid', $result['errors']);
        } catch (Throwable $e) {
            Logger::error('MahasiswaApiController::update', $e);
            $this->json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    // DELETE /api/mahasiswa/{id}
    public function destroy(int $id): void
    {
        try {
            if ($this->repo->find($id) === null) {
                $this->json(404, false, 'Data mahasiswa tidak ditemukan');
                return;
            }

            $result = $this->service->delete($id);

            if ($result['success']) {
                $this->json(200, true, 'Data mahasiswa berhasil dihapus');
                return;
            }

            $this->json(500, false, 'Data gagal dihapus');
        } catch (Throwable $e) {
            Logger::error('MahasiswaApiController::destroy', $e);
            $this->json(500, false, 'Terjadi kesalahan pada server');
        }
    }
}