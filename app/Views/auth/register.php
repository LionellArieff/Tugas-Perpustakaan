<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Perpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <?php require __DIR__ . '/../layout/bg.php'; ?>
    <div class="card card-glass p-4 shadow-sm my-4" style="width: 100%; max-width: 420px; border-top: 5px solid var(--primary-orange);">
        <h3 class="text-center fw-bold" style="color: var(--primary-orange);">Daftar Akun</h3>
        <p class="text-center text-muted small">Pendaftaran khusus mahasiswa / pengguna perpustakaan (role: User)</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger small">
                <ul class="mb-0 ps-3">
                    <?php foreach ($errors as $err): ?><li><?= e($err); ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= e(url('register-process')); ?>" method="POST">
            <?= csrf_field(); ?>
            <div class="mb-2">
                <label class="form-label small">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= e($old['nama'] ?? ''); ?>" minlength="3" maxlength="100" required>
            </div>
            <div class="mb-2">
                <label class="form-label small">Username</label>
                <input type="text" name="username" class="form-control" value="<?= e($old['username'] ?? ''); ?>" pattern="[A-Za-z0-9_]{4,30}" title="4-30 karakter: huruf, angka, underscore" required>
            </div>
            <div class="mb-2">
                <label class="form-label small">Email</label>
                <input type="email" name="email" class="form-control" value="<?= e($old['email'] ?? ''); ?>" maxlength="100" required>
            </div>
            <div class="mb-2">
                <label class="form-label small">Password (minimal 6 karakter)</label>
                <input type="password" name="password" class="form-control" minlength="6" maxlength="72" required>
            </div>
            <div class="mb-3">
                <label class="form-label small">Konfirmasi Password</label>
                <input type="password" name="konfirmasi" class="form-control" minlength="6" maxlength="72" required>
            </div>
            <button type="submit" class="btn w-100 text-white fw-bold" style="background-color: var(--primary-orange);">Daftar Sekarang</button>
        </form>
        <div class="text-center mt-3"><a href="<?= e(url('login')); ?>" class="small">Sudah punya akun? Login</a></div>
    </div>
</body>
</html>
