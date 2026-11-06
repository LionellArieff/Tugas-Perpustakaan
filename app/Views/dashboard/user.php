<?php
$page = 'dashboard';
$title = 'Dashboard Pengguna - E-Perpus Kampus';
ob_start();
?>
<div class="mb-4">
    <h3 class="fw-bold mb-1">Halo, <?= e($_SESSION['nama']); ?> 👋</h3>
    <p class="text-muted mb-0">Cari buku dan cek ketersediaannya sebelum datang ke perpustakaan.</p>
</div>

<div class="card card-custom p-4 mb-4">
    <form action="index.php" method="GET">
        <input type="hidden" name="page" value="buku">
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="text" name="q" class="form-control" placeholder="Cari judul, penulis, kategori, atau kode buku...">
            <button class="btn btn-teal px-4" type="submit">Cari</button>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card stat-card p-3"><div class="text-muted small">Judul Buku</div><div class="fs-2 fw-bold"><?= e($stats['total_judul']); ?></div></div></div>
    <div class="col-md-4"><div class="card stat-card p-3"><div class="text-muted small">Buku Tersedia</div><div class="fs-2 fw-bold text-success"><?= e($stats['tersedia']); ?></div></div></div>
    <div class="col-md-4"><div class="card stat-card p-3"><div class="text-muted small">Buku Habis</div><div class="fs-2 fw-bold text-danger"><?= e($stats['habis']); ?></div></div></div>
</div>

<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="fa-solid fa-circle-check text-success me-2"></i>Buku yang Tersedia</h5>
        <a href="<?= e(url('buku')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Kode</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Stok</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($tersedia)): ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada buku yang tersedia.</td></tr>
            <?php else: foreach ($tersedia as $b): ?>
                <tr>
                    <td class="fw-bold"><?= e($b['id_buku']); ?></td>
                    <td><?= e($b['judul']); ?></td>
                    <td><?= e($b['penulis']); ?></td>
                    <td><?= e($b['kategori']); ?></td>
                    <td><span class="badge bg-secondary"><?= e($b['stok']); ?></span></td>
                    <td><a class="btn btn-sm btn-info text-white" href="<?= e(url('buku', 'detail', ['id' => $b['id_buku']])); ?>">Detail</a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout/main.php';
