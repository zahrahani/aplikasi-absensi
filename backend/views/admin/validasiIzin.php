<!-- Custom CSS -->
<link rel="stylesheet" href="<?= pathCss('validasiIzin') ?>"/>

<!-- Page Header -->
<div class="validasi-header-section mb-4">
  <h4 class="page-title">Validasi Pengajuan Izin</h4>
  <!-- Tab Status -->
  <ul class="nav nav-tabs mb-3" id="tabStatus">
    <li class="nav-item">
      <a class="nav-link active" href="#" data-status="pending">
        Menunggu
        <span class="badge bg-danger ms-1" id="badge-pending">0</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-status="approved">
        Disetujui
        <span class="badge bg-success ms-1" id="badge-disetujui">0</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-status="rejected">
        Ditolak
        <span class="badge bg-secondary ms-1" id="badge-ditolak">0</span>
      </a>
    </li>
  </ul>
  <!-- Toolbar: Search + Filter + Bulk Actions -->
  <div class="toolbar d-flex flex-wrap align-items-center gap-2 mt-3">

    <!-- Search -->
    <div class="search-box d-flex align-items-center">
      <i class="bi bi-search search-icon"></i>
      <input
      type="text"
      id="searchInput"
      class="search-input"
      placeholder="Cari Pengajuan...."
      />
    </div>

    <!-- Filter Jenis -->
    <div class="dropdown">
      <button
      class="btn-filter dropdown-toggle"
      type="button"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      id="filterLabel"
      >
      Semua Jenis
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item filter-option" data-value="semua" href="#">Semua Jenis</a></li>
      <?php foreach($jenisAbsensi as $jenis) { ?>

      <li><a class="dropdown-item filter-option" data-value="<?= strtolower($jenis['nama_jenis']) ?>" href="#"><?= $jenis['nama_jenis'] ?></a></li>
      
      <?php } ?>

    </ul>
  </div>
  <!-- Bulk Actions -->
  <div class="ms-auto d-flex gap-2">
    <button class="btn-bulk-tolak" id="btnTolakSemua">
      <i class="bi bi-x-lg"></i> Tolak Semua
    </button>
    <button class="btn-bulk-setujui" id="btnSetujuiSemua">
      <i class="bi bi-check2-square"></i> Setujui Semua
    </button>
  </div>
</div>
</div>

<!-- ── Daftar Kartu Pengajuan ── -->
<div id="pengajuan-list">
  <!-- Kartu pengajuan dirender oleh JS -->
</div>

<!-- Empty State -->
<div id="empty-state" class="empty-state d-none">
  <i class="bi bi-inbox empty-icon"></i>
  <p class="empty-text">Tidak ada pengajuan yang ditemukan</p>
</div>
<!-- Bootstrap JS -->
<script src="<?= pathJs('bootstrap.bundle.min') ?>"></script>

<!-- Custom JS -->
<script src="<?= pathJs('validasiIzin') ?>"></script>
