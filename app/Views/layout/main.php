<?php
$loggedIn = Auth::check();
$isAdminNav = Auth::isAdmin();
$page = $page ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Sistem Perpustakaan Kampus'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-teal: #ea580c; --primary-dark: #c2410c; --bg-soft: #fff7ed; }
        body { background-color: var(--bg-soft); font-family: 'Segoe UI', sans-serif; }
        .navbar-teal { background-color: var(--primary-teal); }
        .btn-teal { background-color: var(--primary-teal); color: white; }
        .btn-teal:hover { background-color: var(--primary-dark); color: white; }
        .text-teal { color: var(--primary-teal); }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); border-left: 5px solid var(--primary-teal); }
        .badge-tersedia { background-color: #d1fae5; color: #065f46; }
        .badge-habis { background-color: #ffe4e6; color: #9f1239; }
        .badge-dipinjam { background-color: #e0f2fe; color: #0369a1; }
    </style>
</head>
<body>
    <?php require __DIR__ . '/bg.php'; ?>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-teal shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= $loggedIn ? e(Auth::homeUrl()) : e(url('login')); ?>">
                <i class="fa-solid fa-book-bookmark me-2"></i>E-PERPUS KAMPUS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if ($loggedIn): ?>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'dashboard') ? 'active fw-semibold' : ''; ?>" href="<?= e(Auth::homeUrl()); ?>">
                            <i class="fa-solid fa-gauge me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'buku') ? 'active fw-semibold' : ''; ?>" href="<?= e(url('buku')); ?>">
                            <i class="fa-solid fa-book me-1"></i> <?= $isAdminNav ? 'Kelola Buku' : 'Katalog Buku'; ?>
                        </a>
                    </li>
                    <?php if ($isAdminNav): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'peminjaman') ? 'active fw-semibold' : ''; ?>" href="<?= e(url('peminjaman')); ?>">
                            <i class="fa-solid fa-hand-holding-hand me-1"></i> Transaksi Peminjaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'pengguna') ? 'active fw-semibold' : ''; ?>" href="<?= e(url('pengguna')); ?>">
                            <i class="fa-solid fa-users me-1"></i> Data Pengguna
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center text-white gap-3">
                    <span class="small">
                        <i class="fa-solid fa-circle-user me-1"></i><?= e($_SESSION['nama'] ?? ''); ?>
                        <span class="badge bg-light text-dark ms-1"><?= e(strtoupper($_SESSION['role'] ?? '')); ?></span>
                    </span>
                    <form action="<?= e(url('logout')); ?>" method="POST" class="m-0">
                        <?= csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <?php require __DIR__ . '/flash.php'; ?>
        <?= $content; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
