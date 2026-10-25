<?php
require_once __DIR__ . '/../Models/ProdiModel.php';

class ProdiController
{
    private function base(): string
    {
        return str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    }

    public function index(): void
    {
        $model = new ProdiModel();
        $daftarProdi = $model->all();

        require_once __DIR__ . '/../Views/prodi/index.php';
    }

    public function create(): void
    {
        require_once __DIR__ . '/../Views/prodi/create.php';
    }

    public function store(): void
    {
        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');

        if ($kode === '' || $nama === '') {
            header("Location: {$this->base()}/prodi/create?error=1");
            exit;
        }

        $model = new ProdiModel();
        $model->create(['kode' => $kode, 'nama' => $nama]);

        header("Location: {$this->base()}/prodi");
        exit;
    }

    public function edit(int $id): void
    {
        $model = new ProdiModel();
        $prodi = $model->find($id);

        if ($prodi === null) {
            http_response_code(404);
            echo "404 - Prodi tidak ditemukan";
            return;
        }

        require_once __DIR__ . '/../Views/prodi/edit.php';
    }

    public function update(int $id): void
    {
        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');

        if ($kode === '' || $nama === '') {
            header("Location: {$this->base()}/prodi/{$id}/edit?error=1");
            exit;
        }

        $model = new ProdiModel();
        $model->update($id, ['kode' => $kode, 'nama' => $nama]);

        header("Location: {$this->base()}/prodi");
        exit;
    }

    public function destroy(int $id): void
    {
        $model = new ProdiModel();
        $model->delete($id);

        header("Location: {$this->base()}/prodi");
        exit;
    }
}
