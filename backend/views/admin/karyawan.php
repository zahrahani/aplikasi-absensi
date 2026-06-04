<!-- Custom CSS -->
<link rel="stylesheet" href="<?= pathCss('karyawan'); ?>"/>

<!-- ── Section 1: Header + Filter + Stats ── -->
<section class="karyawan-header-section mb-4">

  <!-- Judul -->
  <h4 class="page-title">Data Karyawan</h4>
  <p class="page-subtitle">Informasi Seluruh Karyawan Aktif</p>

  <!-- Toolbar: Filter Divisi, Filter Status, Tombol Tambah -->
  <div class="toolbar d-flex flex-wrap align-items-center gap-2 mt-3 mb-4">

    <!-- Filter Divisi -->
    <div class="dropdown">
      <button class="btn-filter dropdown-toggle" type="button"
      data-bs-toggle="dropdown" id="filterDivisiLabel">
      Semua Divisi
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item filter-divisi" data-value="semua" href="#">Semua Divisi</a></li>
      <?php foreach ($divisis as $divisi) { ?>
      <li><a class="dropdown-item filter-divisi" data-value="<?= $divisi['nama_divisi'] ?>" href="#"><?= $divisi['nama_divisi'] ?></a></li>
      <?php } ?>
    </ul>
  </div>

  <!-- Filter Status -->
  <div class="dropdown">
    <button class="btn-filter dropdown-toggle" type="button"
    data-bs-toggle="dropdown" id="filterStatusLabel">
    Semua Status
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item filter-status" data-value="semua" href="#">Semua Status</a></li>
    <li><a class="dropdown-item filter-status" data-value="Aktif" href="#">Aktif</a></li>
    <li><a class="dropdown-item filter-status" data-value="Nonaktif" href="#">Non-aktif</a></li>

  </ul>
</div>

<!-- Tombol Tambah Karyawan -->
<a class="btn-tambah ms-auto" id="btnTambah" href="<?= BASE_URL . 'karyawan/create' ?>">
  <i class="bi bi-plus-lg"></i>&nbsp; Tambah Karyawan
</a>
</div>

<!-- Stat Cards -->
<div class="row g-3" id="stat-cards">
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" id="stat-total">0</div>
      <div class="stat-label">Total Karyawan</div>
    </div>
  </div>
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" id="stat-aktif">0</div>
      <div class="stat-label">Karyawan Aktif</div>
    </div>
  </div>
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" id="stat-cuti">0</div>
      <div class="stat-label">Sedang Cuti</div>
    </div>
  </div>
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" id="stat-divisi">0</div>
      <div class="stat-label">Total Divisi</div>
    </div>
  </div>
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" id="stat-baru">0</div>
      <div class="stat-label">Karyawan Baru (Bulan Ini)</div>
    </div>
  </div>
</div>

</section>

<?= \displayFlashMessage() ?>


<!-- ── Section 2: Tabel Data Karyawan ── -->
<section class="karyawan-table-section">

  <div class="table-section-header d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h5 class="table-title" id="table-title">Daftar Karyawan Aktif</h5>
    <!-- Search Karyawan -->
    <div class="search-box d-flex align-items-center">
      <i class="bi bi-search search-icon"></i>
      <input type="text" id="searchInput" class="search-input" placeholder="Cari Karyawan...."/>
    </div>
  </div>

  <!-- Tabel -->
  <div class="table-responsive">
    <table class="karyawan-table w-100">
      <thead>
        <tr>
          <th>Karyawan</th>
          <th>Divisi</th>
          <th>Jabatan</th>
          <th>No. HP</th>
          <th>Tgl. Bergabung</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="table-body">
        <!-- Dirender oleh JS -->
      </tbody>
    </table>
  </div>

  <!-- Empty State -->
  <div id="empty-state" class="empty-state d-none">
    <i class="bi bi-inbox empty-icon"></i>
    <p class="empty-text">Tidak ada data karyawan yang ditemukan</p>
  </div>

  <!-- Pagination -->
  <div class="d-flex align-items-center justify-content-between mt-4 flex-wrap gap-2">
    <div class="pagination-info" id="pagination-info"></div>
    <div class="d-flex gap-2" id="pagination-btns"></div>
  </div>

</section>

<!-- Bootstrap JS -->
<script src="<?= pathJs('bootstrap.bundle.min') ?>"></script>

<!-- Custom JS -->
<script src="<?= pathJs('karyawan') ?>"></script>
