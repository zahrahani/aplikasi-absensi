<!-- Bootstrap JS -->
<script src="<?= pathJs('bootstrap') ?>"></script>

<link rel="stylesheet" href="<?= pathCss('divisi-jabatan') ?>"/>
<link rel="stylesheet" href="<?= pathCss('karyawan') ?>"/>

<?= \displayFlashMessage() ?>

<!-- ── Section 1: Header + Filter + Stats ── -->
<section class="karyawan-header-section mb-4">
<h4 class="page-title">Tambah Divisi</h4>
<p class="page-subtitle">Tambahkan divisi baru ke dalam sistem</p>
</section>

<div class="dj-form-card mt-3">
  <form action="<?= BASE_URL ?>divisi-jabatan/create-divisi" method="post">
    <input type="hidden" name="_token" value="<?= $_SESSION['csrf_token'] ?>">

    <div class="mb-3">
      <label class="form-label-custom" for="nama_divisi">Nama Divisi</label>
      <input
        type="text"
        class="form-input-custom <?= isset($errors['nama_divisi']) ? 'is-invalid' : '' ?>"
        id="nama_divisi"
        name="nama_divisi"
        value="<?= e($old['nama_divisi'] ?? '') ?>"
        placeholder="Contoh: Human Resource, IT, Finance"
      />
      <?= \messageError('nama_divisi') ?>
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