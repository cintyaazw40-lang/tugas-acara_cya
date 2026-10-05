<?php
namespace App\Controllers;

class MahasiswaController
{
    public function index()
    {
        echo "<h1>Daftar Mahasiswa</h1>";
        echo "<p>Ini adalah MahasiswaController  selamat datang di si akademik'/mahasiswa'</p>";
    }

    public function create()
    {
        echo "<h1>Form Tambah Mahasiswa</h1>";
        echo "<p>Ini adalah MahasiswaController '/mahasiswa/create'</p>";
    }

    public function store()
    {
        echo "<h1>Data Mahasiswa Disimpan</h1>";
        echo "<p>Ini adalah MahasiswaController '/mahasiswa'</p>";
    }

    // Tugas Mandiri: menampilkan detail mahasiswa berdasarkan ID dari URL
    // Contoh: /mahasiswa/5 -> show(5)
    public function show($id)
    {
        echo "<h1>Detail Mahasiswa</h1>";
        echo "<p>Menampilkan data mahasiswa dengan ID: <strong>{$id}</strong></p>";
    }
}