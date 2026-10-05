<?php
/** @var array $daftarMahasiswa */
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

$badgeStatus = [
    'aktif' => 'success',
    'cuti'  => 'warning',
    'lulus' => 'secondary',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="d-flex align-items-center gap-3 mb-4">
            <img src="<?= $base ?>/assets/logo-polije.png" alt="Logo POLIJE" style="width:56px;height:auto;">
            <div>
                <h1 class="h3 text-info-emphasis mb-0">Sistem Informasi Akademik</h1>
                <p class="text-secondary mb-0">Daftar Mahasiswa</p>
            </div>
        </div>

        <div class="alert alert-secondary">
            Halaman ini dilindungi <strong>AuthMiddleware</strong> — hanya bisa diakses setelah login.
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Prodi</th>
                            <th>Angkatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarMahasiswa)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data mahasiswa</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($daftarMahasiswa as $mhs): ?>
                            <tr>
                                <td><?= htmlspecialchars($mhs['nim']) ?></td>
                                <td><?= htmlspecialchars($mhs['nama']) ?></td>
                                <td><?= htmlspecialchars($mhs['email']) ?></td>
                                <td><?= htmlspecialchars($mhs['prodi_nama']) ?></td>
                                <td><?= htmlspecialchars($mhs['angkatan']) ?></td>
                                <td>
                                    <?php $warna = $badgeStatus[$mhs['status']] ?? 'secondary'; ?>
                                    <span class="badge text-bg-<?= $warna ?>"><?= htmlspecialchars($mhs['status']) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a class="btn btn-outline-secondary" href="<?= $base ?>/dashboard">Kembali ke Dashboard</a>
    </main>
</body>
</html>
