<?php
require_once __DIR__ . '/../Models/MahasiswaModel.php';

class MahasiswaController
{
    public function index()
    {
        $model = new MahasiswaModel();
        $daftarMahasiswa = $model->all();

        require_once __DIR__ . '/../Views/mahasiswa/index.php';
    }
}
