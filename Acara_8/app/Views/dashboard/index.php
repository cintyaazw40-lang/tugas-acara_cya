<?php
/** @var string $username */
$justLoggedIn = $_GET['login'] ?? null;
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="d-flex align-items-center gap-3 mb-4">
            <img src="<?= $base ?>/assets/logo-polije.png" alt="Logo POLIJE" style="width:56px;height:auto;">
            <div>
                <h1 class="h3 text-info-emphasis mb-0">Sistem Informasi Akademik</h1>
                <p class="text-secondary mb-0">Dashboard</p>
            </div>
        </div>

        <?php if ($justLoggedIn): ?>
            <div class="alert alert-success">Selamat datang, Admin.</div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm mb-4" style="max-width: 480px;">
            <div class="card-body">
                <p class="mb-0">Selamat datang, <strong><?= htmlspecialchars($username) ?></strong>.</p>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-outline-info" href="<?= $base ?>/mahasiswa">Mahasiswa</a>
            <a class="btn btn-outline-info" href="<?= $base ?>/prodi">Program Studi</a>
            <a class="btn btn-outline-info" href="<?= $base ?>/matakuliah">Mata Kuliah</a>
            <a class="btn btn-outline-secondary" href="<?= $base ?>/logout">Logout</a>
        </div>
    </main>
</body>
</html>
