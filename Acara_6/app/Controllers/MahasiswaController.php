<?php

class MahasiswaController
{
    public function __construct()
    {
    }

    public function index()
    {
        require_once __DIR__ . '/../Views/mahasiswa/index.php';
    }
}