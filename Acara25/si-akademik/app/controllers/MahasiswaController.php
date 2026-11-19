<?php
class MahasiswaController {
    private Mahasiswa $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    /** GET /mahasiswa  atau  GET /mahasiswa?id=1 */
    public function index(): void {
        $id = $_GET['id'] ?? null;
        if ($id !== null && $id !== '') {
            if (!$this->validId($id)) {
                Response::json(400, false, 'Parameter id harus berupa bilangan bulat positif.');
            }
            $data = $this->model->find((int) $id);
            if (!$data) {
                Response::json(404, false, 'Data mahasiswa tidak ditemukan.');
            }
            Response::json(200, true, 'Data berhasil diambil.', $data);
        }
        Response::json(200, true, 'Data berhasil diambil.', $this->model->getAll());
    }

    /** POST /mahasiswa */
    public function store(): void {
        Auth::requireAdmin();
        $data = $this->validateData($this->readJsonBody());
        if ($this->model->findByNim($data['nim'])) {
            Response::json(422, false, 'Validasi data gagal.', null, [
                'nim' => ['NIM sudah terdaftar.'],
            ]);
        }
        $id   = $this->model->create($data);
        Response::json(201, true, 'Data berhasil ditambahkan.', ['id' => $id]);
    }

    /** PUT /mahasiswa?id=1 */
    public function update($id): void {
        Auth::requireAdmin();
        if ($id === null || $id === '') {
            Response::json(400, false, 'Parameter id wajib diisi untuk memperbarui data.');
        }
        if (!$this->validId($id)) {
            Response::json(400, false, 'Parameter id harus berupa bilangan bulat positif.');
        }
        $id = (int) $id;
        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data mahasiswa tidak ditemukan.');
        }
        $data = $this->validateData($this->readJsonBody());
        if ($this->model->findByNim($data['nim'], $id)) {
            Response::json(422, false, 'Validasi data gagal.', null, [
                'nim' => ['NIM sudah terdaftar.'],
            ]);
        }
        $this->model->update($id, $data);
        Response::json(200, true, 'Data berhasil diperbarui.', ['id' => $id]);
    }

    /** DELETE /mahasiswa?id=1 */
    public function destroy($id): void {
        Auth::requireAdmin();
        if ($id === null || $id === '') {
            Response::json(400, false, 'Parameter id wajib diisi untuk menghapus data.');
        }
        if (!$this->validId($id)) {
            Response::json(400, false, 'Parameter id harus berupa bilangan bulat positif.');
        }
        $id = (int) $id;
        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data mahasiswa tidak ditemukan.');
        }
        $this->model->delete($id);
        Response::json(200, true, 'Data berhasil dihapus.', ['id' => $id]);
    }

    /** Membaca body request sebagai JSON; 400 bila bukan JSON object yang valid. */
    private function readJsonBody(): array {
        $raw  = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) {
            Response::json(400, false, 'Body harus berisi JSON yang valid.');
        }
        return $data;
    }

    /**
     * Validasi dua tahap, seluruh kesalahan dikumpulkan per field (Tugas Mandiri 1):
     *   1) Field wajib kosong/tidak ada          -> 400 Bad Request
     *   2) Field ada tetapi formatnya tidak sah  -> 422 Unprocessable Entity
     *
     * @return array data yang sudah dinormalisasi (nim string, teks di-trim)
     */
    private function validateData(array $data): array {
        $required = ['nim', 'nama', 'prodi'];

        // Tahap 1: kelengkapan field
        $errors = [];
        foreach ($required as $field) {
            $value = $data[$field] ?? null;
            if ($value === null || (is_string($value) && trim($value) === '')) {
                $errors[$field][] = "Field $field wajib diisi.";
            }
        }
        if ($errors) {
            Response::json(400, false, 'Field nim, nama, dan prodi wajib diisi.', null, $errors);
        }

        // Tahap 2: format & batas panjang (sesuai ukuran kolom tabel)
        $nim = $data['nim'];
        if ((!is_string($nim) && !is_int($nim)) || !preg_match('/^[0-9]{9}$/', (string) $nim)) {
            $errors['nim'][] = 'Format NIM tidak valid, harus 9 digit.';
        }

        $limits = ['nama' => 100, 'prodi' => 50];
        foreach ($limits as $field => $max) {
            $value = $data[$field];
            if (!is_string($value)) {
                $errors[$field][] = "Field $field harus berupa teks.";
            } elseif ($this->textLength(trim($value)) > $max) {
                $errors[$field][] = "Field $field maksimal $max karakter.";
            }
        }
        if ($errors) {
            Response::json(422, false, 'Validasi data gagal.', null, $errors);
        }

        return [
            'nim'   => (string) $nim,
            'nama'  => trim($data['nama']),
            'prodi' => trim($data['prodi']),
        ];
    }

    /** Panjang teks dalam karakter (bukan byte); aman bila ekstensi mbstring nonaktif. */
    private function textLength(string $text): int {
        return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
    }

    private function validId($id): bool {
        return (is_int($id) || is_string($id)) && preg_match('/^[1-9][0-9]*$/', (string) $id) === 1;
    }
}
