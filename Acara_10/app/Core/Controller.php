<?php
class Controller
{
    // Memuat file View, dengan $data diubah menjadi variabel-variabel
    // yang bisa langsung dipakai di dalam file View tersebut.
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    // Mengarahkan pengguna ke URL lain, lalu menghentikan eksekusi script.
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}
