<?php ob_start(); ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-person-plus me-2"></i>Tambah Mahasiswa</h2>
            <a href="index.php?page=mahasiswa" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= e($errors['general']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form method="POST"
                      action="index.php?page=mahasiswa&action=create"
                      enctype="multipart/form-data"
                      id="mahasiswaForm"
                      novalidate>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nim" class="form-label">
                                NIM <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control <?= isset($errors['nim']) ? 'is-invalid' : '' ?>"
                                   id="nim"
                                   name="nim"
                                   value="<?= e($old['nim'] ?? '') ?>"
                                   placeholder="Masukkan NIM"
                                   required>
                            <div class="invalid-feedback">
                                <?= e($errors['nim'] ?? 'NIM wajib diisi.') ?>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                                   id="nama"
                                   name="nama"
                                   value="<?= e($old['nama'] ?? '') ?>"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                            <div class="invalid-feedback">
                                <?= e($errors['nama'] ?? 'Nama wajib diisi.') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                   id="email"
                                   name="email"
                                   value="<?= e($old['email'] ?? '') ?>"
                                   placeholder="Masukkan email"
                                   required>
                            <div class="invalid-feedback">
                                <?= e($errors['email'] ?? 'Format email tidak valid.') ?>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text"
                                   class="form-control"
                                   id="telepon"
                                   name="telepon"
                                   value="<?= e($old['telepon'] ?? '') ?>"
                                   placeholder="Masukkan nomor telepon">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jurusan" class="form-label">
                                Jurusan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?= isset($errors['jurusan']) ? 'is-invalid' : '' ?>"
                                    id="jurusan"
                                    name="jurusan"
                                    required>
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="Teknik Informatika" <?= ($old['jurusan'] ?? '') === 'Teknik Informatika' ? 'selected' : '' ?>>
                                    Teknik Informatika
                                </option>
                                <option value="Sistem Informasi" <?= ($old['jurusan'] ?? '') === 'Sistem Informasi' ? 'selected' : '' ?>>
                                    Sistem Informasi
                                </option>
                                <option value="Teknik Komputer" <?= ($old['jurusan'] ?? '') === 'Teknik Komputer' ? 'selected' : '' ?>>
                                    Teknik Komputer
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                <?= e($errors['jurusan'] ?? 'Jurusan wajib dipilih.') ?>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="semester" class="form-label">
                                Semester <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?= isset($errors['semester']) ? 'is-invalid' : '' ?>"
                                    id="semester"
                                    name="semester"
                                    required>
                                <option value="">-- Pilih Semester --</option>
                                <?php for ($i = 1; $i <= 14; $i++): ?>
                                <option value="<?= $i ?>" <?= ($old['semester'] ?? '') == $i ? 'selected' : '' ?>>
                                    Semester <?= $i ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= e($errors['semester'] ?? 'Semester wajib dipilih.') ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control"
                                  id="alamat"
                                  name="alamat"
                                  rows="3"
                                  placeholder="Masukkan alamat lengkap"><?= e($old['alamat'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file"
                               class="form-control <?= isset($errors['foto']) ? 'is-invalid' : '' ?>"
                               id="foto"
                               name="foto"
                               accept="image/jpeg,image/png,image/gif">
                        <div class="invalid-feedback">
                            <?= e($errors['foto'] ?? '') ?>
                        </div>
                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>

                        <!-- Preview -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php?page=mahasiswa" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview
document.getElementById('foto').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const img = preview.querySelector('img');

    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(this.files[0]);
    } else {
        preview.style.display = 'none';
    }
});
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . 'layouts/main.php';
?>
