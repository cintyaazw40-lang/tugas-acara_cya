<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';
require_once __DIR__ . '/../Helpers/Flash.php';
require_once __DIR__ . '/../Helpers/Logger.php';

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

    private function tampilkanError(string $context, Throwable $e): void
    {
        Logger::error($context, $e);
        http_response_code(500);
        echo "Terjadi kesalahan pada sistem. Silakan coba lagi nanti.";
    }

    public function index(): void
    {
        try {
            $keyword = trim($_GET['q'] ?? '');
            $daftarMahasiswa = $this->repo->all($keyword !== '' ? $keyword : null);

            $this->view('mahasiswa/index', ['daftarMahasiswa' => $daftarMahasiswa]);
        } catch (Throwable $e) {
            $this->tampilkanError('MahasiswaController::index', $e);
        }
    }

    public function create(): void
    {
        try {
            $daftarProdi = $this->prodiRepo->all();

            $this->view('mahasiswa/create', ['daftarProdi' => $daftarProdi]);
        } catch (Throwable $e) {
            $this->tampilkanError('MahasiswaController::create', $e);
        }
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
        try {
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
        } catch (Throwable $e) {
            $this->tampilkanError('MahasiswaController::edit', $e);
        }
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
        $result = $this->service->delete($id);

        if ($result['success']) {
            Flash::set('success', 'Data mahasiswa berhasil dihapus');
        } else {
            Flash::set('danger', implode(', ', $result['errors']));
        }

        $this->redirect("{$this->base()}/mahasiswa");
    }
}