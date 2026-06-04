<?php /* View: jadwalKaryawan.php */ ?>

<!-- CSS Spesifik -->
<link rel="stylesheet" href="<?= pathCss('jadwalKaryawan') ?>"/>

<!-- ════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════ -->
<div class="jadwal-header-section">
  <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
    <div>
      <h4 class="page-title">Pengaturan Jadwal Shift</h4>
      <p class="page-subtitle">Kelola dan ubah shift karyawan</p>
    </div>
  </div>

  <!-- Toolbar -->
  <div class="toolbar d-flex flex-wrap align-items-center gap-2">

    <!-- Search -->
    <div class="search-box">
      <i class="bi bi-search search-icon"></i>
      <input type="text" id="searchInput" class="search-input"
      placeholder="Cari karyawan..."/>
    </div>

    <!-- Filter Divisi -->
    <div class="dropdown">
      <button class="btn-filter dropdown-toggle" type="button"
      data-bs-toggle="dropdown" id="filterDivisiLabel">
      Semua Divisi
    </button>
    <ul class="dropdown-menu" id="filterDivisiMenu">
      <li>
        <a class="dropdown-item filter-divisi-item active" href="#"
        data-value="semua" data-label="Semua Divisi">Semua Divisi</a>
      </li>
      <!-- Diisi dinamis dari API -->
    </ul>
  </div>

  <!-- Filter Shift -->
  <div class="dropdown">
    <button class="btn-filter dropdown-toggle" type="button"
    data-bs-toggle="dropdown" id="filterShiftLabel">
    Semua Shift
  </button>
  <ul class="dropdown-menu" id="filterShiftMenu">
    <li>
      <a class="dropdown-item filter-shift-item active" href="#"
      data-value="semua" data-label="Semua Shift">Semua Shift</a>
    </li>
    <!-- Diisi dinamis dari API -->
  </ul>
</div>

<!-- Filter Status -->
<div class="dropdown">
  <button class="btn-filter dropdown-toggle" type="button"
  data-bs-toggle="dropdown" id="filterStatusLabel">
  Semua Status
</button>
<ul class="dropdown-menu">
  <li><a class="dropdown-item filter-status-item active" href="#" data-value="semua">Semua Status</a></li>
  <li><a class="dropdown-item filter-status-item" href="#" data-value="aktif">Sedang Shift</a></li>
  <li><a class="dropdown-item filter-status-item" href="#" data-value="selesai">Sudah Pulang</a></li>
  <li><a class="dropdown-item filter-status-item" href="#" data-value="belum">Belum Masuk</a></li>
</ul>
</div>
</div>


<!-- ════════════════════════════════════════
     STAT CARDS
════════════════════════════════════════ -->
<div class="row g-3 mt-2">
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" id="stat-total">–</div>
      <div class="stat-label">Total Karyawan</div>
    </div>
  </div>
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" style="color:#e03b3b;" id="stat-aktif">–</div>
      <div class="stat-label">Sedang Shift</div>
    </div>
  </div>
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" style="color:var(--green);" id="stat-selesai">–</div>
      <div class="stat-label">Sudah Pulang</div>
    </div>
  </div>
  <div class="col-6 col-md">
    <div class="stat-box">
      <div class="stat-number green" style="color:var(--muted);" id="stat-belum">–</div>
      <div class="stat-label">Belum Masuk</div>
    </div>
  </div>
</div>

</div>

<!-- ════════════════════════════════════════
     TABEL JADWAL
════════════════════════════════════════ -->
<div class="jadwal-table-wrap">
  <table class="jadwal-table">
    <thead>
      <tr>
        <th>Karyawan</th>
        <th>Divisi</th>
        <th>Shift Aktif</th>
        <th>Jam Masuk</th>
        <th>Jam Pulang</th>
        <th>Status Hari Ini</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody id="jadwal-table-body">
      <!-- Dirender oleh JS -->
    </tbody>
  </table>
</div>

<!-- Empty State -->
<div id="empty-state" class="empty-state d-none">
  <i class="bi bi-calendar-x empty-icon"></i>
  <p class="empty-text">Tidak ada karyawan ditemukan</p>
</div>


<!-- Bootstrap JS -->
<script src="<?= pathJs('bootstrap.bundle.min') ?>"></script>

<!-- JS Spesifik -->
<script src="<?= pathJs('jadwalKaryawan') ?>"></script>