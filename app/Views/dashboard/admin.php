<?php
$page = 'dashboard';
$title = 'Dashboard Admin - E-Perpus Kampus';
ob_start();
?>
<div class="mb-4">
    <h3 class="fw-bold mb-1">Dashboard Admin</h3>
    <p class="text-muted mb-0">Selamat datang, <b><?= e($_SESSION['nama']); ?></b>. Ringkasan data perpustakaan hari ini.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6"><div class="card stat-card p-3"><div class="text-muted small">Judul Buku</div><div class="fs-2 fw-bold"><?= e($stats['total_judul']); ?></div></div></div>
    <div class="col-md-3 col-6"><div class="card stat-card p-3"><div class="text-muted small">Total Stok (eksemplar)</div><div class="fs-2 fw-bold"><?= e($stats['total_stok']); ?></div></div></div>
    <div class="col-md-3 col-6"><div class="card stat-card p-3"><div class="text-muted small">Buku Tersedia</div><div class="fs-2 fw-bold text-success"><?= e($stats['tersedia']); ?></div></div></div>
    <div class="col-md-3 col-6"><div class="card stat-card p-3"><div class="text-muted small">Buku Habis</div><div class="fs-2 fw-bold text-danger"><?= e($stats['habis']); ?></div></div></div>
    <div class="col-md-3 col-6"><div class="card stat-card p-3"><div class="text-muted small">Pengguna Terdaftar</div><div class="fs-2 fw-bold"><?= e($stats['total_user']); ?></div></div></div>
    <div class="col-md-3 col-6"><div class="card stat-card p-3"><div class="text-muted small">Peminjaman Aktif</div><div class="fs-2 fw-bold"><?= e($stats['pinjam_aktif']); ?></div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>Stok Menipis (&le; 2)</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light"><tr><th>Kode</th><th>Judul</th><th>Stok</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php if (empty($lowStock)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">Semua stok aman.</td></tr>
                    <?php else: foreach ($lowStock as $b): ?>
                        <tr>
                            <td class="fw-bold"><?= e($b['id_buku']); ?></td>
                            <td><?= e($b['judul']); ?></td>
                            <td><?= e($b['stok']); ?></td>
                            <td><span class="badge <?= $b['status'] === 'Tersedia' ? 'bg-success' : 'bg-danger'; ?> rounded-pill"><?= e($b['status']); ?></span></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold mb-3">Menu Cepat</h5>
            <div class="d-grid gap-2">
                <a href="<?= e(url('buku')); ?>" class="btn btn-teal rounded-pill"><i class="fa-solid fa-book me-1"></i> Kelola Data Buku &amp; Stok</a>
                <a href="<?= e(url('peminjaman')); ?>" class="btn btn-outline-secondary rounded-pill"><i class="fa-solid fa-hand-holding-hand me-1"></i> Transaksi Peminjaman</a>
                <a href="<?= e(url('pengguna')); ?>" class="btn btn-outline-secondary rounded-pill"><i class="fa-solid fa-users me-1"></i> Lihat Data Pengguna</a>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout/main.php';
