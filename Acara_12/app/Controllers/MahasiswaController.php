<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Entities/Mahasiswa.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

// MahasiswaController mewarisi (extends) Controller, sehingga otomatis
// punya method view() dan redirect() tanpa perlu menulis ulang.
class MahasiswaController extends Controller
{
    private MahasiswaRepository $repo;

    // Constructor Dependency Injection: Controller menerima Repository dari luar.
    // Seluruh query SQL berada di MahasiswaRepository, tidak ada SQL di sini.
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

        // Memakai method view() yang diwariskan dari Controller (BaseController)
        $this->view('mahasiswa/index', ['daftarMahasiswa' => $daftarMahasiswa]);
    }

    public function create(): void
    {
        $prodiModel = new ProdiModel();
        $daftarProdi = $prodiModel->all();

        $this->view('mahasiswa/create', ['daftarProdi' => $daftarProdi]);
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

            // Controller tidak menulis SQL -- cukup memanggil method Repository
            $this->repo->create($mahasiswa);

            // Memakai method redirect() yang diwariskan dari Controller
            $this->redirect("{$this->base()}/mahasiswa");
        } catch (InvalidArgumentException $e) {
            $pesan = urlencode($e->getMessage());
            $this->redirect("{$this->base()}/mahasiswa/create?error={$pesan}");
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

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa,
            'daftarProdi' => $daftarProdi,
        ]);
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
            $this->redirect("{$this->base()}/mahasiswa");
        } catch (InvalidArgumentException $e) {
            $pesan = urlencode($e->getMessage());
            $this->redirect("{$this->base()}/mahasiswa/{$id}/edit?error={$pesan}");
        }
    }

    public function destroy(int $id): void
    {
        $this->repo->delete($id);
        $this->redirect("{$this->base()}/mahasiswa");
    }
}
