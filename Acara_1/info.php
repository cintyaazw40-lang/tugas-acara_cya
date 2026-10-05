<?php
$nama = "CINTYA AZWA SAFRINA R.H";
$nim  = "E41250631";

$waktu_server = date("Y-m-d H:i:s");
$versi_php    = phpversion();
$os_server    = PHP_OS;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Info Server - Tugas Mandiri Acara 1</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            padding-top: 40px;
        }
        table {
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px 20px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        caption {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <table>
        <caption>Informasi Mahasiswa & Server</caption>
        <tr>
            <th>Keterangan</th>
            <th>Nilai</th>
        </tr>
        <tr>
            <td>Nama</td>
            <td><?php echo $nama; ?></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td><?php echo $nim; ?></td>
        </tr>
        <tr>
            <td>Waktu Server Saat Ini</td>
            <td><?php echo $waktu_server; ?></td>
        </tr>
        <tr>
            <td>Versi PHP</td>
            <td><?php echo $versi_php; ?></td>
        </tr>
        <tr>
            <td>Sistem Operasi Server</td>
            <td><?php echo $os_server; ?></td>
        </tr>
    </table>
</body>
</html>