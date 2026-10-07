<?php
/** @var array $daftarMatakuliah */
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mata Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="d-flex align-items-center gap-3 mb-4">
            <img src="<?= $base ?>/assets/logo-polije.png" alt="Logo POLIJE" style="width:56px;height:auto;">
            <div>
                <h1 class="h3 text-info-emphasis mb-0">Sistem Informasi Akademik</h1>
                <p class="text-secondary mb-0">Mata Kuliah</p>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="<?= $base ?>/matakuliah/create" class="btn btn-success">+ Tambah Mata Kuliah</a>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>SKS</th>
                            <th>Prodi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daftarMatakuliah as $mk): ?>
                        <tr>
                            <td><?= htmlspecialchars($mk['kode']) ?></td>
                            <td><?= htmlspecialchars($mk['nama']) ?></td>
                            <td><?= htmlspecialchars($mk['sks']) ?></td>
                            <td><?= htmlspecialchars($mk['prodi_nama']) ?></td>
                            <td class="d-flex gap-1">
                                <a href="<?= $base ?>/matakuliah/<?= $mk['id'] ?>/edit" class="btn btn-sm btn-outline-warning">Edit</a>
                                <form action="<?= $base ?>/matakuliah/<?= $mk['id'] ?>/delete" method="post"
                                      onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?');">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a class="btn btn-outline-secondary" href="<?= $base ?>/dashboard">Kembali ke Dashboard awal</a>
    </main>
</body>
</html>
