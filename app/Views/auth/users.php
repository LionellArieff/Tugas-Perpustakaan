<?php
$page = 'pengguna';
$title = 'Data Pengguna - E-Perpus Kampus';
ob_start();
?>
<div class="card card-custom p-4">
    <h4 class="fw-bold mb-1"><i class="fa-solid fa-users me-2 text-teal"></i>Data Pengguna Terdaftar</h4>
    <p class="text-muted small">Halaman ini hanya dapat diakses oleh Admin. Total: <?= count($users); ?> akun.</p>
    <div class="table-responsive">
        <table class="table table-hover align-middle mt-2">
            <thead class="table-light">
                <tr><th>ID</th><th>Nama Lengkap</th><th>Username</th><th>Email</th><th>Role</th><th>Terdaftar</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= e($u['id']); ?></td>
                        <td><?= e($u['nama']); ?></td>
                        <td><?= e($u['username']); ?></td>
                        <td><?= e($u['email']); ?></td>
                        <td><span class="badge <?= $u['role'] === 'admin' ? 'bg-danger' : 'bg-secondary'; ?>"><?= e(strtoupper($u['role'])); ?></span></td>
                        <td class="small text-muted"><?= e(date('d/m/Y H:i', strtotime($u['created_at']))); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout/main.php';
