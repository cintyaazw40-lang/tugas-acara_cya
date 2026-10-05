<?php
/** @var array $daftarProdi */
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5" style="max-width: 600px;">
        <div class="d-flex align-items-center gap-3 mb-4">
            <img src="<?= $base ?>/assets/logo-polije.png" alt="Logo POLIJE" style="width:56px;height:auto;">
            <div>
                <h1 class="h3 text-info-emphasis mb-0">Sistem Informasi Akademik</h1>
                <p class="text-secondary mb-0">Tambah Mahasiswa</p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">NIM dan Nama wajib diisi.</div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="<?= $base ?>/mahasiswa" method="post">
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program Studi</label>
                        <select name="prodi_id" class="form-select" required>
                            <option value="" selected disabled>Pilih program studi</option>
                            <?php foreach ($daftarProdi as $prodi): ?>
                                <option value="<?= $prodi['id'] ?>"><?= htmlspecialchars($prodi['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Angkatan</label>
                        <input type="number" name="angkatan" class="form-control" value="<?= date('Y') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif" selected>Aktif</option>
                            <option value="cuti">Cuti</option>
                            <option value="lulus">Lulus</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-info text-white">Simpan</button>
                    <a href="<?= $base ?>/mahasiswa" class="btn btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
