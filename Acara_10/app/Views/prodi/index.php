<?php
/** @var array $daftarProdi */
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Program Studi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="d-flex align-items-center gap-3 mb-4">
            <img src="<?= $base ?>/assets/logo-polije.png" alt="Logo POLIJE" style="width:56px;height:auto;">
            <div>
                <h1 class="h3 text-info-emphasis mb-0">Sistem Informasi Akademik</h1>
                <p class="text-secondary mb-0">Program Studi</p>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="<?= $base ?>/prodi/create" class="btn btn-success">+ Tambah Prodi</a>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daftarProdi as $prodi): ?>
                        <tr>
                            <td><?= htmlspecialchars($prodi['kode']) ?></td>
                            <td><?= htmlspecialchars($prodi['nama']) ?></td>
                            <td class="d-flex gap-1">
                                <a href="<?= $base ?>/prodi/<?= $prodi['id'] ?>/edit" class="btn btn-sm btn-outline-warning">Edit</a>
                                <form action="<?= $base ?>/prodi/<?= $prodi['id'] ?>/delete" method="post"
                                      onsubmit="return confirm('Yakin ingin menghapus prodi ini?');">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a class="btn btn-outline-secondary" href="<?= $base ?>/dashboard">Kembali ke Dashboard</a>
    </main>
</body>
</html>
