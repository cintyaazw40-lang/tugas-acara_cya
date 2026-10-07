<?php
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Entities/Mahasiswa.php';

class MahasiswaService
{
    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;

    public function __construct(MahasiswaRepository $repo, ProdiRepository $prodiRepo)
    {
        $this->repo = $repo;
        $this->prodiRepo = $prodiRepo;
    }

    public function create(array $input): array
    {
        $errors = $this->validate($input);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $mahasiswa = $this->buatEntity($input);
            $this->repo->create($mahasiswa);
            return ['success' => true, 'errors' => []];
        } catch (InvalidArgumentException | PDOException $e) {
            return ['success' => false, 'errors' => ['Data gagal disimpan']];
        }
    }

    public function update(int $id, array $input): array
    {
        if ($this->repo->find($id) === null) {
            return ['success' => false, 'errors' => ['Data mahasiswa tidak ditemukan']];
        }

        $errors = $this->validate($input, $id);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $mahasiswa = $this->buatEntity($input, $id);
            $this->repo->update($id, $mahasiswa);
            return ['success' => true, 'errors' => []];
        } catch (InvalidArgumentException | PDOException $e) {
            return ['success' => false, 'errors' => ['Data gagal disimpan']];
        }
    }

    private function validate(array $input, ?int $ignoreId = null): array
    {
        $errors = [];

        $nim = trim($input['nim'] ?? '');
        if ($nim === '') {
            $errors['nim'] = 'NIM wajib diisi';
        } elseif (!ctype_digit($nim)) {
            $errors['nim'] = 'NIM harus berupa angka';
        } elseif ($this->repo->existsByNim($nim, $ignoreId)) {
            $errors['nim'] = 'NIM sudah terdaftar';
        }

        if (trim($input['nama'] ?? '') === '') {
            $errors['nama'] = 'Nama wajib diisi';
        }

        $email = trim($input['email'] ?? '');
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid';
        }

        $prodiId = (int) ($input['prodi_id'] ?? 0);
        if ($prodiId <= 0 || !$this->prodiRepo->exists($prodiId)) {
            $errors['prodi_id'] = 'Program studi tidak tersedia';
        }

        $angkatan = (int) ($input['angkatan'] ?? 0);
        if ($angkatan < 2000 || $angkatan > (int) date('Y')) {
            $errors['angkatan'] = 'Angkatan tidak valid';
        }

        $status = $input['status'] ?? 'aktif';
        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            $errors['status'] = 'Status tidak valid';
        }

        return $errors;
    }

    private function buatEntity(array $input, ?int $id = null): Mahasiswa
    {
        return new Mahasiswa(
            trim($input['nim'] ?? ''),
            trim($input['nama'] ?? ''),
            trim($input['email'] ?? ''),
            (int) ($input['prodi_id'] ?? 0),
            (int) ($input['angkatan'] ?? date('Y')),
            $input['status'] ?? 'aktif',
            $id
        );
    }
}