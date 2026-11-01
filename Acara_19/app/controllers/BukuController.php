<?php
class BukuController {
    private Buku $model;

    public function __construct() {
        $this->model = new Buku();
    }

    private function validatedBody(): array {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            Response::json(400, false, 'Body harus berupa JSON yang valid');
        }

        $errors = [];
        foreach (['judul', 'penulis'] as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "Field {$field} wajib diisi";
            }
        }
        if (!empty($errors)) {
            Response::json(400, false, 'Field judul dan penulis wajib diisi', null, $errors);
        }

        if (isset($data['tahun_terbit']) && !preg_match('/^\d{4}$/', (string) $data['tahun_terbit'])) {
            Response::json(422, false, 'Format tahun terbit tidak valid, harus 4 digit', null, [
                'tahun_terbit' => 'Tahun terbit harus terdiri dari 4 digit',
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
        $id = $this->model->create($data);
        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    public function update($id): void {
        $id = $this->requireExisting($id);
        $data = $this->validatedBody();
        $this->model->update($id, $data);
        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id): void {
        $id = $this->requireExisting($id);
        $this->model->delete($id);
        Response::json(200, true, 'Data berhasil dihapus');
    }
}
