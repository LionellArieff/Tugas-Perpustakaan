<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Perpus Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-orange: #ea580c; --primary-dark: #c2410c; --bg-soft: #fff7ed; }
        body { background-color: var(--bg-soft); font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-login { border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(234, 88, 12, 0.15); border-top: 5px solid var(--primary-orange); width: 100%; max-width: 400px; }
        .btn-orange { background-color: var(--primary-orange); color: white; border: none; }
        .btn-orange:hover { background-color: var(--primary-dark); color: white; }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../layout/bg.php'; ?>
    <div class="card card-login p-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold" style="color: var(--primary-orange);"><i class="fa-solid fa-book-bookmark me-2"></i>E-Perpus</h3>
            <p class="text-muted small">Silakan login untuk mengakses sistem</p>
        </div>

        <?php require __DIR__ . '/../layout/flash.php'; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center small rounded-3 p-2 border-0 border-start border-danger border-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> <?= e($error); ?>
            </div>
        <?php endif; ?>

        <form action="<?= e(url('login-process')); ?>" method="POST">
            <?= csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Username / Email</label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control border-start-0 px-2" placeholder="Masukkan username atau email" value="<?= e($old['username'] ?? ''); ?>" maxlength="100" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold">Password</label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 px-2" placeholder="Masukkan password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-orange w-100 rounded-pill fw-bold py-2 shadow-sm">
                Masuk <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="<?= e(url('register')); ?>" class="small">Belum punya akun? Daftar di sini</a>
        </div>
    </div>
</body>
</html>
