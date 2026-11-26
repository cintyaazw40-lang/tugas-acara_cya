
<?php

class BukuController
{
    private Buku $model;

    public function __construct()
    {
        $this->model = new Buku();
    }

    private function respond(int $status, array $body): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($body, JSON_UNESCAPED_UNICODE);
    }

    private function input(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    private function validate(array $data): ?string
    {
        if (!isset($data['judul'], $data['penulis'],
                   $data['tahun_terbit'])) {
            return 'Judul, penulis, dan tahun_terbit wajib diisi';
        }

        if (!is_string($data['judul']) ||
            trim($data['judul']) === '' ||
            strlen($data['judul']) > 150) {
            return 'Judul tidak valid';
        }

        if (!is_string($data['penulis']) ||
            trim($data['penulis']) === '' ||
            strlen($data['penulis']) > 100) {
            return 'Penulis tidak valid';
        }

        $tahun = filter_var(
            $data['tahun_terbit'],
            FILTER_VALIDATE_INT
        );

        if ($tahun === false || $tahun < 1 ||
            $tahun > (int) date('Y') + 5) {
            return 'Tahun terbit tidak valid';
        }

        return null;
    }

    public function index(): void
    {
        try {
            $id = $_GET['id'] ?? null;

            if ($id !== null) {
                $data = $this->model->find((int) $id);

                if (!$data) {
                    $this->respond(404, [
                        'success' => false,
                        'message' => 'Buku tidak ditemukan'
                    ]);
                    return;
                }

                $this->respond(200, [
                    'success' => true,
                    'data' => $data
                ]);
                return;
            }

            $this->respond(200, [
                'success' => true,
                'data' => $this->model->getAll()
            ]);
        } catch (Throwable $e) {
            $this->respond(500, [
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ]);
        }
    }

    public function store(): void
    {
        $data = $this->input();
        $error = $this->validate($data);

        if ($error !== null) {
            $this->respond(400, [
                'success' => false,
                'message' => $error
            ]);
            return;
        }

        try {
            $id = $this->model->create([
                'judul' => trim($data['judul']),
                'penulis' => trim($data['penulis']),
                'tahun_terbit' => (int) $data['tahun_terbit']
            ]);

            $this->respond(201, [
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => ['id' => $id]
            ]);
        } catch (Throwable $e) {
            $this->respond(500, [
                'success' => false,
                'message' => 'Gagal menambahkan buku'
            ]);
        }
    }

    public function update(int $id): void
    {
        if ($id < 1 || !$this->model->find($id)) {
            $this->respond(404, [
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ]);
            return;
        }

        $data = $this->input();
        $error = $this->validate($data);

        if ($error !== null) {
            $this->respond(400, [
                'success' => false,
                'message' => $error
            ]);
            return;
        }

        try {
            $this->model->update($id, [
                'judul' => trim($data['judul']),
                'penulis' => trim($data['penulis']),
                'tahun_terbit' => (int) $data['tahun_terbit']
            ]);

            $this->respond(200, [
                'success' => true,
                'message' => 'Buku berhasil diperbarui'
            ]);
        } catch (Throwable $e) {
            $this->respond(500, [
                'success' => false,
                'message' => 'Gagal memperbarui buku'
            ]);
        }
    }

    public function destroy(int $id): void
    {
        if ($id < 1 || !$this->model->find($id)) {
            $this->respond(404, [
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ]);
            return;
        }

        try {
            $this->model->delete($id);

            $this->respond(200, [
                'success' => true,
                'message' => 'Buku berhasil dihapus'
            ]);
        } catch (Throwable $e) {
            $this->respond(500, [
                'success' => false,
                'message' => 'Gagal menghapus buku'
            ]);
        }
    }
}
