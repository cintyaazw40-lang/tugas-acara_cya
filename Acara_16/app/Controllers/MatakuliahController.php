<?php
require_once __DIR__ . '/../Models/MatakuliahModel.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

class MatakuliahController
{
    private function base(): string
    {
        return str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    }

    public function index(): void
    {
        $model = new MatakuliahModel();
        $daftarMatakuliah = $model->all();

        require_once __DIR__ . '/../Views/matakuliah/index.php';
    }

    public function create(): void
    {
        $prodiModel = new ProdiModel();
        $daftarProdi = $prodiModel->all();

        require_once __DIR__ . '/../Views/matakuliah/create.php';
    }

    public function store(): void
    {
        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $sks = (int) ($_POST['sks'] ?? 0);
        $prodi_id = (int) ($_POST['prodi_id'] ?? 0);

        if ($kode === '' || $nama === '') {
            header("Location: {$this->base()}/matakuliah/create?error=1");
            exit;
        }

        $model = new MatakuliahModel();
        $model->create(['kode' => $kode, 'nama' => $nama, 'sks' => $sks, 'prodi_id' => $prodi_id]);

        header("Location: {$this->base()}/matakuliah");
        exit;
    }

    public function edit(int $id): void
    {
        $model = new MatakuliahModel();
        $matakuliah = $model->find($id);

        if ($matakuliah === null) {
            http_response_code(404);
            echo "404 - Mata kuliah tidak ditemukan";
            return;
        }

        $prodiModel = new ProdiModel();
        $daftarProdi = $prodiModel->all();

        require_once __DIR__ . '/../Views/matakuliah/edit.php';
    }

    public function update(int $id): void
    {
        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $sks = (int) ($_POST['sks'] ?? 0);
        $prodi_id = (int) ($_POST['prodi_id'] ?? 0);

        if ($kode === '' || $nama === '') {
            header("Location: {$this->base()}/matakuliah/{$id}/edit?error=1");
            exit;
        }

        $model = new MatakuliahModel();
        $model->update($id, ['kode' => $kode, 'nama' => $nama, 'sks' => $sks, 'prodi_id' => $prodi_id]);

        header("Location: {$this->base()}/matakuliah");
        exit;
    }

    public function destroy(int $id): void
    {
        $model = new MatakuliahModel();
        $model->delete($id);

        header("Location: {$this->base()}/matakuliah");
        exit;
    }
}
