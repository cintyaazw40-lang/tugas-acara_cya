<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';
require_once __DIR__ . '/../Helpers/Flash.php';

class MahasiswaController extends Controller
{
    private MahasiswaRepository $repo;
    private MahasiswaService $service;
    private ProdiRepository $prodiRepo;

    public function __construct(
        MahasiswaRepository $repo,
        MahasiswaService $service,
        ProdiRepository $prodiRepo
    ) {
        $this->repo = $repo;
        $this->service = $service;
        $this->prodiRepo = $prodiRepo;
    }

    private function base(): string
    {
        return str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    }

    public function index(): void
    {
        $keyword = trim($_GET['q'] ?? '');
        $daftarMahasiswa = $this->repo->all($keyword !== '' ? $keyword : null);

        $this->view('mahasiswa/index', ['daftarMahasiswa' => $daftarMahasiswa]);
    }

    public function create(): void
    {
        $daftarProdi = $this->prodiRepo->all();

        $this->view('mahasiswa/create', ['daftarProdi' => $daftarProdi]);
    }

    public function store(): void
    {
        $result = $this->service->create($_POST);

        if ($result['success']) {
            Flash::set('success', 'Data mahasiswa berhasil ditambahkan');
            $this->redirect("{$this->base()}/mahasiswa");
        }

        Flash::set('danger', implode(', ', $result['errors']));
        $this->redirect("{$this->base()}/mahasiswa/create");
    }

    public function edit(int $id): void
    {
        $mahasiswa = $this->repo->find($id);

        if ($mahasiswa === null) {
            http_response_code(404);
            echo "404 - Mahasiswa tidak ditemukan";
            return;
        }

        $daftarProdi = $this->prodiRepo->all();

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa,
            'daftarProdi' => $daftarProdi,
        ]);
    }

    public function update(int $id): void
    {
        $result = $this->service->update($id, $_POST);

        if ($result['success']) {
            Flash::set('success', 'Data mahasiswa berhasil diubah');
            $this->redirect("{$this->base()}/mahasiswa");
        }

        Flash::set('danger', implode(', ', $result['errors']));
        $this->redirect("{$this->base()}/mahasiswa/{$id}/edit");
    }

    public function destroy(int $id): void
    {
        $this->repo->delete($id);

        Flash::set('success', 'Data mahasiswa berhasil dihapus');
        $this->redirect("{$this->base()}/mahasiswa");
    }
}