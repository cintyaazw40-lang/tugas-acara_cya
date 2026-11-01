<?php
class MahasiswaController {
    private Mahasiswa $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    // Membaca body JSON, lalu memvalidasi field wajib (400) dan format NIM (422)
    private function validatedBody(): array {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            Response::json(400, false, 'Body harus berupa JSON yang valid');
        }

        $errors = [];
        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "Field {$field} wajib diisi";
            }
        }
        if (!empty($errors)) {
            Response::json(400, false, 'Field nim, nama, dan prodi wajib diisi', null, $errors);
        }

        if (strlen($data['nim']) !== 9) {
            Response::json(422, false, 'Format NIM tidak valid, harus 9 digit', null, [
                'nim' => 'NIM harus terdiri dari 9 digit',
            ]);
        }

        return $data;
    }

    private function requireExisting($id): int {
        if (!$id) {
            Response::json(400, false, 'Parameter id wajib disertakan (?id=)');
        }
        if (!$this->model->find((int) $id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }
        return (int) $id;
    }

    public function index(): void {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $data = $this->model->find((int) $id);
            if (!$data) Response::json(404, false, 'Data tidak ditemukan');
            Response::json(200, true, 'Data berhasil diambil', $data);
        }

        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    public function store(): void {
        $data = $this->validatedBody();

        try {
            $id = $this->model->create($data);
        } catch (InvalidArgumentException $e) {
            Response::json(409, false, $e->getMessage(), null, ['nim' => $e->getMessage()]);
        }

        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    public function update($id): void {
        $id = $this->requireExisting($id);
        $data = $this->validatedBody();

        try {
            $this->model->update($id, $data);
        } catch (InvalidArgumentException $e) {
            Response::json(409, false, $e->getMessage(), null, ['nim' => $e->getMessage()]);
        }

        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id): void {
        $id = $this->requireExisting($id);
        $this->model->delete($id);
        Response::json(200, true, 'Data berhasil dihapus');
    }
}
