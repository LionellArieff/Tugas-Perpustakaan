<?php
$page = 'buku';
$title = ($isAdmin ? 'Kelola Buku' : 'Katalog Buku') . ' - E-Perpus Kampus';
ob_start();
?>
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-book me-2 text-teal"></i><?= $isAdmin ? 'Kelola Data Buku' : 'Katalog Buku'; ?></h4>
            <p class="text-muted small mb-0">
                <?= $isAdmin ? 'Tambah, ubah, hapus data buku dan atur stok.' : 'Cari buku dan lihat stok serta status ketersediaannya.'; ?>
            </p>
        </div>
        <?php if ($isAdmin): ?>
        <button class="btn btn-teal rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahBuku">
            <i class="fa-solid fa-plus me-1"></i> Tambah Buku
        </button>
        <?php endif; ?>
    </div>

    <!-- Pencarian: judul, penulis, kategori, kode buku -->
    <form action="index.php" method="GET" class="row g-2 mb-4">
        <input type="hidden" name="page" value="buku">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Cari judul, penulis, kategori, atau kode buku..." value="<?= e($keyword); ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategoriList as $k): ?>
                    <option value="<?= e($k); ?>" <?= ($k === $kategori) ? 'selected' : ''; ?>><?= e($k); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-teal flex-fill" type="submit">Cari</button>
            <a href="<?= e(url('buku')); ?>" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th><th>Kode</th><th>Judul</th><th>Penulis</th><th>Kategori</th>
                    <?php if ($isAdmin): ?><th>Penerbit</th><th>Tahun</th><?php endif; ?>
                    <th>Stok</th><th>Status</th><th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bukuList)): ?>
                    <tr><td colspan="<?= $isAdmin ? 10 : 8; ?>" class="text-center text-muted py-4">Data buku tidak ditemukan.</td></tr>
                <?php else: $no = 1; foreach ($bukuList as $b): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><span class="fw-bold text-dark"><?= e($b['id_buku']); ?></span></td>
                        <td><?= e($b['judul']); ?></td>
                        <td><?= e($b['penulis']); ?></td>
                        <td><?= e($b['kategori']); ?></td>
                        <?php if ($isAdmin): ?>
                            <td><?= e($b['penerbit']); ?></td>
                            <td><?= e($b['tahun_terbit']); ?></td>
                            <td style="min-width:150px">
                                <!-- Manajemen stok (Admin) -->
                                <form action="<?= e(url('buku', 'stok')); ?>" method="POST" class="d-flex gap-1">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="id_buku" value="<?= e($b['id_buku']); ?>">
                                    <input type="number" name="stok" min="0" max="100000" value="<?= e($b['stok']); ?>" class="form-control form-control-sm" style="width:80px" required>
                                    <button class="btn btn-sm btn-outline-success" title="Simpan stok"><i class="fa-solid fa-check"></i></button>
                                </form>
                            </td>
                        <?php else: ?>
                            <td><span class="badge bg-secondary"><?= e($b['stok']); ?></span></td>
                        <?php endif; ?>
                        <td>
                            <span class="badge <?= ($b['status'] === 'Tersedia') ? 'bg-success' : 'bg-danger'; ?> px-3 py-2 rounded-pill"><?= e($b['status']); ?></span>
                        </td>
                        <td class="text-center text-nowrap">
                            <a class="btn btn-sm btn-info text-white" href="<?= e(url('buku', 'detail', ['id' => $b['id_buku']])); ?>">Detail</a>
                            <?php if ($isAdmin): ?>
                                <button type="button" class="btn btn-sm btn-warning text-white btn-edit"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit"
                                    data-id="<?= e($b['id_buku']); ?>"
                                    data-judul="<?= e($b['judul']); ?>"
                                    data-penulis="<?= e($b['penulis']); ?>"
                                    data-penerbit="<?= e($b['penerbit']); ?>"
                                    data-tahun="<?= e($b['tahun_terbit']); ?>"
                                    data-kategori="<?= e($b['kategori']); ?>"
                                    data-stok="<?= e($b['stok']); ?>">Edit</button>
                                <form action="<?= e(url('buku', 'delete')); ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="id_buku" value="<?= e($b['id_buku']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <div class="text-muted small">Menampilkan <?= count($bukuList); ?> buku.</div>
</div>

<?php if ($isAdmin): ?>
<datalist id="listKategori">
    <?php foreach ($kategoriList as $k): ?><option value="<?= e($k); ?>"><?php endforeach; ?>
</datalist>

<!-- Modal Tambah (hanya dirender untuk Admin) -->
<div class="modal fade" id="modalTambahBuku" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content rounded-4 border-0">
        <div class="modal-header border-0">
            <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus-circle me-2 text-teal"></i>Tambah Buku Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="<?= e(url('buku', 'store')); ?>" method="POST">
            <?= csrf_field(); ?>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label small fw-semibold">Kode Buku</label>
                    <input type="text" name="id_buku" class="form-control" placeholder="Contoh: BK005" maxlength="20" required></div>
                <div class="mb-3"><label class="form-label small fw-semibold">Judul Buku</label>
                    <input type="text" name="judul" class="form-control" maxlength="150" required></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label small fw-semibold">Penulis</label>
                        <input type="text" name="penulis" class="form-control" maxlength="100" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label small fw-semibold">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" maxlength="100" required></div>
                </div>
                <div class="mb-3"><label class="form-label small fw-semibold">Kategori</label>
                    <input type="text" name="kategori" list="listKategori" class="form-control" maxlength="50" required></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label small fw-semibold">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" class="form-control" min="1900" max="<?= (int)date('Y') + 1; ?>" value="<?= date('Y'); ?>" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label small fw-semibold">Jumlah Stok</label>
                        <input type="number" name="stok" class="form-control" min="0" value="1" required></div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-teal rounded-pill px-4">Simpan Buku</button>
            </div>
        </form>
    </div></div>
</div>

<!-- Modal Edit (satu modal dipakai semua baris) -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content rounded-4 border-0">
        <div class="modal-header border-0">
            <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square text-warning me-2"></i>Edit Buku - <span id="editKodeLabel"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="<?= e(url('buku', 'update')); ?>" method="POST">
            <?= csrf_field(); ?>
            <input type="hidden" name="id_buku" id="editId">
            <div class="modal-body">
                <div class="mb-3"><label class="form-label small fw-semibold">Judul Buku</label>
                    <input type="text" name="judul" id="editJudul" class="form-control" maxlength="150" required></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label small fw-semibold">Penulis</label>
                        <input type="text" name="penulis" id="editPenulis" class="form-control" maxlength="100" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label small fw-semibold">Penerbit</label>
                        <input type="text" name="penerbit" id="editPenerbit" class="form-control" maxlength="100" required></div>
                </div>
                <div class="mb-3"><label class="form-label small fw-semibold">Kategori</label>
                    <input type="text" name="kategori" id="editKategori" list="listKategori" class="form-control" maxlength="50" required></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label small fw-semibold">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" id="editTahun" class="form-control" min="1900" max="<?= (int)date('Y') + 1; ?>" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label small fw-semibold">Jumlah Stok</label>
                        <input type="number" name="stok" id="editStok" class="form-control" min="0" required></div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning text-white rounded-pill px-4">Simpan Perubahan</button>
            </div>
        </form>
    </div></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var d = btn.dataset;
            document.getElementById('editId').value = d.id;
            document.getElementById('editKodeLabel').textContent = d.id;
            document.getElementById('editJudul').value = d.judul;
            document.getElementById('editPenulis').value = d.penulis;
            document.getElementById('editPenerbit').value = d.penerbit;
            document.getElementById('editKategori').value = d.kategori;
            document.getElementById('editTahun').value = d.tahun;
            document.getElementById('editStok').value = d.stok;
        });
    });
});
</script>
<?php endif; ?>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout/main.php';
