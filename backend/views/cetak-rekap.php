<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="base-url" content="<?= BASE_URL ?>">
  <title>Rekap Kehadiran — CV. Nafihaka Creative</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= pathCss('cetak-rekap') ?>">
</head>
<body>

  <!-- Preview bar — hilang saat print -->
  <div class="preview-bar no-print">
    <span>Preview Surat Rekap Kehadiran — CV. Nafihaka Creative</span>
    <div class="preview-actions">
      <button class="btn-print" onclick="window.print()">
        Cetak / Simpan PDF
      </button>
      <button class="btn-back" onclick="window.history.back()">
        Kembali
      </button>
    </div>
  </div>

  <!-- Halaman surat -->
  <div class="page" id="page">

    <!-- Header perusahaan -->
    <div class="header">
      <div>
        <div class="company-name">CV. NAFIHAKA CREATIVE</div>
        <div class="company-sub">Jl. Magelang No. 12, Yogyakarta 55123</div>
        <div class="company-sub">Telp: (0274) 123-456 &nbsp;|&nbsp; nafihaka@creative.co.id</div>
      </div>
      <div class="doc-title">
        <h2>Laporan Rekap Kehadiran</h2>
        <p>Periode Bulanan</p>
      </div>
    </div>

    <!-- Nomor dokumen -->
    <div class="doc-no">
      <span>No. Dokumen: <b id="doc-no">NK/RK/-/-</b></span>
      <span>Periode: <b id="doc-periode">-</b></span>
      <span>Tanggal Cetak: <b id="doc-tanggal">-</b></span>
    </div>

    <!-- Informasi laporan -->
    <div class="section-title">Informasi Laporan</div>
    <div class="info-grid">
      <div class="info-row">
        <span class="info-label">Nama Perusahaan</span>
        <span class="info-value">CV. Nafihaka Creative</span>
      </div>
      <div class="info-row">
        <span class="info-label">Total Hari Kerja</span>
        <span class="info-value" id="info-hari-kerja">-</span>
      </div>
      <div class="info-row">
        <span class="info-label">Periode Laporan</span>
        <span class="info-value" id="info-periode">-</span>
      </div>
      <div class="info-row">
        <span class="info-label">Dicetak Oleh</span>
        <span class="info-value"><?= $_SESSION['nama_lengkap'] ?? 'Administrator' ?></span>
      </div>
      <div class="info-row">
        <span class="info-label">Total Karyawan</span>
        <span class="info-value" id="info-total-karyawan">-</span>
      </div>
      <div class="info-row">
        <span class="info-label">Filter Divisi</span>
        <span class="info-value" id="info-divisi">Semua Divisi</span>
      </div>
    </div>

    <!-- Ringkasan -->
    <div class="section-title">Ringkasan Kehadiran</div>
    <div class="insight-grid">
      <div class="insight-box">
        <div class="insight-num" id="ins-total">0</div>
        <div class="insight-lbl">Total Karyawan</div>
      </div>
      <div class="insight-box green">
        <div class="insight-num" id="ins-kehadiran">0%</div>
        <div class="insight-lbl">Rata Kehadiran</div>
      </div>
      <div class="insight-box amber">
        <div class="insight-num" id="ins-terlambat">0</div>
        <div class="insight-lbl">Rata Terlambat</div>
      </div>
      <div class="insight-box red">
        <div class="insight-num" id="ins-alpha">0</div>
        <div class="insight-lbl">Rata Alpha</div>
      </div>
      <div class="insight-box">
        <div class="insight-num" id="ins-izin">0</div>
        <div class="insight-lbl">Total Izin</div>
      </div>
    </div>

    <!-- Tabel detail -->
    <div class="section-title">Detail Rekap per Karyawan</div>
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Karyawan</th>
          <th>Divisi</th>
          <th style="text-align:center">Hadir</th>
          <th style="text-align:center">Terlambat</th>
          <th style="text-align:center">Izin</th>
          <th style="text-align:center">Alpha</th>
          <th style="text-align:center">% Hadir</th>
        </tr>
      </thead>
      <tbody id="tabel-body">
        <tr>
          <td colspan="8" style="text-align:center;padding:20px;color:#9ca3af">
            Memuat data...
          </td>
        </tr>
      </tbody>
    </table>

    <div class="keterangan">
      Keterangan: Hadir = tepat waktu, Alpha = tidak hadir tanpa keterangan
    </div>

    <!-- Footer & tanda tangan -->
    <div class="footer">
      <div>
        <div class="footer-note">Laporan ini digenerate otomatis oleh sistem.</div>
        <div class="footer-copy">CV. Nafihaka Creative &copy; <?= date('Y') ?> — Dokumen resmi perusahaan</div>
      </div>
      <div class="ttd-wrap">
        <div class="ttd-box">
          <div class="ttd-space"></div>
          <div class="ttd-name"><?= $_SESSION['nama_lengkap'] ?? 'Administrator' ?></div>
          <div class="ttd-role">Dicetak Oleh</div>
        </div>
        <div class="ttd-box">
          <div class="ttd-space"></div>
          <div class="ttd-name">Direktur</div>
          <div class="ttd-role">Mengetahui</div>
        </div>
      </div>
    </div>

  </div>

  <script src="<?= pathJs('cetak-rekap') ?>"></script>
</body>
</html>