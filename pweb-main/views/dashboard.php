<?php ob_start(); ?>

<h2 class="mb-4">
    <i class="bi bi-speedometer2 me-2"></i>Dashboard
</h2>

<div class="row mb-4">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="bi bi-hand-wave me-2"></i>Selamat Datang, <?= e($_SESSION['nama_lengkap']) ?>!
                </h4>
                <p class="card-text mb-0">
                    Anda login sebagai <strong><?= e(ucfirst($_SESSION['role'])) ?></strong>.
                    Terakhir login: <?= date('d F Y, H:i') ?> WIB.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0"><?= $totalMahasiswa ?? 0 ?></h3>
                        <p class="text-muted mb-0">Total Mahasiswa</p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="index.php?page=mahasiswa" class="text-primary text-decoration-none">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-person-check fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0"><?= $totalUsers ?? 0 ?></h3>
                        <p class="text-muted mb-0">Total Users</p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <span class="text-muted">Pengguna Terdaftar</span>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-info h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-mortarboard fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0"><?= count($statsByJurusan ?? []) ?></h3>
                        <p class="text-muted mb-0">Jurusan</p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <span class="text-muted">Program Studi Aktif</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stats by Jurusan -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-bar-chart me-2"></i>Mahasiswa per Jurusan
            </div>
            <div class="card-body">
                <?php if (!empty($statsByJurusan)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($statsByJurusan as $stat): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= e($stat['jurusan']) ?>
                        <span class="badge bg-primary rounded-pill"><?= $stat['total'] ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="text-muted text-center mb-0">Belum ada data.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Stats by Semester -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <i class="bi bi-calendar3 me-2"></i>Mahasiswa per Semester
            </div>
            <div class="card-body">
                <?php if (!empty($statsBySemester)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($statsBySemester as $stat): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Semester <?= e($stat['semester']) ?>
                        <span class="badge bg-info rounded-pill"><?= $stat['total'] ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="text-muted text-center mb-0">Belum ada data.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header bg-secondary text-white">
        <i class="bi bi-lightning me-2"></i>Aksi Cepat
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-2">
                <a href="index.php?page=mahasiswa&action=create" class="btn btn-outline-primary w-100">
                    <i class="bi bi-person-plus me-2"></i>Tambah Mahasiswa
                </a>
            </div>
            <div class="col-md-4 mb-2">
                <a href="index.php?page=mahasiswa" class="btn btn-outline-info w-100">
                    <i class="bi bi-list-ul me-2"></i>Lihat Data Mahasiswa
                </a>
            </div>
            <div class="col-md-4 mb-2">
                <a href="index.php?page=profile" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-person-gear me-2"></i>Edit Profil
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/main.php';
?>
