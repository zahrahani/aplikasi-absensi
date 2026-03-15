<?php ob_start(); ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-person-lines-fill me-2"></i>Detail Mahasiswa</h2>
            <a href="index.php?page=mahasiswa" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Photo Column -->
                    <div class="col-md-4 text-center mb-4">
                        <?php if ($mahasiswa['foto']): ?>
                        <img src="uploads/mahasiswa/<?= e($mahasiswa['foto']) ?>"
                             alt="Foto <?= e($mahasiswa['nama']) ?>"
                             class="img-fluid rounded-circle mb-3"
                             style="width: 200px; height: 200px; object-fit: cover;">
                        <?php else: ?>
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 200px; height: 200px;">
                            <i class="bi bi-person text-white" style="font-size: 5rem;"></i>
                        </div>
                        <?php endif; ?>

                        <h4 class="mb-1"><?= e($mahasiswa['nama']) ?></h4>
                        <p class="text-muted"><code><?= e($mahasiswa['nim']) ?></code></p>
                        <span class="badge bg-primary fs-6"><?= e($mahasiswa['jurusan']) ?></span>
                    </div>

                    <!-- Details Column -->
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%" class="text-muted">
                                    <i class="bi bi-hash me-2"></i>NIM
                                </th>
                                <td><code class="fs-5"><?= e($mahasiswa['nim']) ?></code></td>
                            </tr>
                            <tr>
                                <th class="text-muted">
                                    <i class="bi bi-person me-2"></i>Nama Lengkap
                                </th>
                                <td><?= e($mahasiswa['nama']) ?></td>
                            </tr>
                            <tr>
                                <th class="text-muted">
                                    <i class="bi bi-envelope me-2"></i>Email
                                </th>
                                <td>
                                    <a href="mailto:<?= e($mahasiswa['email']) ?>">
                                        <?= e($mahasiswa['email']) ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">
                                    <i class="bi bi-telephone me-2"></i>Telepon
                                </th>
                                <td>
                                    <?php if ($mahasiswa['telepon']): ?>
                                    <a href="tel:<?= e($mahasiswa['telepon']) ?>">
                                        <?= e($mahasiswa['telepon']) ?>
                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">
                                    <i class="bi bi-mortarboard me-2"></i>Jurusan
                                </th>
                                <td><?= e($mahasiswa['jurusan']) ?></td>
                            </tr>
                            <tr>
                                <th class="text-muted">
                                    <i class="bi bi-calendar me-2"></i>Semester
                                </th>
                                <td>
                                    <span class="badge bg-info">Semester <?= e($mahasiswa['semester']) ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">
                                    <i class="bi bi-geo-alt me-2"></i>Alamat
                                </th>
                                <td>
                                    <?php if ($mahasiswa['alamat']): ?>
                                    <?= nl2br(e($mahasiswa['alamat'])) ?>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">
                                    <i class="bi bi-clock me-2"></i>Terdaftar
                                </th>
                                <td>
                                    <?= date('d F Y, H:i', strtotime($mahasiswa['created_at'])) ?> WIB
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">
                                    <i class="bi bi-clock-history me-2"></i>Diperbarui
                                </th>
                                <td>
                                    <?= date('d F Y, H:i', strtotime($mahasiswa['updated_at'])) ?> WIB
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php?page=mahasiswa&action=edit&id=<?= $mahasiswa['id'] ?>"
                       class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                    <button type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-2"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data mahasiswa:</p>
                <p class="fw-bold"><?= e($mahasiswa['nama']) ?> (<?= e($mahasiswa['nim']) ?>)?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="index.php?page=mahasiswa&action=delete" class="d-inline">
                    <input type="hidden" name="id" value="<?= $mahasiswa['id'] ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/main.php';
?>
