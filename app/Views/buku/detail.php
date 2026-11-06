<?php
$page = 'buku';
$title = 'Detail Buku - E-Perpus Kampus';
$tersedia = ($buku['status'] === 'Tersedia' && (int)$buku['stok'] > 0);
ob_start();
?>
<a href="<?= e(url('buku')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill mb-3"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke daftar</a>
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <div>
            <div class="text-muted small">Kode: <b><?= e($buku['id_buku']); ?></b></div>
            <h3 class="fw-bold mb-0"><?= e($buku['judul']); ?></h3>
        </div>
        <span class="badge <?= $tersedia ? 'bg-success' : 'bg-danger'; ?> px-3 py-2 rounded-pill fs-6">
            <?= $tersedia ? 'Tersedia' : 'Habis'; ?>
        </span>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Penulis:</strong> <?= e($buku['penulis']); ?></li>
        <li class="list-group-item"><strong>Penerbit:</strong> <?= e($buku['penerbit']); ?></li>
        <li class="list-group-item"><strong>Tahun Terbit:</strong> <?= e($buku['tahun_terbit']); ?></li>
        <li class="list-group-item"><strong>Kategori:</strong> <?= e($buku['kategori']); ?></li>
        <li class="list-group-item"><strong>Stok:</strong> <?= e($buku['stok']); ?> eksemplar</li>
        <li class="list-group-item"><strong>Status:</strong> <?= $tersedia ? 'Tersedia untuk dipinjam' : 'Stok habis, belum dapat dipinjam'; ?></li>
    </ul>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout/main.php';
