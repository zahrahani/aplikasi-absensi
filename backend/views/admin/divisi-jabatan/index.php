<!-- Bootstrap JS -->
<script src="<?= pathJs('bootstrap') ?>"></script>

<!-- Custom CSS -->
<link rel="stylesheet" href="<?= pathCss('divisi-jabatan') ?>"/>
<link rel="stylesheet" href="<?= pathCss('karyawan') ?>"/>

<!-- Flash message -->
<?= \displayFlashMessage() ?>

<!-- ── Section 1: Header + Filter + Stats ── -->
<section class="karyawan-header-section mb-4">
<!-- Judul -->
<h4 class="page-title">Manajemen Divisi & Jabatan</h4>
<p class="page-subtitle">Kelola divisi dan jabatan yang tersedia di perusahaan</p>
</section>

<div class="row g-4 mt-1">

  <!-- Kolom Divisi -->
  <div class="col-12 col-lg-6">
    <div class="dj-card">

      <div class="dj-card-header">
        <div>
          <div class="dj-card-title">Daftar Divisi</div>
          <div class="dj-card-subtitle"><?= count($divisis) ?> divisi terdaftar</div>
        </div>
        <a href="<?= BASE_URL ?>divisi-jabatan/create-divisi" class="btn-tambah">
          <i class="bi bi-plus-lg"></i> Tambah
        </a>
      </div>

      <?php if (empty($divisis)): ?>
        <div class="empty-state">
          <i class="bi bi-diagram-3 empty-icon"></i>
          <div class="empty-text">Belum ada divisi</div>
        </div>
      <?php else: ?>
        <div class="dj-list">
          <?php foreach ($divisis as $divisi): ?>
            <div class="dj-item">
              <div class="dj-item-left">
                <div class="dj-icon">
                  <i class="bi bi-diagram-3-fill"></i>
                </div>
                <div>
                  <div class="dj-item-name"><?= e($divisi['nama_divisi']) ?></div>
                  <div class="dj-item-id"><?= e($divisi['divisi_id']) ?></div>
                </div>
              </div>
              <div class="dj-item-actions">
                  <button type="submit" class="btn-hapus-item hapus-divisi" data-nama="<?= e($divisi['nama_divisi']) ?>" data-id="<?= e($divisi['divisi_id']) ?>" >
                    <i class="bi bi-trash3-fill"></i>
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <!-- Kolom Jabatan -->
  <div class="col-12 col-lg-6">
    <div class="dj-card">

      <div class="dj-card-header">
        <div>
          <div class="dj-card-title">Daftar Jabatan</div>
          <div class="dj-card-subtitle"><?= count($jabatans) ?> jabatan terdaftar</div>
        </div>
        <a href="<?= BASE_URL ?>divisi-jabatan/create-jabatan" class="btn-tambah">
          <i class="bi bi-plus-lg"></i> Tambah
        </a>
      </div>

      <?php if (empty($jabatans)): ?>
        <div class="empty-state">
          <i class="bi bi-person-badge empty-icon"></i>
          <div class="empty-text">Belum ada jabatan</div>
        </div>
      <?php else: ?>
        <div class="dj-list">
          <?php foreach ($jabatans as $jabatan): ?>
            <div class="dj-item">
              <div class="dj-item-left">
                <div class="dj-icon dj-icon-jabatan">
                  <i class="bi bi-person-badge-fill"></i>
                </div>
                <div>
                  <div class="dj-item-name"><?= e($jabatan['nama_jabatan']) ?></div>
                  <div class="dj-item-id"><?= e($jabatan['nama_divisi']) ?></div>
                </div>
              </div>
              <div class="dj-item-actions">
                  <button type="submit" class="btn-hapus-item hapus-jabatan" data-nama="<?= e($jabatan['nama_jabatan']) ?>" data-id="<?= e($jabatan['jabatan_id']) ?>" >
                    <i class="bi bi-trash3-fill"></i>
                  </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>

</div>

<script>

const bsModalHapusJabatan = new bootstrap.Modal(document.getElementById('modalHapusJabatan'));
document.querySelectorAll('.hapus-jabatan').forEach((btn) => {
  btn.addEventListener('click', (e) => {
    console.log()
    document.getElementById('hapus_nama_jabatan').textContent = btn.getAttribute('data-nama');
    document.getElementById('input_jabatan_id').value = btn.getAttribute('data-id');

    bsModalHapusJabatan.show()
  })
});

const bsModalHapusDivisi = new bootstrap.Modal(document.getElementById('modalHapusDivisi'));
document.querySelectorAll('.hapus-divisi').forEach((btn) => {
  btn.addEventListener('click', (e) => {
    document.getElementById('hapus_nama_divisi').textContent = btn.getAttribute('data-nama');
    document.getElementById('input_divisi_id').value = btn.getAttribute('data-id');

    bsModalHapusDivisi.show()
  })
});



</script>