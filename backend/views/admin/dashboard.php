<!-- Custom CSS -->
<link rel="stylesheet" href="<?= pathCss('dashboard') ?>"/>


<!-- ── Ringkasan Kehadiran ── -->
<section class="summary-card mb-4">
  <h5 class="summary-title">Ringkasan Kehadiran Hari Ini</h5>
  <div class="row g-3">

    <!-- Hadir -->
    <div class="col-6 col-md-3">
      <div class="stat-box hadir">
        <div class="stat-top">
          <i class="bi bi-check-square-fill stat-icon"></i>
          <span class="stat-number" id="stat-hadir">0</span>
        </div>
        <div class="stat-label">Total Hadir</div>
      </div>
    </div>

    <!-- Terlambat -->
    <div class="col-6 col-md-3">
      <div class="stat-box lambat">
        <div class="stat-top">
          <i class="bi bi-clock-history stat-icon"></i>
          <span class="stat-number" id="stat-lambat">0</span>
        </div>
        <div class="stat-label">Terlambat</div>
      </div>
    </div>

    <!-- Tidak Hadir -->
    <div class="col-6 col-md-3">
      <div class="stat-box absen">
        <div class="stat-top">
          <i class="bi bi-x-circle-fill stat-icon"></i>
          <span class="stat-number" id="stat-absen">0</span>
        </div>
        <div class="stat-label">Tidak Hadir</div>
      </div>
    </div>

    <!-- Izin Pending -->
    <div class="col-6 col-md-3">
      <div class="stat-box izin">
        <div class="stat-top">
          <i class="bi bi-file-earmark-text stat-icon"></i>
          <span class="stat-number" id="stat-izin">0</span>
        </div>
        <div class="stat-label">Izin Pending</div>
      </div>
    </div>

  </div>
</section>

<!-- ── Charts Row ── -->
<div class="row g-4">

  <!-- Grafik Bar Mingguan -->
  <div class="col-12 col-lg-6">
    <div class="chart-card h-100">
      <div class="chart-card-title">Grafik Kehadiran Mingguan</div>
      <div class="chart-wrapper">
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Grafik Donut -->
  <div class="col-12 col-lg-6">
    <div class="chart-card h-100">
      <div class="chart-card-title">Kehadiran Hari Ini</div>
      <div class="donut-wrap">
        <!-- Canvas -->
        <div class="donut-container">
          <canvas id="donutChart"></canvas>
          <div class="donut-center-label">
            <span class="donut-pct">0%</span>
            <span class="donut-sub">Hadir</span>
          </div>
        </div>
        <!-- Legend -->
        <div class="donut-legend">
          <div class="legend-item">
            <div class="legend-label-row">
              <span class="legend-name">Hadir</span>
              <div class="legend-bar hadir-bar"></div>
            </div>
            <div class="legend-count">0 Orang</div>
          </div>
          <div class="legend-item">
            <div class="legend-label-row">
              <span class="legend-name">Terlambat</span>
              <div class="legend-bar lambat-bar"></div>
            </div>
            <div class="legend-count">0 Orang</div>
          </div>
          <div class="legend-item">
            <div class="legend-label-row">
              <span class="legend-name">Tidak Hadir</span>
              <div class="legend-bar absen-bar"></div>
            </div>
            <div class="legend-count">0 Orang</div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- /charts row -->

<!-- Bootstrap JS -->
<script src="<?= pathJs('bootstrap') ?>"></script>
<!-- Chart.js -->
<script src="<?= pathJs('chart') ?>"></script>
<!-- Custom JS -->
<script src="<?= pathJs('dashboard') ?>"></script>
