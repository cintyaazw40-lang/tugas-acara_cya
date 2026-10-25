<?php
/** @var array $daftarMahasiswa Array of ['mahasiswa' => Mahasiswa, 'prodi_nama' => string] */
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$keyword = htmlspecialchars($_GET['q'] ?? '');
$flash = Flash::get();

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

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <form action="<?= $base ?>/mahasiswa" method="get" class="d-flex gap-2">
                <input type="text" name="q" class="form-control" placeholder="Cari nama atau NIM..." value="<?= $keyword ?>">
                <button type="submit" class="btn btn-info text-white">Cari</button>
                <?php if ($keyword !== ''): ?>
                    <a href="<?= $base ?>/mahasiswa" class="btn btn-outline-secondary">Reset</a>
                <?php endif; ?>
            </form>
            <a href="<?= $base ?>/mahasiswa/create" class="btn btn-success">+ Tambah Mahasiswa</a>
        </div>

        <div class="mb-3">
            <span class="badge text-bg-info fs-6">
                Total Mahasiswa: <?= count($daftarMahasiswa) ?>
            </span>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarMahasiswa)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Data tidak ditemukan</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($daftarMahasiswa as $item): ?>
                            <?php $mhs = $item['mahasiswa']; /** @var Mahasiswa $mhs */ ?>
                            <tr>
                                <td><?= htmlspecialchars($mhs->getNim()) ?></td>
                                <td><?= htmlspecialchars($mhs->getNama()) ?></td>
                                <td><?= htmlspecialchars($mhs->getEmail()) ?></td>
                                <td><?= htmlspecialchars($item['prodi_nama']) ?></td>
                                <td><?= htmlspecialchars($mhs->getAngkatan()) ?></td>
                                <td>
                                    <?php $warna = $badgeStatus[$mhs->getStatus()] ?? 'secondary'; ?>
                                    <span class="badge text-bg-<?= $warna ?>"><?= htmlspecialchars($mhs->getStatus()) ?></span>
                                </td>
                                <td class="d-flex gap-1">
                                    <a href="<?= $base ?>/mahasiswa/<?= $mhs->getId() ?>/edit" class="btn btn-sm btn-outline-warning">Edit</a>
                                    <form action="<?= $base ?>/mahasiswa/<?= $mhs->getId() ?>/delete" method="post"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>