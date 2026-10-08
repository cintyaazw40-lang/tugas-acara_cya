<?php
class HomeController
{
    public function index()
    {
        $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        echo "<h1>Selamat Datang di Sistem Informasi Akademik</h1>";
        echo "<p><a href='{$base}/login'>Login</a> untuk mengakses dashboard.</p>";
    }
}
