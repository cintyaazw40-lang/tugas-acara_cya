<?php

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    /**
     * Folder penyimpanan foto.
     * Otomatis mencari public_html/uploads (hosting/cPanel) atau
     * public/uploads (lokal), relatif terhadap folder induk app/.
     */
    private static function folderUpload(): string
    {
        $root = dirname(__DIR__, 2);

        foreach (['/public_html/uploads/', '/public/uploads/'] as $rel) {
            if (is_dir($root . $rel)) {
                return $root . $rel;
            }
        }

        return $root . '/public/uploads/';
    }

    // Acara 26: mendukung multipart/form-data (FormData) maupun JSON
    private function input(): array
    {
        if (!empty($_POST)) {
            return $_POST;
        }

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

    // ===== BARU Acara 26: helper upload foto =====

    /**
     * Validasi berkas foto. Mengembalikan $_FILES['foto'] jika valid,
     * null jika tidak ada berkas yang diunggah.
     */
    private function ambilFoto(): ?array
    {
        if (empty($_FILES['foto']) ||
            $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['foto'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            Response::json(422, false, 'Upload foto gagal');
        }

        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $info = @getimagesize($file['tmp_name']);

        if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'], true) ||
            $info === false ||
            !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            Response::json(422, false, 'Berkas harus berupa gambar JPG/PNG yang valid');
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            Response::json(422, false, 'Ukuran berkas maksimal 2MB');
        }

        return $file;
    }

    /** Pindahkan berkas ke folder uploads dan kembalikan nama barunya. */
    private function simpanFoto(array $file): string
    {
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $namaBaru = uniqid('foto_', true) . '.' . $ekstensi;

        if (!move_uploaded_file($file['tmp_name'], self::folderUpload() . $namaBaru)) {
            Response::json(500, false, 'Gagal menyimpan berkas foto');
        }

        return $namaBaru;
    }

    /** Tugas mandiri 1: hapus berkas foto dari disk dengan unlink(). */
    private function hapusFoto(?string $nama): void
    {
        if (!$nama) {
            return;
        }

        $path = self::folderUpload() . basename($nama);

        if (is_file($path)) {
            unlink($path);
        }
    }

    // ===== Endpoint =====

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
        Auth::requireAdmin(); // BARU Acara 25 (RBAC)

        $data = $this->input();

        $errors = $this->cekWajib($data);
        if ($errors) {
            Response::json(400, false, 'Data tidak lengkap', null, $errors);
        }

        $data = $this->bersihkan($data);
        unset($data['foto']); // nama foto hanya boleh dibuat oleh server

        $errors = $this->cekFormat($data);
        if ($errors) {
            Response::json(422, false, 'Validasi gagal', null, $errors);
        }

        $foto = $this->ambilFoto(); // validasi berkas dulu

        try {
            // PERBAIKAN Acara 23 (TC-03): 409 diubah menjadi 422
            if ($this->model->nimExists($data['nim'])) {
                Response::json(422, false, 'NIM sudah terdaftar');
            }

            // Berkas baru dipindahkan setelah semua validasi lolos
            if ($foto !== null) {
                $data['foto'] = $this->simpanFoto($foto);
            }

            $id = $this->model->create($data);

            Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
        } catch (Throwable $e) {
            Response::json(500, false, 'Gagal menambahkan data');
        }
    }

    public function update(int $id): void
    {
        Auth::requireAdmin(); // BARU Acara 25 (RBAC)

        $lama = $id < 1 ? false : $this->model->find($id);
        if (!$lama) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $data = $this->input();

        $errors = $this->cekWajib($data);
        if ($errors) {
            Response::json(400, false, 'Data tidak lengkap', null, $errors);
        }

        $data = $this->bersihkan($data);
        unset($data['foto']);

        $errors = $this->cekFormat($data);
        if ($errors) {
            Response::json(422, false, 'Validasi gagal', null, $errors);
        }

        $foto = $this->ambilFoto();

        try {
            // PERBAIKAN Acara 23 (TC-12): 409 diubah menjadi 422
            if ($this->model->nimExists($data['nim'], $id)) {
                Response::json(422, false, 'NIM sudah digunakan mahasiswa lain');
            }

            if ($foto !== null) {
                $data['foto'] = $this->simpanFoto($foto);
            }

            $this->model->update($id, $data);

            // Tugas mandiri 1: hapus foto lama setelah foto baru tersimpan
            if ($foto !== null) {
                $this->hapusFoto($lama['foto'] ?? null);
            }

            Response::json(200, true, 'Data berhasil diperbarui');
        } catch (Throwable $e) {
            Response::json(500, false, 'Gagal memperbarui data');
        }
    }

    public function destroy(int $id): void
    {
        Auth::requireAdmin(); // BARU Acara 25 (RBAC)

        $lama = $id < 1 ? false : $this->model->find($id);
        if (!$lama) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        try {
            $this->model->delete($id);
            $this->hapusFoto($lama['foto'] ?? null);

            Response::json(200, true, 'Data berhasil dihapus');
        } catch (Throwable $e) {
            Response::json(500, false, 'Gagal menghapus data');
        }
    }
}