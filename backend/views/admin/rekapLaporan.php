<!-- Custom CSS -->
<link rel="stylesheet" href="<?= pathCss('rekap') ?>"/>


<!-- ── Section 1: Header + Filter + Stats ── -->
<section class="rekap-header-section mb-4">

  <!-- Judul -->
  <h4 class="page-title">Rekap Laporan</h4>
  <p class="page-subtitle">Laporan Kehadiran Bulanan</p>

  <!-- Toolbar: Filter Bulan, Filter Divisi, Export PDF -->
  <div class="toolbar d-flex flex-wrap align-items-center gap-2 mt-3 mb-4">
<!-- Filter Bulan — ganti dropdown dengan input bulan & tahun -->
<div class="d-flex align-items-center gap-2">
    <!-- Dropdown bulan -->
    <div class="dropdown">
        <button class="btn-filter dropdown-toggle" type="button"
                data-bs-toggle="dropdown" id="filterBulanLabel">
            – Pilih Bulan –
        </button>
        <ul class="dropdown-menu" id="filterBulanMenu">
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="01">Januari</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="02">Februari</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="03">Maret</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="04">April</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="05">Mei</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="06">Juni</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="07">Juli</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="08">Agustus</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="09">September</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="10">Oktober</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="11">November</a></li>
            <li><a class="dropdown-item filter-bulan-item" href="#" data-value="12">Desember</a></li>
        </ul>
    </div>

    <!-- Input tahun -->
    <input class="btn-filter" type="number" id="filterTahunInput"
           min="2020" max="2099"
           style="width:100px;text-align:center;">
  </div>

<div class="dropdown">
  <button class="btn-filter dropdown-toggle" type="button"
  data-bs-toggle="dropdown" id="filterDivisiLabel">
  Semua Divisi
</button>
<ul class="dropdown-menu">
  <li><a class="dropdown-item filter-divisi" data-value="semua" href="#">Semua Divisi</a></li>
  <?php foreach ($divisis as $divisi) { ?>
  <li><a class="dropdown-item filter-divisi" data-value="<?= htmlspecialchars($divisi['nama_divisi']) ?>" href="#"><?= htmlspecialchars($divisi['nama_divisi']) ?></a></li>
  <?php } ?>
</ul>
</div>

<!-- Export PDF -->
<button class="btn-export ms-auto" id="btnExport">
  Export PDF &nbsp;<i class="bi bi-arrow-right"></i>
</button>
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
      <div class="stat-number green" id="stat-kehadiran">0%</div>
      <div class="stat-label">Rata-rata Kehadiran</div>
    </div>
  </div>
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" id="stat-terlambat">0%</div>
      <div class="stat-label">Rata-rata Keterlambatan</div>
    </div>
  </div>
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" id="stat-alpha">0%</div>
      <div class="stat-label">Rata-rata Alpha</div>
    </div>
  </div>
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" id="stat-izin">0</div>
      <div class="stat-label">Total Izin Disetujui</div>
    </div>
  </div>
</div>

</section>

<!-- ── Section 2: Tabel Rekap per Karyawan ── -->
<section class="rekap-table-section">

  <div class="table-section-header d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h5 class="table-title" id="table-title">Rekap per Karyawan – Februari 2026</h5>
    <!-- Search Karyawan -->
    <div class="search-box d-flex align-items-center">
      <i class="bi bi-search search-icon"></i>
      <input type="text" id="searchInput" class="search-input" placeholder="Cari Karyawan...."/>
    </div>
  </div>

  <!-- Tabel -->
  <div class="table-responsive">
    <table class="rekap-table w-100">
      <thead>
        <tr>
          <th>Karyawan</th>
          <th>Divisi</th>
          <th>Hadir</th>
          <th>Terlambat</th>
          <th>Izin</th>
          <th>Absen</th>
          <th>% Kehadiran</th>
          <th>Detail</th>
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
<script src="<?= pathJs('rekap') ?>"></script>
