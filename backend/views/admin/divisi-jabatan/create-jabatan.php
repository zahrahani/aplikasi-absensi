<!-- Bootstrap JS -->
<script src="<?= pathJs('bootstrap') ?>"></script>

<link rel="stylesheet" href="<?= pathCss('karyawan') ?>"/>
<link rel="stylesheet" href="<?= pathCss('kCreate') ?>"/>
<link rel="stylesheet" href="<?= pathCss('divisi-jabatan') ?>"/>


<?= \displayFlashMessage() ?>

<!-- ── Section 1: Header + Filter + Stats ── -->
<section class="karyawan-header-section mb-4">
<h4 class="page-title">Tambah Jabatan</h4>
<p class="page-subtitle">Tambahkan jabatan baru ke dalam sistem</p>
</section>

<div class="dj-form-card mt-3">
  <form action="<?= BASE_URL ?>divisi-jabatan/create-jabatan" method="post">
    <input type="hidden" name="_token" value="<?= $_SESSION['csrf_token'] ?>">

    <div class="mb-3">
      <label class="form-label-custom" for="divisi_id">Divisi</label>
      <select
        class="form-input-custom <?= isset($errors['divisi_id']) ? 'is-invalid' : '' ?>"
        id="divisi_id"
        name="divisi_id"
      >
        <option value="">-- Pilih Divisi --</option>
        <?php foreach ($divisis as $divisi): ?>
          <option
            value="<?= e($divisi['divisi_id']) ?>"
            <?= ($old['divisi_id'] ?? '') === $divisi['divisi_id'] ? 'selected' : '' ?>
          >
            <?= e($divisi['nama_divisi']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <?= \messageError('divisi_id') ?>
    </div>

    <div class="mb-3">
      <label class="form-label-custom" for="nama_jabatan">Nama Jabatan</label>
      <input
        type="text"
        class="form-input-custom <?= isset($errors['nama_jabatan']) ? 'is-invalid' : '' ?>"
        id="nama_jabatan"
        name="nama_jabatan"
        value="<?= e($old['nama_jabatan'] ?? '') ?>"
        placeholder="Contoh: Frontend Developer, HR Manager"
      />
      <?= \messageError('nama_jabatan') ?>
    </div>

    <hr class="divider"/>

    <div class="d-flex justify-content-end gap-2">
      <a href="<?= BASE_URL ?>divisi-jabatan" class="btn-modal-batal" style="text-decoration:none">
        Batal
      </a>
      <button type="submit" class="btn-modal-simpan">
        <i class="bi bi-floppy-fill"></i> Simpan
      </button>
    </div>

  </form>
</div>