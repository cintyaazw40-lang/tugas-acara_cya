<?php
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Prodi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5" style="max-width: 500px;">
        <div class="d-flex align-items-center gap-3 mb-4">
            <img src="<?= $base ?>/assets/logo-polije.png" alt="Logo POLIJE" style="width:56px;height:auto;">
            <div>
                <h1 class="h3 text-info-emphasis mb-0">Sistem Informasi Akademik</h1>
                <p class="text-secondary mb-0">Tambah Program Studi</p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">Kode dan Nama wajib diisi.</div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="<?= $base ?>/prodi" method="post">
                    <div class="mb-3">
                        <label class="form-label">Kode Prodi</label>
                        <input type="text" name="kode" class="form-control" placeholder="TI" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Prodi</label>
                        <input type="text" name="nama" class="form-control" placeholder="Teknik Informatika" required>
                    </div>
                    <button type="submit" class="btn btn-info text-white">Simpan</button>
                    <a href="<?= $base ?>/prodi" class="btn btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
