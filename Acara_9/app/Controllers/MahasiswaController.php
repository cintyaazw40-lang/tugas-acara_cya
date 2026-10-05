<?php
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Entities/Mahasiswa.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

class MahasiswaController
{
    private MahasiswaRepository $repo;

    // Constructor Dependency Injection: Controller menerima Repository dari luar,
    // bukan membuat sendiri (dulu di Acara 8 Controller yang bikin "new MahasiswaModel()").
    public function __construct(MahasiswaRepository $repo)
    {
        $this->repo = $repo;
    }

    private function base(): string
    {
        return str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    }

    public function index(): void
    {
        $keyword = trim($_GET['q'] ?? '');
        $daftarMahasiswa = $this->repo->all($keyword !== '' ? $keyword : null);

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
        try {
            // Getter/setter pada class Mahasiswa otomatis memvalidasi input di sini
            $mahasiswa = new Mahasiswa(
                trim($_POST['nim'] ?? ''),
                trim($_POST['nama'] ?? ''),
                trim($_POST['email'] ?? ''),
                (int) ($_POST['prodi_id'] ?? 0),
                (int) ($_POST['angkatan'] ?? date('Y')),
                $_POST['status'] ?? 'aktif'
            );

            $this->repo->create($mahasiswa);
            header("Location: {$this->base()}/mahasiswa");
            exit;
        } catch (InvalidArgumentException $e) {
            $pesan = urlencode($e->getMessage());
            header("Location: {$this->base()}/mahasiswa/create?error={$pesan}");
            exit;
        }
    }

    public function edit(int $id): void
    {
        $mahasiswa = $this->repo->find($id);

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
        try {
            $mahasiswa = new Mahasiswa(
                trim($_POST['nim'] ?? ''),
                trim($_POST['nama'] ?? ''),
                trim($_POST['email'] ?? ''),
                (int) ($_POST['prodi_id'] ?? 0),
                (int) ($_POST['angkatan'] ?? date('Y')),
                $_POST['status'] ?? 'aktif',
                $id
            );

            $this->repo->update($id, $mahasiswa);
            header("Location: {$this->base()}/mahasiswa");
            exit;
        } catch (InvalidArgumentException $e) {
            $pesan = urlencode($e->getMessage());
            header("Location: {$this->base()}/mahasiswa/{$id}/edit?error={$pesan}");
            exit;
        }
    }

    public function destroy(int $id): void
    {
        $this->repo->delete($id);
        header("Location: {$this->base()}/mahasiswa");
        exit;
    }
}
