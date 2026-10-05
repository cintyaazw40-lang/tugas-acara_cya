<?php
require_once __DIR__ . '/../Models/MahasiswaModel.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

class MahasiswaController
{
    private function base(): string
    {
        return str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    }

    public function index(): void
    {
        $model = new MahasiswaModel();
        $keyword = trim($_GET['q'] ?? '');
        $daftarMahasiswa = $model->all($keyword !== '' ? $keyword : null);

        require_once __DIR__ . '/../Views/mahasiswa/index.php';
    }

    public function create(): void
    {
        $prodiModel = new ProdiModel();
        $daftarProdi = $prodiModel->all();

        require_once __DIR__ . '/../Views/mahasiswa/create.php';
    }

    public function store(): void
    {
        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $prodi_id = (int) ($_POST['prodi_id'] ?? 0);
        $angkatan = (int) ($_POST['angkatan'] ?? date('Y'));
        $status = $_POST['status'] ?? 'aktif';

        if ($nim === '' || $nama === '') {
            header("Location: {$this->base()}/mahasiswa/create?error=1");
            exit;
        }

        $model = new MahasiswaModel();
        $model->create([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => $prodi_id,
            'angkatan' => $angkatan,
            'status' => $status,
        ]);

        header("Location: {$this->base()}/mahasiswa");
        exit;
    }

    public function edit(int $id): void
    {
        $model = new MahasiswaModel();
        $mahasiswa = $model->find($id);

        if ($mahasiswa === null) {
            http_response_code(404);
            echo "404 - Mahasiswa tidak ditemukan";
            return;
        }

        $prodiModel = new ProdiModel();
        $daftarProdi = $prodiModel->all();

        require_once __DIR__ . '/../Views/mahasiswa/edit.php';
    }

    public function update(int $id): void
    {
        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $prodi_id = (int) ($_POST['prodi_id'] ?? 0);
        $angkatan = (int) ($_POST['angkatan'] ?? date('Y'));
        $status = $_POST['status'] ?? 'aktif';

        if ($nim === '' || $nama === '') {
            header("Location: {$this->base()}/mahasiswa/{$id}/edit?error=1");
            exit;
        }

        $model = new MahasiswaModel();
        $model->update($id, [
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => $prodi_id,
            'angkatan' => $angkatan,
            'status' => $status,
        ]);

        header("Location: {$this->base()}/mahasiswa");
        exit;
    }

    public function destroy(int $id): void
    {
        $model = new MahasiswaModel();
        $model->delete($id);

        header("Location: {$this->base()}/mahasiswa");
        exit;
    }
}
