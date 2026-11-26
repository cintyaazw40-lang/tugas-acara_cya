<?php

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    private function input(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Cek field wajib. Mengembalikan array [field => pesan].
     * Array kosong berarti semua field wajib sudah terisi.
     */
    private function cekWajib(array $data): array
    {
        $errors = [];

        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (!isset($data[$field]) ||
                !is_string($data[$field]) ||
                trim($data[$field]) === '') {
                $errors[$field] = "Field $field wajib diisi dengan teks";
            }
        }

        return $errors;
    }

    /**
     * Cek format field (dipanggil setelah data di-trim).
     */
    private function cekFormat(array $data): array
    {
        $errors = [];

        if (!preg_match('/^\d{9}$/', $data['nim'])) {
            $errors['nim'] = 'Format NIM tidak valid, harus 9 digit';
        }

        if (strlen($data['nama']) > 100) {
            $errors['nama'] = 'Nama maksimal 100 karakter';
        }

        if (strlen($data['prodi']) > 50) {
            $errors['prodi'] = 'Prodi maksimal 50 karakter';
        }

        return $errors;
    }

    private function bersihkan(array $data): array
    {
        return array_map(
            fn($v) => is_string($v) ? trim($v) : $v,
            $data
        );
    }

    public function index(): void
    {
        try {
            $id = $_GET['id'] ?? null;

            if ($id !== null) {
                if (!ctype_digit((string) $id) || (int) $id < 1) {
                    Response::json(400, false, 'ID tidak valid');
                }

                $data = $this->model->find((int) $id);

                if (!$data) {
                    Response::json(404, false, 'Data tidak ditemukan');
                }

                Response::json(200, true, 'Data berhasil diambil', $data);
            }

            Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
        } catch (Throwable $e) {
            Response::json(500, false, 'Terjadi kesalahan server');
        }
    }

    public function store(): void
    {
        Auth::requireAuth(); // BARU Acara 20

        $data = $this->input();

        $errors = $this->cekWajib($data);
        if ($errors) {
            Response::json(400, false, 'Data tidak lengkap', null, $errors);
        }

        $data = $this->bersihkan($data);

        $errors = $this->cekFormat($data);
        if ($errors) {
            Response::json(422, false, 'Validasi gagal', null, $errors);
        }

        try {
            if ($this->model->nimExists($data['nim'])) {
                Response::json(409, false, 'NIM sudah terdaftar');
            }

            $id = $this->model->create($data);

            Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
        } catch (Throwable $e) {
            Response::json(500, false, 'Gagal menambahkan data');
        }
    }

    public function update(int $id): void
    {
        Auth::requireAuth(); // BARU Acara 20

        if ($id < 1 || !$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $data = $this->input();

        $errors = $this->cekWajib($data);
        if ($errors) {
            Response::json(400, false, 'Data tidak lengkap', null, $errors);
        }

        $data = $this->bersihkan($data);

        $errors = $this->cekFormat($data);
        if ($errors) {
            Response::json(422, false, 'Validasi gagal', null, $errors);
        }

        try {
            if ($this->model->nimExists($data['nim'], $id)) {
                Response::json(409, false, 'NIM sudah digunakan mahasiswa lain');
            }

            $this->model->update($id, $data);

            Response::json(200, true, 'Data berhasil diperbarui');
        } catch (Throwable $e) {
            Response::json(500, false, 'Gagal memperbarui data');
        }
    }

    public function destroy(int $id): void
    {
        Auth::requireAuth(); // BARU Acara 20

        if ($id < 1 || !$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        try {
            $this->model->delete($id);

            Response::json(200, true, 'Data berhasil dihapus');
        } catch (Throwable $e) {
            Response::json(500, false, 'Gagal menghapus data');
        }
    }
}