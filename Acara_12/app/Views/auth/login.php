<?php
$error = $_GET['error'] ?? null;
$logout = $_GET['logout'] ?? null;
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Informasi Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5" style="max-width: 420px;">
        <div class="text-center mb-4">
            <img src="<?= $base ?>/assets/logo-polije.png" alt="Logo POLIJE" style="width:64px;height:auto;">
            <h1 class="h4 text-info-emphasis mt-2 mb-0">Politeknik Negeri Jember</h1>
            <p class="text-secondary">Sistem Informasi Akademik</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Login</h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger py-2">Username atau password salah.</div>
                <?php endif; ?>

                <?php if ($logout): ?>
                    <div class="alert alert-info py-2">Anda telah logout.</div>
                <?php endif; ?>

                <form action="<?= $base ?>/login/process" method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="admin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="12345" required>
                    </div>
                                        <button type="submit" class="btn btn-info text-white w-100">Login</button>
                    <div class="text-center mt-3">
                        <a href="#" class="text-decoration-none small">Lupa Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
