<?php
$page = 'peminjaman';
$title = 'Transaksi Peminjaman - E-Perpus Kampus';
ob_start();
?>
<div class="card card-custom p-4 mb-4">
    <h4 class="fw-bold text-dark mb-3"><i class="fa-solid fa-hand-holding-hand me-2 text-teal"></i>Form Peminjaman Buku</h4>
    <form action="<?= e(url('peminjaman', 'store')); ?>" method="POST">
        <?= csrf_field(); ?>
        <div class="row align-items-end g-3">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">NIM Mahasiswa *</label>
                <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM" maxlength="20" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Pilih Buku *</label>
                <select name="id_buku" class="form-select" required>
                    <option value="">-- Pilih Buku --</option>
                    <?php foreach ($bukuList as $b): ?>
                        <option value="<?= e($b['id_buku']); ?>" <?= ($b['stok'] <= 0) ? 'disabled' : ''; ?>>
                            <?= e($b['id_buku']); ?> - <?= e($b['judul']); ?> (Stok: <?= e($b['stok']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Jumlah Pinjam *</label>
                <input type="number" name="jumlah" class="form-control" min="1" max="3" value="1" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Pinjam *</label>
                <input type="date" name="tanggal_pinjam" class="form-control" value="<?= e(date('Y-m-d')); ?>" required>
            </div>
            <div class="col-12 text-end mt-3">
                <button type="submit" class="btn btn-teal px-4 rounded-3 py-2">
                    <i class="fa-solid fa-paper-plane me-1"></i> Simpan Peminjaman
                </button>
            </div>
        </div>
    </form>
</div>

<div class="card card-custom p-4">
    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-clock-rotate-left me-2 text-teal"></i>Daftar Peminjaman Aktif &amp; Riwayat</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>ID Transaksi</th><th>NIM</th><th>Judul Buku</th><th>Jumlah</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th class="text-center">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (empty($peminjaman)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada transaksi peminjaman.</td></tr>
                <?php else: foreach ($peminjaman as $p): ?>
                    <tr>
                        <td><span class="fw-bold text-dark"><?= e($p['id_peminjaman']); ?></span></td>
                        <td><?= e($p['nim']); ?></td>
                        <td><?= e($p['judul']); ?></td>
                        <td><span class="badge bg-secondary"><?= e($p['jumlah'] ?? 1); ?> Unit</span></td>
                        <td><?= e(date('d/m/Y', strtotime($p['tanggal_pinjam']))); ?></td>
                        <td><?= $p['tanggal_kembali'] ? e(date('d/m/Y', strtotime($p['tanggal_kembali']))) : '-'; ?></td>
                        <td>
                            <span class="badge <?= ($p['status_peminjaman'] === 'Dipinjam') ? 'badge-dipinjam' : 'badge-tersedia'; ?> px-3 py-2 rounded-pill"><?= e($p['status_peminjaman']); ?></span>
                        </td>
                        <td class="text-center">
                            <?php if ($p['status_peminjaman'] === 'Dipinjam'): ?>
                                <form action="<?= e(url('peminjaman', 'kembalikan')); ?>" method="POST" class="d-inline">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="id_peminjaman" value="<?= e($p['id_peminjaman']); ?>">
                                    <input type="hidden" name="tanggal_kembali" value="<?= e(date('Y-m-d')); ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3"><i class="fa-solid fa-rotate-left me-1"></i> Kembalikan</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small"><i class="fa-solid fa-check-double text-success me-1"></i>Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout/main.php';
