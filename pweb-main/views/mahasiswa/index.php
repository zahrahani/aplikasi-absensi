<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people me-2"></i>Data Mahasiswa</h2>
    <a href="index.php?page=mahasiswa&action=create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Mahasiswa
    </a>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-3">
            <input type="hidden" name="page" value="mahasiswa">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text"
                           class="form-control"
                           name="search"
                           value="<?= e($search) ?>"
                           placeholder="Cari berdasarkan NIM, nama, atau jurusan...">
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-grid">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-body">
        <?php if (empty($mahasiswa)): ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
            <p class="text-muted mt-3">Belum ada data mahasiswa.</p>
            <a href="index.php?page=mahasiswa&action=create" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Tambah Mahasiswa Pertama
            </a>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Foto</th>
                        <th width="12%">NIM</th>
                        <th width="20%">Nama</th>
                        <th width="18%">Jurusan</th>
                        <th width="10%">Semester</th>
                        <th width="25%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = ($page - 1) * $perPage + 1;
                    foreach ($mahasiswa as $mhs):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <?php if ($mhs['foto']): ?>
                            <img src="uploads/mahasiswa/<?= e($mhs['foto']) ?>"
                                 alt="Foto"
                                 class="rounded-circle"
                                 width="40"
                                 height="40"
                                 style="object-fit: cover;">
                            <?php else: ?>
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-person text-white"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td><code><?= e($mhs['nim']) ?></code></td>
                        <td><?= e($mhs['nama']) ?></td>
                        <td><?= e($mhs['jurusan']) ?></td>
                        <td><span class="badge bg-info">Semester <?= e($mhs['semester']) ?></span></td>
                        <td>
                            <a href="index.php?page=mahasiswa&action=show&id=<?= $mhs['id'] ?>"
                               class="btn btn-sm btn-info"
                               title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="index.php?page=mahasiswa&action=edit&id=<?= $mhs['id'] ?>"
                               class="btn btn-sm btn-warning"
                               title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal<?= $mhs['id'] ?>"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?= $mhs['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus data mahasiswa:</p>
                                            <p class="fw-bold"><?= e($mhs['nama']) ?> (<?= e($mhs['nim']) ?>)?</p>
                                            <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form method="POST" action="index.php?page=mahasiswa&action=delete" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $mhs['id'] ?>">
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-trash me-2"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="index.php?page=mahasiswa&p=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">
                        <i class="bi bi-chevron-left"></i> Prev
                    </a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?page=mahasiswa&p=<?= $i ?>&search=<?= urlencode($search) ?>">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="index.php?page=mahasiswa&p=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <p class="text-center text-muted">
            Menampilkan <?= count($mahasiswa) ?> dari <?= $totalData ?> data
        </p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/main.php';
?>
